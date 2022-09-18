<?php

require "funciones_catastro.php";

if (empty($_POST)) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: /");
    exit();
}

header("Content-type: application/json");

$obtener = filter_input(INPUT_POST, "obtener");

if ($obtener === "municipios") {
    $provincia = filter_input(INPUT_POST, "provincia");
    
    $parametros = [
        ["{PROVINCIA}"],
        [$provincia]
    ];
    
    $municipios_xml = peticion_catastro("MUNICIPIOS", $parametros);
    
    $municipios = procesar_municipios($municipios_xml);
    
    $response = [];
    $response["municipios"] = $municipios;
    
    echo(json_encode($response));
    exit();
}
if ($obtener === "vias") {
    $provincia = filter_input(INPUT_POST, "provincia");
    $municipio = filter_input(INPUT_POST, "municipio");
    $tipoVia = filter_input(INPUT_POST, "tipoVia");
    
    $parametros = [
        ["{PROVINCIA}", "{MUNICIPIO}", "{TIPOVIA}"],
        [$provincia, $municipio, $tipoVia]
    ];

    $vias_xml = peticion_catastro("VIAS", $parametros);
    $vias = procesar_vias($vias_xml);
    
    $response = [];
    $response["vias"] = $vias;
    
    echo(json_encode($response));
    exit();
}
else if ($obtener === "numeros") {
    $provincia = filter_input(INPUT_POST, "provincia");
    $municipio = filter_input(INPUT_POST, "municipio");
    $tipoVia = filter_input(INPUT_POST, "tipoVia");
    $viaTexto = filter_input(INPUT_POST, "viaTexto");
    $numero = filter_input(INPUT_POST, "numero");
    
    $parametros = [
        ["{PROVINCIA}", "{MUNICIPIO}", "{TIPOVIA}", "{NOMVIA}", "{NUMERO}"],
        [$provincia, $municipio, $tipoVia, $viaTexto, $numero]
    ];
    
    $numeros_xml = peticion_catastro("NUMEROS", $parametros);
    
    $numeros = procesar_numeros($numeros_xml);
    
    if (is_string($numeros)) {
        $response = [];
        $response["error"] = $numeros;
    }
    else {
        $response = $numeros;
    }
    
    echo(json_encode($response));
    exit();
}
else if ($obtener === "referencia_catastral") {
    $provincia = filter_input(INPUT_POST, "provincia");
    $municipio = filter_input(INPUT_POST, "municipio");
    $viaTexto = filter_input(INPUT_POST, "viaTexto");
    $numero = filter_input(INPUT_POST, "numero");
    $rc = filter_input(INPUT_POST, "rc");
    
    $parametros = [
        ["{PROVINCIA}", "{MUNICIPIO}", "{RC}"],
        [$provincia, $municipio, $rc]
    ];
    
    $ref_cat_xml = peticion_catastro("RC", $parametros);
    
    $ref_cat = procesar_rc($ref_cat_xml, $viaTexto, $numero);
    
    if (is_string($ref_cat)) {
        $response = [];
        $response["error"] = $ref_cat;
    }
    else {
        $response = $ref_cat;
    }
    
    echo(json_encode($response));
    exit();
}
else if ($obtener === "datos_inmueble") {
    $provincia = filter_input(INPUT_POST, "provincia");
    $municipio = filter_input(INPUT_POST, "municipio");
    $rc = filter_input(INPUT_POST, "rc");
    
    $parametros = [
        ["{PROVINCIA}", "{MUNICIPIO}", "{RC}"],
        [$provincia, $municipio, $rc]
    ];
    
    $datos_inmueble_xml = peticion_catastro("RC", $parametros);
    
    $datos_inmueble = procesar_datos_inmueble($datos_inmueble_xml);
    
    if (is_string($datos_inmueble)) {
        $response = [];
        $response["error"] = $datos_inmueble;
    }
    else {
        $response = $datos_inmueble;
    }
    
    echo(json_encode($response));
    exit();
}