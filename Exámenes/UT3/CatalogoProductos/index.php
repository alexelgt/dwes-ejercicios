<?php

require 'vendor/autoload.php';
require 'funciones.php';
require 'src/modelo/Usuario.php';
require 'src/modelo/Categoria.php';
require 'src/modelo/Producto.php';
require 'src/app/BD.php';

use eftec\bladeone\BladeOne;
use \PDO as PDO;
use \App\Usuario as Usuario;
use \App\Categoria as Categoria;
use \App\Producto as Producto;
use \App\BD as BD;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';

$blade = new BladeOne($views, $cache);

$titulo = "Catálogo de Productos";

//==== Comprobación base de datos ====
try {
    $bd = BD::getConexion();
} catch (PDOException $e) {
    $titulo .= " - Error fatal";
    $mensaje_error = "No se ha podido conectar con la base de datos.";

    echo $blade->run("error_fatal", ["titulo" => $titulo, "mensaje_error" => $mensaje_error]);
    exit();
}
//== Comprobación base de datos ==

session_start();

//==== Camino si se entra por primera vez sin sesión guardada ====
if (empty($_POST) && !isset($_SESSION["usuario"])) {
    $titulo .= " - Inicio";

    echo $blade->run("inicio_sesion", ["titulo" => $titulo]);
    exit();
}
//== Camino si se entra por primera vez sin sesión guardada ==

//==== Elección de camino ====
$tipo_formulario = filter_input(INPUT_POST, "tipo_formulario");

// Se pide volver a menú inicial
if ($tipo_formulario === "menu_inicial") {
    session_destroy();
    $titulo .= " - Inicio";

    echo $blade->run("inicio_sesion", ["titulo" => $titulo]);
    exit();
}
// Se pide volver al perfil o se recarga la página con datos de sesión existentes
else if ((empty($_POST) && isset($_SESSION["usuario"])) || $tipo_formulario === "volver_perfil") {
    $usuario = $_SESSION["usuario"];

    obtener_perfil($blade, $bd, $titulo, $usuario);
}
// Se realiza un intento de inicio de sesión
else if ($tipo_formulario === "inicio_sesion") {
    $nombre = filter_input(INPUT_POST, "nombre");
    $nombre_valido = valid_input($nombre) && strlen($nombre) > 0;
    
    $clave = filter_input(INPUT_POST, "clave");
    $clave_valido = valid_input($clave) && strlen($clave) > 0;
    
    if ($nombre_valido && $clave_valido) {
        $usuario = Usuario::obtenerPorLogin($bd, $nombre, $clave);
        
        if (!is_null($usuario)) {
            $_SESSION["usuario"] = $usuario;

            obtener_perfil($blade, $bd, $titulo, $usuario);
        }
        else {
            $mensajes_error = ["El usuario no existe."];
            
            $titulo .= " - Inicio";

            echo $blade->run("inicio_sesion", ["titulo" => $titulo, "mensajes_error" => $mensajes_error]);
            exit();
        }
    }
    else {
        $mensajes_error = [];
        
        if (!$nombre_valido) {
            $mensajes_error[] = "Nombre no válido (introduce algo).";
        }
        
        if (!$clave_valido) {
            $mensajes_error[] = "Contraseña no válida (introduce algo).";
        }
        
        $titulo .= " - Inicio";

        echo $blade->run("inicio_sesion", ["titulo" => $titulo, "mensajes_error" => $mensajes_error]);
        exit();
    }
}
// Se elige una categoría dentro del perfil
else if (preg_match("/elegirCategoria_/", $tipo_formulario)) {
    $id_categoria = (int) explode("_", $tipo_formulario)[1];
    
    $usuario = $_SESSION["usuario"];
    
    $categoria = Categoria::obtenerPorId($bd, $id_categoria);
    
    $_SESSION["categoria"] = $categoria;
    
    $productos = Producto::obtenerPorCategoria($bd, $categoria->getId());
    
    obtener_perfil($blade, $bd, $titulo, $usuario);
}
// Se elige añadir un producto por lo que se manda al formulario correspondiente
else if ($tipo_formulario === "form_add_producto") {
    $categoria = $_SESSION["categoria"];
    
    $titulo .= " - Añadir producto (" . $categoria->getNombre() . ")";
    
    echo $blade->run("add_producto", ["titulo" => $titulo, "categoria" => $categoria]);
    exit();
}
// Se reciben los datos del formulario de añadir producto
else if ($tipo_formulario === "add_producto") {
    $categoria = $_SESSION["categoria"];
    
    $nombre = filter_input(INPUT_POST, "nombre");
    $nombre_valido = valid_input($nombre) && strlen($nombre) > 0 && strlen($nombre) <= 100; // la base de datos tiene varchar(100)
    
    $precio = filter_input(INPUT_POST, "precio", FILTER_VALIDATE_FLOAT);
    $precio_valido = valid_input($precio) && $precio >= 0 && preg_match("/^[0-9]{0,3}(\.[0-9]{0,2})?$/", $precio);
    
    if ($nombre_valido && $precio_valido) {
        $producto = new Producto(null, $nombre, $precio, $categoria->getId());
        
        $producto->add($bd);
        
        $usuario = $_SESSION["usuario"];
        
        obtener_perfil($blade, $bd, $titulo, $usuario);
    }
    else {
        $mensajes_error = [];
        
        if (!$nombre_valido) {
            $mensajes_error[] = "Nombre no válido (tiene que tener una longitud entre 1 y 100).";
        }
        
        if (!$precio_valido) {
            $mensajes_error[] = "Precio no válido (tiene que tener un valor entre 0 y 999.99).";
        }
        
        $titulo .= " - Añadir producto (" . $categoria->getNombre() . ")";
    
        echo $blade->run("add_producto", ["titulo" => $titulo, "categoria" => $categoria, "mensajes_error" => $mensajes_error]);
        exit();
    }
}
// Se elige editar un producto por lo que se manda al formulario correspondiente
else if (preg_match("/formEditarProducto_/", $tipo_formulario)) {
    $id_producto = (int) explode("_", $tipo_formulario)[1];
    $producto = Producto::obtenerPorId($bd, $id_producto);
    
    $nombre = $producto->getNombre();
    $precio = $producto->getPrecio();
    $categoria_id = $producto->getId_categoria();
    
    obtener_editar_perfil($blade, $bd, $titulo, $id_producto, $nombre, $precio, $categoria_id);
}
// Se reciben los datos del formulario de editar producto
else if (preg_match("/editarProducto_/", $tipo_formulario)) {
    $id_producto = (int) explode("_", $tipo_formulario)[1];
    $producto = Producto::obtenerPorId($bd, $id_producto);
    
    $nombre = filter_input(INPUT_POST, "nombre");
    $nombre_valido = valid_input($nombre) && strlen($nombre) > 0 && strlen($nombre) <= 100; // la base de datos tiene varchar(100)
    
    $precio = filter_input(INPUT_POST, "precio", FILTER_VALIDATE_FLOAT);
    $precio_valido = valid_input($precio) && $precio >= 0 && preg_match("/^[0-9]{0,3}(\.[0-9]{0,2})?$/", $precio);
    
    $categoria_id = filter_input(INPUT_POST, "categoria", FILTER_VALIDATE_INT);
    $categoria_id_valido = valid_input($categoria_id);
    
    if ($nombre_valido && $precio_valido && $categoria_id_valido) {
        $categoria = Categoria::obtenerPorId($bd, $categoria_id);
        
        $_SESSION["categoria"] = $categoria;
        
        $producto = new Producto($id_producto, $nombre, $precio, $categoria->getId());
        
        $producto->update($bd);
        
        $usuario = $_SESSION["usuario"];
        
        obtener_perfil($blade, $bd, $titulo, $usuario);
    }
    else {
        $mensajes_error = [];
        
        if (!$nombre_valido) {
            $mensajes_error[] = "Nombre no válido (tiene que tener una longitud entre 1 y 100).";
        }
        
        if (!$precio_valido) {
            $mensajes_error[] = "Precio no válido (tiene que tener un valor entre 0 y 999.99).";
        }
        
        if (!$categoria_id_valido) {
            $mensajes_error[] = "Categoría no válida."; //realmente debería llegar siempre un valor válido pero meh
        }
        
        obtener_editar_perfil($blade, $bd, $titulo, $id_producto, $nombre, $precio, $categoria_id, $mensajes_error);
    }
}
// En caso de no detectar el formulario recibido se muestra un mensaje de error (en circunstancias normales no se debería pasar por este camino)
else {
    $titulo .= " - Error fatal";
    $mensaje_error = "No se ha reconocido el formulario recibido.";

    echo $blade->run("error_fatal", ["titulo" => $titulo, "mensaje_error" => $mensaje_error]);
    exit();
}
//== Elección de camino ==

//==== Función que muestra la vista del perfil ====
function obtener_perfil(BladeOne $blade, PDO $bd, string $titulo, Usuario $usuario): void {
    $titulo .= " - Perfil";
    
    $categorias = Categoria::obtenerTodas($bd);
    
    if (isset($_SESSION["categoria"])) {
        $categoria = $_SESSION["categoria"];
        $productos = Producto::obtenerPorCategoria($bd, $categoria->getId());
    }
    else {
        $categoria = null;
        $productos = null;
    }

    echo $blade->run("perfil", ["titulo" => $titulo, "usuario" => $usuario, "categorias" => $categorias, "categoria_elegida" => $categoria, "productos" => $productos]);
    exit();
}
//== Función que muestra la vista del perfil ==

//==== Función que muestra la vista de editar un producto ====
function obtener_editar_perfil(BladeOne $blade, PDO $bd, string $titulo, int $id_producto, string $nombre, float $precio, int $categoria_id, ?array $mensajes_error=null): void {
    $categorias = Categoria::obtenerTodas($bd);
    
    $titulo .= " - Editar producto (" . $nombre . ")";
    
    echo $blade->run("editar_producto", ["titulo" => $titulo, "id_producto" => $id_producto, "nombre" => $nombre, "precio" => $precio, "categoria_id" => $categoria_id, "categorias" => $categorias, "mensajes_error" => $mensajes_error]);
    exit();
}
//== Función que muestra la vista de editar un producto ==