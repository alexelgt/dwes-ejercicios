<?php

require 'vendor/autoload.php';
require 'config.php';
require 'funciones.php';
require 'src/modelo/Usuario.php';
require 'src/modelo/Apunte.php';

use eftec\bladeone\BladeOne;
use \PDO as PDO;
use \App\Usuario as Usuario;
use \App\Apunte as Apunte;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';

$blade = new BladeOne($views, $cache);

$titulo = "Contabilidad";

try {
    $bd = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USERNAME, PASSWORD);
} catch (PDOException $e) {
    $titulo .= " - Error fatal";
    $mensaje_error = "No se ha podido conectar con la base de datos.";

    echo $blade->run("error_fatal", ["titulo" => $titulo, "mensaje_error" => $mensaje_error]);
    exit();
}

session_start();

if (empty($_POST) && !isset($_SESSION["usuario"])) {
    $titulo .= " - Inicio";
    
    echo $blade->run("pantalla_inicial", ["titulo" => $titulo]);
    exit();
}

$tipo_formulario = filter_input(INPUT_POST, "tipo_formulario");

if ($tipo_formulario === "menu_inicial") {
    session_destroy();
    $titulo .= " - Inicio";
    
    echo $blade->run("pantalla_inicial", ["titulo" => $titulo]);
    exit();
}
else if ($tipo_formulario === "form_inicio_sesion") {
    $titulo .= " - Iniciar sesión";
    
    echo $blade->run("inicio_sesion", ["titulo" => $titulo]);
    exit();
}
else if ($tipo_formulario === "form_registro") {
    $titulo .= " - Registro";
    
    echo $blade->run("registro", ["titulo" => $titulo]);
    exit();
}
else if ($tipo_formulario === "registro") {
    $nombre = filter_input(INPUT_POST, "nombre");
    $nombre_valido = valid_input($nombre) && strlen($nombre) > 0;
    
    $clave = filter_input(INPUT_POST, "clave");
    $clave_valido = valid_input($clave) && strlen($clave) > 0;
    
    if ($nombre_valido && $clave_valido) {
        $usuario = new Usuario(
                null,
                $nombre,
                $clave
        );
        
        $resultado_registro = $usuario->registrar($bd);
        
        if ($resultado_registro === Usuario::USUARIO_REGISTRADO) {
            $_SESSION["usuario"] = $usuario;
            
            obtenerPerfil($blade, $titulo, $bd, $usuario);
        }
        else {
            $mensajes_error = ["Ha ocurrido un error al registrar el usuario."];
            
            $titulo .= " - Registro";
    
            echo $blade->run("registro", ["titulo" => $titulo, "mensajes_error" => $mensajes_error]);
            exit();
        }
    }
    else {
        $mensajes_error = [];

        if (!$nombre_valido) {
            $mensajes_error[] = "Nombre no válido.";
        }
        
        if (!$clave_valido) {
            $mensajes_error[] = "Contraseña no válida.";
        }
        
        $titulo .= " - Registro";
        
        echo $blade->run("registro", ["titulo" => $titulo, "mensajes_error" => $mensajes_error]);
        exit();
    }
}
else if ($tipo_formulario === "inicio_sesion") {
    $nombre = filter_input(INPUT_POST, "nombre");
    $nombre_valido = valid_input($nombre) && strlen($nombre) > 0;
    
    $clave = filter_input(INPUT_POST, "clave");
    $clave_valido = valid_input($clave) && strlen($clave) > 0;
    
    if ($nombre_valido && $clave_valido) {
        $usuario = Usuario::obtenerPorLogin($bd, $nombre, $clave);
        
        if (is_null($usuario)) {
            $mensajes_error = ["No existe el usuario."];
            
            $titulo .= " - Iniciar sesión";
        
            echo $blade->run("inicio_sesion", ["titulo" => $titulo, "mensajes_error" => $mensajes_error]);
            exit();
        }
        else {
            $_SESSION["usuario"] = $usuario;

            obtenerPerfil($blade, $titulo, $bd, $usuario);
        }
    }
    else {
        $mensajes_error = [];

        if (!$nombre_valido) {
            $mensajes_error[] = "Nombre no válido.";
        }
        
        if (!$clave_valido) {
            $mensajes_error[] = "Contraseña no válida.";
        }
        
        $titulo .= " - Iniciar sesión";
        
        echo $blade->run("inicio_sesion", ["titulo" => $titulo, "mensajes_error" => $mensajes_error]);
        exit();
    }
}
else if (preg_match ("/addPunte_/", $tipo_formulario)) {
    $id_usuario = (int) explode("_", $tipo_formulario)[1];
    
    $usuario = $_SESSION["usuario"];
    
    $ingreso = filter_input(INPUT_POST, "ingreso");
    $ingreso_valido = valid_input($ingreso);
    
    $concepto = filter_input(INPUT_POST, "concepto");
    $concepto_valido = valid_input($concepto) && strlen($concepto) > 0;
    
    $cantidad = filter_input(INPUT_POST, "cantidad", FILTER_VALIDATE_FLOAT);
    $cantidad_valido = valid_input($cantidad);
    
    $fecha = filter_input(INPUT_POST, "fecha");
    $fecha_valido = valid_input($fecha) && strlen($fecha) > 0;
    
    if (!$fecha_valido) {
        $fecha = date("Y-m-d");
        
        $fecha_valido = true;
    }
    
    if ($ingreso_valido && $concepto_valido && $cantidad_valido && $fecha_valido) {
        $apunte = new Apunte(
                null,
                $ingreso,
                $concepto,
                $cantidad,
                $fecha,
                $id_usuario
        );
        
        $apunte->add($bd);

        obtenerPerfil($blade, $titulo, $bd, $usuario);
    }
    else {
        $mensaje_error = "Los datos del apunte no son correctos.";
        
        obtenerPerfil($blade, $titulo, $bd, $usuario, "todos", $mensaje_error);
    }
}
else if (empty($_POST) && isset($_SESSION["usuario"])) {
    $usuario = $_SESSION["usuario"];
    
    obtenerPerfil($blade, $titulo, $bd, $usuario);
}
else if (preg_match ("/perfilMostrar_/", $tipo_formulario)) {
    $usuario = $_SESSION["usuario"];
    
    $apuntes_mostrar = explode("_", $tipo_formulario)[1];
    
    obtenerPerfil($blade, $titulo, $bd, $usuario, $apuntes_mostrar);
}
else if ($tipo_formulario === "descargar_xml") {
    $usuario = $_SESSION["usuario"];
    
    $texto_xml = Apunte::obtenerXML($bd, $usuario->getId());
    
    header('Content-Disposition: attachment; filename="archivo.xml"');
    header('Content-type: application/xml');
    header('Content-Length: ' . strlen($texto_xml));
    header('Connection: close');
}
else {
    $titulo .= " - Error fatal";
    $mensaje_error = "No se ha reconocido el formulario recibido.";

    echo $blade->run("error_fatal", ["titulo" => $titulo, "mensaje_error" => $mensaje_error]);
    exit();
}