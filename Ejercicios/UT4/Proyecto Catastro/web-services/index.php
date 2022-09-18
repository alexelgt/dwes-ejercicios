<?php

require "vendor/autoload.php";

function cargar_inicio_sesion($blade, $clientID, $clientSecret, $redirectUri) {
    $client = new Google_Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("email");
    $client->addScope("profile");

    $url = $client->createAuthUrl();

    $titulo = "Catastro - Inicio de sesión";

    echo $blade->run("inicio_sesion", ["titulo" => $titulo, "url" => $url]);
    exit();
}

use eftec\bladeone\BladeOne;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';

$blade = new BladeOne($views, $cache);

session_start();

$clientID = "273333373184-36flovgaqhiief68nr64kc2qb704il23.apps.googleusercontent.com";
$clientSecret = "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";
$redirectUri = "http://localhost:8000";

if (empty($_POST)) {
    if (isset($_SESSION["email"])) {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: /formulario.php");
        exit();
    }

    if (isset($_GET["code"])) {
        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        
        $code = filter_input(INPUT_GET, "code");
        $scope = filter_input(INPUT_GET, "scope");
        $authuser = filter_input(INPUT_GET, "authuser");
        
        $token = $client->fetchAccessTokenWithAuthCode($code);
        
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        $email = $google_account_info->email;
        $picture = $google_account_info->picture;
        $name = $google_account_info->givenName;
        
        $_SESSION["email"] = $email;
        $_SESSION["picture"] = $picture;
        $_SESSION["name"] = $name;
        
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: /formulario.php");
        exit();
    }
    else {
        cargar_inicio_sesion($blade, $clientID, $clientSecret, $redirectUri);
    }
}
else {
    session_destroy();
    
    cargar_inicio_sesion($blade, $clientID, $clientSecret, $redirectUri);
}