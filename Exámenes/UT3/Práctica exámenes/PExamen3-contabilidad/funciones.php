<?php

use eftec\bladeone\BladeOne;
use \PDO as PDO;
use \App\Usuario as Usuario;
use \App\Apunte as Apunte;

function valid_input($variable) {
    if ($variable === null || $variable === false) {
        return false;
    }

    return true;
}


function obtenerPerfil(BladeOne $blade, string $titulo, PDO $bd, Usuario $usuario, string $apuntes_mostrar="todos", ?string $mensaje_error=null) {
    $titulo .= " - Perfil";

    if ($apuntes_mostrar === "todos") {
        $apuntes = Apunte::obtenerPorUsuario($bd, $usuario->getId());
    }
    else if ($apuntes_mostrar === "ingresos") {
        $apuntes = Apunte::obtenerIngresosPorUsuario($bd, $usuario->getId());
    }
    else {
        $apuntes = Apunte::obtenerGastosPorUsuario($bd, $usuario->getId());
    }

    $saldo = Apunte::obtenerSaldoUsuario($bd, $usuario->getId());
    
    if (is_null($mensaje_error)) {
        echo $blade->run("perfil", ["titulo" => $titulo, "usuario" => $usuario, "saldo" => $saldo, "apuntes" => $apuntes, "apuntes_mostrar" => $apuntes_mostrar]);
        exit();
    }
    else {
        echo $blade->run("perfil", ["titulo" => $titulo, "usuario" => $usuario, "saldo" => $saldo, "apuntes" => $apuntes, "apuntes_mostrar" => $apuntes_mostrar, "mensaje_error" => $mensaje_error]);
        exit();
    }
}