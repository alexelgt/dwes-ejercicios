<?php

require 'vendor/autoload.php';
require 'config.php';
require 'funciones.php';
require 'src/modelo/Profesor.php';
require 'src/modelo/Asignatura.php';
require 'src/modelo/Alumno.php';
require 'src/modelo/Nota.php';

use eftec\bladeone\BladeOne;
use \PDO as PDO;
use \App\Profesor as Profesor;
use \App\Asignatura as Asignatura;
use \App\Alumno as Alumno;
use \App\Nota as Nota;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';

$blade = new BladeOne($views, $cache);

$titulo = "Gestión de notas";

try {
    $bd = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USERNAME, PASSWORD);
} catch (PDOException $e) {
    $titulo .= " - Error fatal";
    $mensaje_error = "No se ha podido conectar con la base de datos.";

    echo $blade->run("error_fatal", ["titulo" => $titulo, "mensaje_error" => $mensaje_error]);
    exit();
}

session_start();

if (empty($_POST) && !isset($_SESSION["profesor"])) {
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
if ($tipo_formulario === "inicio_sesion") {
    $nombre = filter_input(INPUT_POST, "nombre");
    $nombre_valido = valid_input($nombre) && strlen($nombre);
    
    $clave = filter_input(INPUT_POST, "clave");
    $clave_valido = valid_input($clave) && strlen($clave);
    
    if ($nombre_valido && $clave_valido) {
        $profesor = Profesor::obtenerPorLogin($bd, $nombre, $clave);
        
        if (!is_null($profesor)) {
            $_SESSION["profesor"] = $profesor;

            obtenerPerfil($blade, $bd, $titulo, $profesor);
        }
        else {
            $mensajes_error = ["El profesor no existe."];
            
            $titulo .= " - Inicio";

            echo $blade->run("pantalla_inicial", ["titulo" => $titulo, "mensajes_error" => $mensajes_error]);
            exit();
        }
    }
    else {
        $mensajes_error = [];
        
        if (!$nombre_valido) {
            $mensajes_error[] = "El nombre no es válido.";
        }
        
        if (!$clave_valido) {
            $mensajes_error[] = "La contraseña no es válida.";
        }
        
        $titulo .= " - Inicio";

        echo $blade->run("pantalla_inicial", ["titulo" => $titulo, "mensajes_error" => $mensajes_error]);
        exit();
    }
}
if ($tipo_formulario === "actualizar_notas") {
    $notas = filter_input(INPUT_POST, "notas", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $test = filter_input(INPUT_POST, "test", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    
    var_dump($test);
    
    foreach ($notas as $alumno_fk => $datos) {
        foreach ($datos as $asignatura_fk => $valor) {
            if (is_numeric($valor) && $valor >= 0 && $valor <= 10) {
                $nota = Nota::obtenerPorFK($bd, $alumno_fk, $asignatura_fk);
            
                if (is_null($nota)) {
                    $nota = new Nota(null, $valor, $alumno_fk, $asignatura_fk);

                    $nota->insert($bd);
                }
                else {
                    if ($valor != $nota->getValor()) {
                        $nota = new Nota($nota->getId(), $valor, $alumno_fk, $asignatura_fk);

                        $nota->update($bd);
                    }
                }
            }
        }
    }
    
    $profesor = $_SESSION["profesor"];
    obtenerPerfil($blade, $bd, $titulo, $profesor);
}
if ($tipo_formulario === "exportar_xml") {
    $profesor = $_SESSION["profesor"];
    
    $texto_xml = $profesor->obtenerXML($bd);
    
    header('Content-Disposition: attachment; filename="archivo.xml"');
    header('Content-type: application/xml');
    header('Content-Length: ' . strlen($texto_xml));
    header('Connection: close');
    
    echo $texto_xml;
}
if (empty($_POST) && isset($_SESSION["profesor"])) {
    $profesor = $_SESSION["profesor"];
    
    obtenerPerfil($blade, $bd, $titulo, $profesor);
}
else {
    $titulo .= " - Error fatal";
    $mensaje_error = "No se ha reconocido el formulario recibido.";

    echo $blade->run("error_fatal", ["titulo" => $titulo, "mensaje_error" => $mensaje_error]);
    exit();
}

function obtenerPerfil(BladeOne $blade, PDO $bd, string $titulo, Profesor $profesor) {
    $titulo .= " - Perfil";
    
    $asignaturas = Asignatura::obtenerTodas($bd);
    $alumnos = Alumno::obtenerPorProfesor($bd, $profesor->getId());
    
    echo $blade->run("perfil", ["titulo" => $titulo, "bd" => $bd, "profesor" => $profesor, "asignaturas" => $asignaturas, "alumnos" => $alumnos]);
    exit();
}