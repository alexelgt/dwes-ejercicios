<?php
require '../vendor/autoload.php';
require '../funciones.php';

use eftec\bladeone\BladeOne;
use App\BD;
use App\Usuario;
use App\Pintor;
use App\Cuadro;

$views = __DIR__ . '/../views';
$cache = __DIR__ . '/../cache';

$blade = new BladeOne($views, $cache);

try {
    $bd = BD::getConexion();
} catch (PDOException $e){
    echo $blade->run("error_fatal", ["titulo" => "Pintor favorito - Error", "mensaje_error" => "No se ha podido conectar a la base de datos"]);
    exit();
}

try {
    $pintores = Pintor::obtenerPintores($bd);

    if (count($pintores) === 0) {
        echo $blade->run("error_fatal", ["titulo" => "Pintor favorito - Error", "mensaje_error" => "No hay pintores en la base de datos"]);
        exit();
    }
} catch (PDOException $e) {
    echo $blade->run("error_fatal", ["titulo" => "Pintor favorito - Error", "mensaje_error" => "Error al obtener información de la base de datos"]);
    exit();
}

session_start();
    
if (empty($_POST) && empty($_SESSION["usuario"])) {
    session_destroy();
    echo $blade->run("eleccion_formulario", ["titulo" => "Pintor favorito"]);
    exit();
}

$tipo_formulario = filter_input(INPUT_POST, "tipo_formulario");

if ($tipo_formulario === "eleccion_inicio_session") {
    echo $blade->run("iniciar_sesion", ["titulo" => "Pintor favorito - Iniciar sesión"]);
    exit();
}
elseif ($tipo_formulario === "eleccion_registro") {
    echo $blade->run("registro", ["titulo" => "Pintor favorito - Registro", "pintores" => $pintores]);
    exit();
}
elseif ($tipo_formulario === "iniciar_sesion") {
    $nombre = filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_STRING);
    $nombre_valido = valid_input($nombre) && strlen($nombre) > 0;
    
    $clave = filter_input(INPUT_POST, "clave");
    $clave_valido = valid_input($nombre) && strlen($clave) > 0;
    
    if ($nombre_valido && $clave_valido) {
        $usuario = Usuario::getUsuarioByLoginData($bd, $nombre, $clave);
        
        if (is_null($usuario)) {
            $mensaje = "Usuario " . $nombre . " no existe";
            echo $blade->run("iniciar_sesion", ["titulo" => "Pintor favorito - Iniciar sesión", "mensaje" => $mensaje]);
            exit();
        }
        else {
            $_SESSION["usuario"] = $usuario;

            $subtitulo = "Bienvenido ". $usuario->getName();
            
            $pintor_favorito = $usuario->getPainter_fk();
            $cuadros = Cuadro::obtenerCuadroPorPintor($bd, $pintor_favorito);
            
            echo $blade->run("perfil", ["titulo" => "Pintor favorito - $subtitulo", "cuadros" => $cuadros]);
            exit();
        }
    }
    else {
        echo $blade->run("iniciar_sesion", ["titulo" => "Pintor favorito - Iniciar sesión", "nombre" => $nombre, "nombre_valido" => $nombre_valido, "clave_valido" => $clave_valido]);
        exit();
    }
}
elseif ($tipo_formulario === "registro") {
    $nombre = filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_STRING);
    $nombre_valido = valid_input($nombre) && strlen($nombre) > 0;
    
    $clave = filter_input(INPUT_POST, "clave");
    $clave_valido = valid_input($nombre) && strlen($clave) > 0;
    
    $mail = filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL);
    $mail_valido = valid_input($mail);
    
    $pintor = filter_input(INPUT_POST, "pintor", FILTER_VALIDATE_INT);
    $pintor_valido = valid_input($pintor);
    
    if ($nombre_valido && $clave_valido && $mail_valido && $pintor_valido) {
        $usuario = new Usuario(
                $id=null,
                $name=$nombre,
                $password=$clave,
                $email=$mail,
                $painter_fk=$pintor
        );
        
        $resultado_registro = $usuario->registrarUsuario($bd);
        
        if ($resultado_registro === $usuario::REGISTRO_INCORRECTO) {
            $mensaje = "Ha habido un error en el registro";
            echo $blade->run("registro", ["titulo" => "Pintor favorito - Registro", "pintores" => $pintores, "mensaje" => $mensaje]);
            exit();
        }
        else if ($resultado_registro === $usuario::USUARIO_EXISTENTE) {
            $mensaje = "Usuario " . $nombre . " ya existe";
            echo $blade->run("registro", ["titulo" => "Pintor favorito - Registro", "pintores" => $pintores, "mensaje" => $mensaje]);
            exit();
        }
        else {
            $_SESSION["usuario"] = $usuario;

            $subtitulo = "Bienvenido ". $usuario->getName();
            
            $pintor_favorito = $usuario->getPainter_fk();
            $cuadros = Cuadro::obtenerCuadroPorPintor($bd, $pintor_favorito);
            
            echo $blade->run("perfil", ["titulo" => "Pintor favorito - $subtitulo", "cuadros" => $cuadros]);
            exit();
        }
    }
    else {
        echo $blade->run("registro", ["titulo" => "Pintor favorito - Registro", "pintores" => $pintores, "nombre" => $nombre, "nombre_valido" => $nombre_valido, "clave_valido" => $clave_valido, "mail" => $mail, "mail_valido" => $mail_valido, "pintor_favorito" => $pintor, "pintor_favorito_valido" => $pintor_valido]);
        exit();
    }
}
elseif ($tipo_formulario === "menu_inicial") {
    session_destroy();
    echo $blade->run("eleccion_formulario", ["titulo" => "Pintor favorito"]);
    exit();
}
elseif ($tipo_formulario === "modificar_usuario_form") {
    $usuario = $_SESSION["usuario"];

    $subtitulo = "Modificar datos de ". $usuario->getName();

    echo $blade->run("modificar_datos", ["titulo" => "Pintor favorito - $subtitulo", "pintores" => $pintores]);
    exit();
}
elseif ($tipo_formulario === "modificar_usuario") {
    $nombre = filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_STRING);
    $nombre_valido = valid_input($nombre) && strlen($nombre) > 0;
    
    $clave = filter_input(INPUT_POST, "clave");
    $clave_valido = valid_input($nombre) && strlen($clave) > 0;
    
    $mail = filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL);
    $mail_valido = valid_input($mail);
    
    $pintor = filter_input(INPUT_POST, "pintor", FILTER_VALIDATE_INT);
    $pintor_valido = valid_input($pintor);
    
    $usuario = $_SESSION["usuario"];
    
    if ($nombre_valido && $clave_valido && $mail_valido && $pintor_valido) {
        $usuario_old = clone $usuario;

        $usuario->setName($nombre);
        $usuario->setPassword($clave);
        $usuario->setEmail($mail);
        $usuario->setPainter_fk($pintor);

        $resultado_modificar = $usuario->modificarUsuario($bd, $usuario_old->getName());
        
        if ($resultado_modificar === $usuario::DATOS_NO_MODIFICADOS) {
            $_SESSION["usuario"] = $usuario_old;

            $subtitulo = "Modificar datos de ". $usuario_old->getName();

            $mensaje = "Ha habido un error al modificar los datos";
            echo $blade->run("modificar_datos", ["titulo" => "Pintor favorito - $subtitulo", "pintores" => $pintores, "mensaje" => $mensaje]);
            exit();
        }
        else if ($resultado_modificar === $usuario::USUARIO_EXISTENTE) {
            $_SESSION["usuario"] = $usuario_old;

            $subtitulo = "Modificar datos de ". $usuario_old->getName();

            $mensaje = "Usuario " . $nombre . " ya existe";
            echo $blade->run("modificar_datos", ["titulo" => "Pintor favorito - $subtitulo", "pintores" => $pintores, "mensaje" => $mensaje]);
            exit();
        }
        else {
            $_SESSION["usuario"] = $usuario;

            $subtitulo = "Bienvenido ". $usuario->getName();
            
            $pintor_favorito = $usuario->getPainter_fk();
            $cuadros = Cuadro::obtenerCuadroPorPintor($bd, $pintor_favorito);
            
            echo $blade->run("perfil", ["titulo" => "Pintor favorito - $subtitulo", "cuadros" => $cuadros]);
            exit();
        }
    }
    else {
        $subtitulo = "Modificar datos de ". $usuario->getName();

        echo $blade->run("modificar_datos", ["titulo" => "Pintor favorito - $subtitulo", "pintores" => $pintores, "nombre" => $nombre, "nombre_valido" => $nombre_valido, "clave_valido" => $clave_valido, "mail" => $mail, "mail_valido" => $mail_valido, "pintor_favorito" => $pintor, "pintor_favorito_valido" => $pintor_valido]);
        exit();
    }
}
elseif ($tipo_formulario === "volver_perfil") {
    $usuario = $_SESSION["usuario"];

    $subtitulo = "Bienvenido ". $usuario->getName();
    
    $pintor_favorito = $usuario->getPainter_fk();
    $cuadros = Cuadro::obtenerCuadroPorPintor($bd, $pintor_favorito);

    echo $blade->run("perfil", ["titulo" => "Pintor favorito - $subtitulo", "cuadros" => $cuadros]);
    exit();
}
elseif ($tipo_formulario === "baja_usuario") {
    if (empty($_SESSION["usuario"])) {
        echo $blade->run("eleccion_formulario", ["titulo" => "Pintor favorito"]);
        exit();
    }
    $usuario = $_SESSION["usuario"];
    
    $nombre_usuario = $usuario->getName();
    
    $usuario->elimina($bd);

    session_destroy();

    echo $blade->run("eleccion_formulario", ["titulo" => "Pintor favorito", "mensaje" => "Usuario $nombre_usuario dado de baja."]);
    exit();
}
elseif (preg_match("/infoCuadro-/", $tipo_formulario)) {
    $id_cuadro = (int) explode("-", $tipo_formulario)[1];
    
    $cuadro = Cuadro::obtenerCuadroPorId($bd, $id_cuadro);

    echo $blade->run("info_cuadro", ["titulo" => "Pintor favorito - Cuadro", "cuadro" => $cuadro]);
    exit();
}
elseif (empty($_POST) && !empty($_SESSION["usuario"])) {
    $usuario = $_SESSION["usuario"];
    
    $subtitulo = "Bienvenido ". $usuario->getName();
    
    $pintor_favorito = $usuario->getPainter_fk();
    $cuadros = Cuadro::obtenerCuadroPorPintor($bd, $pintor_favorito);

    echo $blade->run("perfil", ["titulo" => "Pintor favorito - $subtitulo", "cuadros" => $cuadros]);
    exit();
}
else {
    echo $blade->run("error_fatal", ["titulo" => "Pintor favorito - Error", "mensaje_error" => "No se ha reconocido el formulario enviado"]);
    exit();
}