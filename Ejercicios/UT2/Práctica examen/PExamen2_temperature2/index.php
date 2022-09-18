<?php
    require 'vendor/autoload.php';
    require 'funciones.php';

    use eftec\bladeone\BladeOne;

    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';

    $blade = new BladeOne($views, $cache);
    
    define("MESES", ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]);
    
    if (empty($_POST)) {
        echo $blade->run("ciudades", ["titulo" => "Formulario ciudades"]);
    }
    else {
        $tipo_formulario = filter_input(INPUT_POST, "tipo_formulario");
        
        if ($tipo_formulario === "ciudades") {
            $ciudades_string = filter_input(INPUT_POST, "ciudades");
            $ciudades_string_valido = valid_input($ciudades_string) && strlen($ciudades_string) > 0;
            
            if ($ciudades_string_valido) {
                $ciudades = preg_split("/[,.;:\t(\r\n)]+/", $ciudades_string);
                
                $num_ciudades = count($ciudades);
                
                $ciudades_string_valido = !excede_max_input($num_ciudades);
                
                if ($ciudades_string_valido) {
                    echo $blade->run("temperaturas", ["titulo" => "Formulario temperaturas", "meses" => MESES, "ciudades" => $ciudades]);
                }
            }
            
            if (!$ciudades_string_valido) {
                echo $blade->run("ciudades", ["titulo" => "Formulario ciudades"]);
            }
        }
        elseif ($tipo_formulario === "temperaturas") {
            $temperaturas = filter_input(INPUT_POST, "temperaturas", FILTER_VALIDATE_FLOAT, FILTER_REQUIRE_ARRAY);
            
            $resumen_ciudades = [];

            foreach ($temperaturas as $ciudad => $datos) {
                $resumen_ciudades[$ciudad]["min"] = min(array_column($datos, "min"));
                $resumen_ciudades[$ciudad]["max"] = max(array_column($datos, "max"));
                $resumen_ciudades[$ciudad]["avg"] = round(array_sum(array_merge(array_column($datos, "max"), array_column($datos, "min"))) / (count(array_column($datos, "max")) + count(array_column($datos, "min"))));
            }
            
            array_multisort(
                    array_column($resumen_ciudades, "max"), SORT_DESC, SORT_NUMERIC,
                    array_column($resumen_ciudades, "min"), SORT_ASC, SORT_NUMERIC,
                    array_keys($resumen_ciudades), SORT_ASC, SORT_STRING,
                    $resumen_ciudades
            );
            
            echo $blade->run("resultados", ["titulo" => "Resultados", "resumen_ciudades" => $resumen_ciudades]);
        }
    }