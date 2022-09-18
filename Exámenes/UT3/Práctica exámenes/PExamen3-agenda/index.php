<?php

require 'vendor/autoload.php';
require 'config.php';
require 'funciones.php';

require 'src/modelo/Contacto.php';
require 'src/modelo/Usuario.php';

use eftec\bladeone\BladeOne;
use \PDO as PDO;
use \App\Contacto as Contacto;
use \App\Usuario as Usuario;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';

$blade = new BladeOne($views, $cache);

try {
    $bd = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USERNAME, PASSWORD);
} catch (PDOException $e) {
    echo $blade->run("error_fatal", ["titulo" => "Agenda contactos - Error fatal", "mensaje_error" => "No se ha podido conectar con la base de datos"]);
    exit();
}

session_start();

if (empty($_POST) && empty($_SESSION["usuario"])) {
    echo $blade->run("pantalla_inicial", ["titulo" => "Agenda contactos"]);
    exit();
}

$tipo_formulario = filter_input(INPUT_POST, "tipo_formulario");

if ($tipo_formulario === "menu_inicial") {
    session_destroy();
    echo $blade->run("pantalla_inicial", ["titulo" => "Agenda contactos"]);
    exit();
}
else if ($tipo_formulario === "form_inicio_sesion") {
    echo $blade->run("inicio_sesion", ["titulo" => "Agenda contactos - Inicio sesión"]);
    exit();
}
else if ($tipo_formulario === "inicio_sesion") {
    $nombre = filter_input(INPUT_POST, "nombre");
    $nombre_valido = valid_input($nombre) && strlen($nombre) > 0;
    
    $clave = filter_input(INPUT_POST, "clave");
    $clave_valido = valid_input($clave) && strlen($clave) > 0;
    
    if ($nombre_valido && $clave_valido) {
        $usuario = Usuario::obtenerUsuarioPorLogin($bd, $nombre, $clave);
        
        if (!is_null($usuario)) {
            $_SESSION["usuario"] = $usuario;
            $contactos = Contacto::obtenerContactosUsuario($bd, $usuario->getId());
    
            echo $blade->run("perfil", ["titulo" => "Agenda contactos - Perfil", "usuario" => $usuario, "contactos" => $contactos]);
            exit();
        }
        else {
            $mensajes_error = ["El usuario no existe"];
            echo $blade->run("inicio_sesion", ["titulo" => "Agenda contactos - Inicio sesión", "mensajes_error" => $mensajes_error]);
            exit();
        }
    }
    else {
        $mensajes_error = [];

        if (!$nombre_valido) {
            $mensajes_error[] = "El nombre introducido no es válido.";
        }
        
        if (!$clave_valido) {
            $mensajes_error[] = "La contraseña introducida no es válida.";
        }
        
        echo $blade->run("inicio_sesion", ["titulo" => "Agenda contactos - Inicio sesión", "mensajes_error" => $mensajes_error]);
        exit();
    }
}
else if ($tipo_formulario === "form_registro") {
    echo $blade->run("registro", ["titulo" => "Agenda contactos - Registro"]);
    exit();
}
else if ($tipo_formulario === "registro") {
    $nombre = filter_input(INPUT_POST, "nombre");
    $nombre_valido = valid_input($nombre) && strlen($nombre) > 0;
    
    $clave = filter_input(INPUT_POST, "clave");
    $clave_valido = valid_input($clave) && strlen($clave) > 0;
    
    if ($nombre_valido && $clave_valido) {
        $usuario = new Usuario(
                $id=null,
                $nombre=$nombre,
                $password=$clave
        );
        
        $resultado_registro = $usuario->registrarUsuario($bd);
        
        if ($resultado_registro == Usuario::REGISTRO_CORRECTO) {
            $_SESSION["usuario"] = $usuario;
            $contactos = Contacto::obtenerContactosUsuario($bd, $usuario->getId());
    
            echo $blade->run("perfil", ["titulo" => "Agenda contactos - Perfil", "usuario" => $usuario, "contactos" => $contactos]);
            exit();
        }
        else if ($resultado_registro == Usuario::REGISTRO_INCORRECTO) {
            $mensajes_error = ["Ha habido algún error al registrar el usuario"];
            echo $blade->run("registro", ["titulo" => "Agenda contactos - Registro", "mensajes_error" => $mensajes_error]);
            exit();
        }
        else if ($resultado_registro == Usuario::USUARIO_EXISTE) {
            $mensajes_error = ["El usuario ya existe"];
            echo $blade->run("registro", ["titulo" => "Agenda contactos - Registro", "mensajes_error" => $mensajes_error]);
            exit();
        }
    }
    else {
        $mensajes_error = [];

        if (!$nombre_valido) {
            $mensajes_error[] = "El nombre introducido no es válido.";
        }
        
        if (!$clave_valido) {
            $mensajes_error[] = "La contraseña introducida no es válida.";
        }
        
        echo $blade->run("inicio_sesion", ["titulo" => "Agenda contactos - Inicio sesión", "mensajes_error" => $mensajes_error]);
        exit();
    }
}
else if (empty($_POST) && !empty($_SESSION["usuario"])) {
    $usuario = $_SESSION["usuario"];
    
    $contactos = Contacto::obtenerContactosUsuario($bd, $usuario->getId());
    
    echo $blade->run("perfil", ["titulo" => "Agenda contactos - Perfil", "usuario" => $usuario, "contactos" => $contactos]);
    exit();
}
else if ($tipo_formulario === "agregar_contacto") {
    $nombre = filter_input(INPUT_POST, "nombre");
    $nombre_valido = valid_input($nombre) && strlen($nombre) > 0;
    
    $apellido = filter_input(INPUT_POST, "apellido");
    $apellido_valido = valid_input($apellido) && strlen($apellido) > 0;
    
    $phone1 = filter_input(INPUT_POST, "phone1");
    $phone1_valido = valid_input($phone1) && strlen($phone1) > 0;
    
    $phone2 = filter_input(INPUT_POST, "phone2");
    $phone2_valido = valid_input($phone2);
    
    $descripcion = filter_input(INPUT_POST, "descripcion");
    $descripcion_valido = valid_input($descripcion);
    
    $usuario = $_SESSION["usuario"];
    
    if ($nombre_valido && $apellido_valido && $phone1_valido && $phone2_valido && $descripcion_valido) {
        $contacto = new Contacto(
                null,
                $nombre,
                $apellido,
                (int) $phone1,
                (int) $phone2,
                $descripcion,
                $usuario->getId()
        );
        
        $resultado = $contacto->addContacto($bd);
        
        if (!$resultado) {
            $mensajes_error = ["No se ha podido añadir el contacto"];
        }
        else {
            $mensajes_error = null;
        }
        
        $contactos = Contacto::obtenerContactosUsuario($bd, $usuario->getId());

        echo $blade->run("perfil", ["titulo" => "Agenda contactos - Perfil", "usuario" => $usuario, "contactos" => $contactos, "mensajes_error" => $mensajes_error]);
        exit();
    }
    else {
        $contactos = Contacto::obtenerContactosUsuario($bd, $usuario->getId());
        $mensajes_error = ["aaa"];
        
        echo $blade->run("perfil", ["titulo" => "Agenda contactos - Perfil", "usuario" => $usuario, "contactos" => $contactos, "mensajes_error" => $mensajes_error]);
        exit();
    }
}
else if (preg_match("/borrarContacto_/", $tipo_formulario)) {
    $id_contacto = (int) explode("_", $tipo_formulario)[1];
    
    (Contacto::obtenerContactoId($bd, $id_contacto))->borrarContacto($bd);
    
    $usuario = $_SESSION["usuario"];
    $contactos = Contacto::obtenerContactosUsuario($bd, $usuario->getId());

    echo $blade->run("perfil", ["titulo" => "Agenda contactos - Perfil", "usuario" => $usuario, "contactos" => $contactos]);
    exit();
}
else if ($tipo_formulario === "exportar_agenda") {
    $usuario = $_SESSION["usuario"];
    
    $texto_xml = Contacto::obtenerXML($bd, $usuario->getId());
    
    header('Content-Disposition: attachment; filename="contactos.xml"');
    header('Content-type: application/xml');
    header('Content-Length: ' . strlen($texto_xml));
    header('Connection: close');
    
    echo $texto_xml;
    exit();
}
else {
    echo $blade->run("error_fatal", ["titulo" => "Agenda contactos - Error", "mensaje_error" => "No se reconoce el formulario recibido"]);
    exit();
}