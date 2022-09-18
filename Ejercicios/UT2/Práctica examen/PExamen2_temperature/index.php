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
            
                // Filtrar duplicados

                $num_ciudades = count($ciudades);

                $ciudades_string_valido = !excede_max_input($num_ciudades);
                
                if ($ciudades_string_valido) {
                    echo $blade->run("temperaturas", ["titulo" => "Formulario temeperaturas", "ciudades" => $ciudades, "meses" => MESES]);
                }
            }
            
            if (!$ciudades_string_valido) {
                echo $blade->run("ciudades", ["titulo" => "Formulario ciudades", "ciudades_string_valido" => $ciudades_string_valido]);
            }
        }
        
        if ($tipo_formulario === "temperaturas") {
            $temperaturas = filter_input(INPUT_POST, "temperaturas", FILTER_VALIDATE_FLOAT, FILTER_REQUIRE_ARRAY);
            
            $datos_ciudades = [];
            
            foreach ($temperaturas as $ciudad => $datos) {
                
                $datos_ciudades[$ciudad]["min"] = min(array_column($datos, "min"));
                $datos_ciudades[$ciudad]["max"] = max(array_column($datos, "max"));
                $datos_ciudades[$ciudad]["avg"] = array_sum(array_merge(array_column($datos, "min"), array_column($datos, "max"))) / (count(array_column($datos, "min")) + count(array_column($datos, "max")));
                
                
            }
            
            array_multisort(
                    array_column($datos_ciudades, "max"), SORT_DESC, SORT_NUMERIC,
                    array_column($datos_ciudades, "min"), SORT_ASC, SORT_NUMERIC,
                    array_keys($datos_ciudades), SORT_ASC, SORT_STRING,
                    $datos_ciudades
                    
            );
            
            echo $blade->run("datos_ciudades", ["titulo" => "Resultados", "datos_ciudades" => $datos_ciudades]);
        }
    }