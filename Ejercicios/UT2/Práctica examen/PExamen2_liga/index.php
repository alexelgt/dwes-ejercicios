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
        $tipo_formulario = filter_input(INPUT_POST, "botoncito");
        
        if ($tipo_formulario === "equipos") {
            $equipos_string = filter_input(INPUT_POST, "equipos");
            $equipos_string_valido = valid_input($equipos_string) && strlen($equipos_string) > 0;
            
            if ($equipos_string_valido) {
                $equipos = preg_split("/[.,;:(\r\n)\t]+/", $equipos_string);
                
                // Hacer filtrado
                
                $num_equipos = count($equipos);
                
                $equipos_string_valido = $num_equipos > 1 && $num_equipos % 2 === 0 && !excede_max_input($num_equipos);
                
                if ($equipos_string_valido) {
                    echo $blade->run("partidos", ["titulo" => "Formulario partidos", "equipos" => $equipos]);
                }
            }

            if (!$equipos_string_valido) {
                echo $blade->run("equipos", ["titulo" => "Formulario equipos", "equipos_string_valido" => $equipos_string_valido]);
            }
        }
        if ($tipo_formulario === "partidos") {
            $resultados = $array = filter_input(INPUT_POST, "resultados", FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
            
            $datos_equipos = [];
            $puntos = [];
            $goles_conseguidos = [];
            $goles_encajados = [];
            $gol_average = [];
            
            foreach ($resultados as $equipo1 => $enfrentamientos) {
                foreach ($enfrentamientos as $equipo2 => $goles) {
                    if (!key_exists($equipo1, $datos_equipos)) {
                        $datos_equipos[$equipo1] = [];
                        set_array_datos($datos_equipos[$equipo1]);
                    }

                    if (!key_exists($equipo2, $datos_equipos)) {
                        $datos_equipos[$equipo2] = [];
                        set_array_datos($datos_equipos[$equipo2]);
                    }

                    [$goles1, $goles2] = $goles;
                    
                    if ($goles1 > $goles2) {
                        $datos_equipos[$equipo1]["puntos"] += PUNTOS_VICTORIA;
                    }
                    elseif ($goles1 < $goles2) {
                        $datos_equipos[$equipo2]["puntos"] += PUNTOS_VICTORIA;
                    }
                    else {
                        $datos_equipos[$equipo1]["puntos"] += PUNTOS_EMPATE;
                        $datos_equipos[$equipo2]["puntos"] += PUNTOS_EMPATE;
                    }
                    
                    $datos_equipos[$equipo1]["goles_conseguidos"] += $goles1;
                    $datos_equipos[$equipo2]["goles_conseguidos"] += $goles2;
                    
                    $datos_equipos[$equipo1]["goles_encajados"] += $goles2;
                    $datos_equipos[$equipo2]["goles_encajados"] += $goles1;
                    
                    $datos_equipos[$equipo1]["gol_average"] += $goles1 - $goles2;
                    $datos_equipos[$equipo2]["gol_average"] += $goles2 - $goles1;
                    
                    
                }
            }
            
            $equipos = array_keys($datos_equipos);
            
            $puntos = array_column($datos_equipos, "puntos");
            $goles_conseguidos = array_column($datos_equipos, "goles_conseguidos");
            $goles_encajados = array_column($datos_equipos, "goles_encajados");
            $gol_average = array_column($datos_equipos, "gol_average");
            
            array_multisort(
                    $puntos, SORT_DESC, SORT_NUMERIC,
                    $gol_average, SORT_DESC, SORT_NUMERIC,
                    $equipos, SORT_ASC, SORT_STRING,
                    $datos_equipos
            );
            
            echo $blade->run("liga", ["titulo" => "Clasificación liga", "datos_equipos" => $datos_equipos]);
        }
    }