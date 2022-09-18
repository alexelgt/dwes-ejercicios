<?php

require "vendor/autoload.php";
require "funciones_catastro.php";

use eftec\bladeone\BladeOne;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';

$blade = new BladeOne($views, $cache);

session_start();

if (!isset($_SESSION["email"])) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: /");
    exit();
}

$provincias_xml = peticion_catastro("PROVINCIAS");
$provincias = procesar_provincias($provincias_xml);

$titulo = "Catastro - Formulario";

$email = $_SESSION["email"];
$picture = $_SESSION["picture"];
$name = $_SESSION["name"];

echo $blade->run("formulario", ["titulo" => $titulo, "provincias" => $provincias, "email" => $email, "picture" => $picture, "name" => $name]);
exit();

