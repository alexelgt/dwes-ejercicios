<?php
    require 'vendor/autoload.php';
    require 'funciones.php';

    use eftec\bladeone\BladeOne;

    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';

    $blade = new BladeOne($views, $cache);
    
    define("PUNTOS_VICTORIA", 3);
    define("PUNTOS_EMPATE", 1);
    
    if (empty($_POST)) {
        echo $blade->run("equipos", ["titulo" => "Formulario equipos"]);
    }
    else {
        $tipo_formulario = filter_input(INPUT_POST, "tipo_formulario");
        
        if ($tipo_formulario === "equipos") {
            $equipos_string = filter_input(INPUT_POST, "equipos");
            
            $equipos_string_valido = valid_input($equipos_string) && strlen($equipos_string) > 0;
            
            if ($equipos_string_valido) {
                $equipos = preg_split("/[,.;:\t(\r\n)]+/", $equipos_string);
                
                $num_equipos = count($equipos);
                
                $equipos_string_valido = !excede_max_input($num_equipos) && $num_equipos > 1;
                
                if ($equipos_string_valido) {
                    echo $blade->run("enfrentamientos", ["titulo" => "Formulario enfrentamientos", "equipos" => $equipos]);
                }
            }
            
            if (!$equipos_string_valido) {
                echo $blade->run("equipos", ["titulo" => "Formulario equipos", "equipos_string_valido" => $equipos_string_valido]);
            }
        }
        
        elseif ($tipo_formulario === "enfrentamientos") {
            $enfrentamientos = filter_input(INPUT_POST, "enfrentamientos", FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
            
            $equipos = array_keys($enfrentamientos);
            
            $resultados = empty_resultados($equipos);
            
            foreach ($enfrentamientos as $equipo1 => $datos) {
                foreach ($datos as $equipo2 => $goles) {
                    [$goles1, $goles2] = $goles;
                    
                    $resultados[$equipo1]["goles_conseguidos"] += $goles1;
                    $resultados[$equipo2]["goles_encajados"] += $goles1;
                    $resultados[$equipo1]["gol_average"] += $goles1 - $goles2;

                    $resultados[$equipo1]["goles_encajados"] += $goles2;
                    $resultados[$equipo2]["goles_conseguidos"] += $goles2;
                    $resultados[$equipo2]["gol_average"] += $goles2 - $goles1;
                    
                    if ($goles1 > $goles2) {
                        $resultados[$equipo1]["puntos"] += PUNTOS_VICTORIA;
                    }
                    elseif ($goles2 > $goles1) {
                        $resultados[$equipo2]["puntos"] += PUNTOS_VICTORIA;
                    }
                    else {
                        $resultados[$equipo1]["puntos"] += PUNTOS_EMPATE;
                        $resultados[$equipo2]["puntos"] += PUNTOS_EMPATE;
                    }
                }
            }
            
            array_multisort(
                    array_column($resultados, "puntos"), SORT_DESC, SORT_NUMERIC,
                    array_column($resultados, "gol_average"), SORT_DESC, SORT_NUMERIC,
                    $equipos, SORT_ASC, SORT_STRING,
                    $resultados
            );
            
            echo $blade->run("clasificacion", ["titulo" => "Clasificación liga", "resultados" => $resultados]);
        }
    }