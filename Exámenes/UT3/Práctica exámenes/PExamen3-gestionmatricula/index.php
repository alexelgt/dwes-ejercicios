<?php

require 'vendor/autoload.php';
require 'config.php';
require 'funciones.php';
require 'src/modelo/Usuario.php';
require 'src/modelo/Grupo.php';

use eftec\bladeone\BladeOne;
use \PDO as PDO;
use \App\Usuario as Usuario;
use \App\Grupo as Grupo;
use \App\Alumno as Alumno;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';

$blade = new BladeOne($views, $cache);

$titulo = "Gestión de Matricula";

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


            echo $blade->run("pantalla_inicial", ["titulo" => $titulo, "mensajes_error" => $mensajes_error]);
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
        
        $titulo .= " - Inicio";


        echo $blade->run("pantalla_inicial", ["titulo" => $titulo, "mensajes_error" => $mensajes_error]);
        exit();
    }
    
}
else if (preg_match("/add_/", $tipo_formulario)) {
    $grupo_id = (int) explode("_", $tipo_formulario)[1];
    
    $nombre = filter_input(INPUT_POST, "nombre");
    $nombre_valido = valid_input($nombre) && strlen($nombre) > 0;
    
    $apellido1 = filter_input(INPUT_POST, "apellido1");
    $apellido1_valido = valid_input($apellido1) && strlen($apellido1) > 0;
    
    $apellido2 = filter_input(INPUT_POST, "apellido2");
    $apellido2_valido = valid_input($apellido2);
    
    $edad = filter_input(INPUT_POST, "edad", FILTER_VALIDATE_INT);
    $edad_valido = valid_input($edad);
    
    $sexo = filter_input(INPUT_POST, "sexo");
    $sexo_valido = valid_input($sexo) && strlen($sexo) > 0;
    
    if ($nombre_valido && $apellido1_valido && $apellido2_valido && $edad_valido && $sexo_valido) {
        $alumno = new Alumno(null, $nombre, $apellido1, $apellido2, $edad, $sexo, $grupo_id);
        
        $alumno->insert($bd);
    }
    
    $usuario = $_SESSION["usuario"];
            
    obtener_perfil($blade, $bd, $titulo, $usuario);
    
}
else if (preg_match("/modificar_/", $tipo_formulario)) {
    $grupo_id = (int) explode("_", $tipo_formulario)[1];
    $alumno_id = (int) explode("_", $tipo_formulario)[2];
    
    $alumnos = filter_input(INPUT_POST, "alumnos", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    
    $nombre = $alumnos[$alumno_id]["nombre"];
    $nombre_valido = valid_input($nombre) && strlen($nombre) > 0;
    
    $apellido1 = $alumnos[$alumno_id]["apellido1"];
    $apellido1_valido = valid_input($apellido1) && strlen($apellido1) > 0;
    
    $apellido2 = $alumnos[$alumno_id]["apellido2"];
    $apellido2_valido = valid_input($apellido2);
    
    $edad = $alumnos[$alumno_id]["edad"];
    $edad_valido = valid_input($edad);
    
    $sexo = $alumnos[$alumno_id]["sexo"];
    $sexo_valido = valid_input($sexo) && strlen($sexo) > 0;
    
    if ($nombre_valido && $apellido1_valido && $apellido2_valido && $edad_valido && $sexo_valido) {
        $alumno = new Alumno($alumno_id, $nombre, $apellido1, $apellido2, $edad, $sexo, $grupo_id);
        
        $alumno->update($bd);
    }
    
    $usuario = $_SESSION["usuario"];
            
    obtener_perfil($blade, $bd, $titulo, $usuario);
}
else if (preg_match("/borrar_/", $tipo_formulario)) {
    $alumno_id = (int) explode("_", $tipo_formulario)[1];
    
    $alumno = Alumno::obtenerPorId($bd, $alumno_id);
    
    $alumno->delete($bd);
    
    $usuario = $_SESSION["usuario"];
            
    obtener_perfil($blade, $bd, $titulo, $usuario);
}
else if ($tipo_formulario === "exportar_xml") {
    
    $grupo_fk = filter_input(INPUT_POST, "grupo");
    
    $texto_xml = Alumno::obtenerXML($bd, $grupo_fk);
    
    header('Content-Disposition: attachment; filename="archivo.xml"');
    header('Content-type: application/xml');
    header('Content-Length: ' . strlen($texto_xml));
    header('Connection: close');
    
    echo $texto_xml;
    exit();
    
}
if (empty($_POST) && isset($_SESSION["usuario"])) {
    $usuario = $_SESSION["usuario"];
            
    obtener_perfil($blade, $bd, $titulo, $usuario);
}
else {
    $titulo .= " - Error fatal";
    $mensaje_error = "No se ha reconocido el formulario recibido.";

    echo $blade->run("error_fatal", ["titulo" => $titulo, "mensaje_error" => $mensaje_error]);
    exit();
}


function obtener_perfil(BladeOne $blade, PDO $bd, string $titulo, Usuario $usuario) {
    $titulo .= " - Perfil";
    
    $grupos = Grupo::obtenerGrupos($bd);
    
    echo $blade->run("perfil", ["titulo" => $titulo, "usuario" => $usuario, "grupos" => $grupos, "bd" => $bd]);
    exit();
}