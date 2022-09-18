<?php
    require "vendor/autoload.php";
    require 'funciones.php';
    
    use eftec\bladeone\BladeOne;
    
    $views = __DIR__ . '/views';
    $compiledFolder = __DIR__ . '/compiled';
    
    $blade = new BladeOne($views, $compiledFolder);
    
    $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    
    if (!empty($_POST)) {
        $tipo_formulario = filter_input(INPUT_POST, "boton");
        
        if ($tipo_formulario === "introducir_ciudades") {
            $titulo = "Formulario ciudades";

            $ciudades_string = filter_input(INPUT_POST, "ciudades");
            
            $ciudades_string_valido = valid_input($ciudades_string) && strlen($ciudades_string) > 0;
            
            if ($ciudades_string_valido) {
                $ciudades = obtener_palabras($ciudades_string);
                
                if (excede_max_input_permitido(count($ciudades))) {
                    $ciudades_string_valido = false;
                }
                else {
                    $titulo = "Formulario temperaturas";
                    echo $blade->run("formulario_introducir_temperaturas", ["titulo" => $titulo, "ciudades" => $ciudades, "meses" => $meses]);
                }
            }
            if (!$ciudades_string_valido) {
                echo $blade->run("formulario_ciudades", ["titulo" => $titulo, "ciudades_string" => $ciudades_string, "ciudades_string_valido" => $ciudades_string_valido]);
            }
        }
        elseif ($tipo_formulario === "introducir_temperaturas") {
            $temperaturas = filter_input(INPUT_POST, "temperaturas", FILTER_VALIDATE_FLOAT, FILTER_REQUIRE_ARRAY);
            
            $ciudades = [];
            $temp_min_media_array = [];
            $temp_max_media_array = [];
            $temp_min_array = [];
            $temp_max_array = [];
            
            foreach ($temperaturas as $key => $value) {
                $ciudades[] = $key;
                
                $temp_min = (float) min(array_column($value, "min"));
                $temp_max = (float) max(array_column($value, "max"));
                
                $temp_min_media = round((float) array_sum(array_column($value, "min")) / count($meses), 1);
                $temp_max_media = round((float) array_sum(array_column($value, "max")) / count($meses), 1);
                
                $temp_min_media_array[] = $temp_min_media;
                $temp_max_media_array[] = $temp_max_media;

                $temp_min_array[] = $temp_min;
                $temp_max_array[] = $temp_max;
            }
            
            array_multisort(
                $temp_max_array, SORT_NUMERIC, SORT_DESC,
                $temp_min_array, SORT_NUMERIC, SORT_ASC,
                $ciudades, SORT_STRING, SORT_ASC,
                $temp_min_media_array,
                $temp_max_media_array
                    
            );
            
            $titulo = "Mostrar temperaturas";
            echo $blade->run("mostrar_temperaturas", ["titulo" => $titulo, "ciudades" => $ciudades, "temp_min_array" => $temp_min_array, "temp_max_array" => $temp_max_array, "temp_min_media_array" => $temp_min_media_array, "temp_max_media_array" => $temp_max_media_array]);
        }
    }
    else {
        $titulo = "Formulario ciudades";
        echo $blade->run("formulario_ciudades", ["titulo" => $titulo]);
    }
?>
