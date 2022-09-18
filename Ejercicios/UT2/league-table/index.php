<?php
    require "vendor/autoload.php";
    require 'funciones.php';
    
    use eftec\bladeone\BladeOne;
    
    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';
    
    $blade = new BladeOne($views, $cache);
    
    define("PUNTOS_VICTORIA", 3);
    define("PUNTOS_EMPATE", 1);
    
    if (!empty($_POST)) {
        $tipo_formulario = filter_input(INPUT_POST, "boton");
        
        if ($tipo_formulario === "introducir_equipos") {
            $equipos_string = filter_input(INPUT_POST, "equipos");
            
            $equipos_string_valido = valid_input($equipos_string) && strlen($equipos_string) > 0;
            
            if ($equipos_string_valido) {
                $equipos = obtener_palabras($equipos_string);
                
                if (count($equipos) % 2 !== 0 || excede_max_input_permitido(count($equipos))) {
                    $equipos_string_valido = false;
                }
                else {
                    shuffle($equipos);
                
                    $partidos = generar_partidos(array_keys($equipos));

                    $titulo = "Formulario jornadas";
                    echo $blade->run("formulario_jornadas", ["titulo" => $titulo, "equipos" => $equipos, "partidos" => $partidos]);
                }
            }

            if (!$equipos_string_valido) {
                $titulo = "Formulario equipos";
                echo $blade->run("formulario_equipos", ["titulo" => $titulo, "equipos_string" => $equipos_string, "equipos_string_valido" => $equipos_string_valido]);
            }
        }
        elseif ($tipo_formulario === "introducir_jornadas") {
            $info_liga = filter_input(INPUT_POST, "info_liga", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

            $resultados_liga = [];
            
            foreach ($info_liga as $num_jornada => $partidos_jornada) {
                foreach ($partidos_jornada as $num_partido => $resultado_partido) {
                    $equipos_partido = array_keys($resultado_partido);
                    
                    $equipo1 = $equipos_partido[0];
                    $equipo2 = $equipos_partido[1];
                    
                    $goles1 = (int) $resultado_partido[$equipo1];
                    $goles2 = (int) $resultado_partido[$equipo2];
                    
                    if (!array_key_exists($equipo1, $resultados_liga)) {
                        $resultados_liga[$equipo1] = [];

                        $resultados_liga[$equipo1]["goles_conseguidos"] = 0;
                        $resultados_liga[$equipo1]["goles_encajados"] = 0;
                        $resultados_liga[$equipo1]["gol_average"] = 0;
                        $resultados_liga[$equipo1]["puntos"] = 0;
                        $resultados_liga[$equipo1]["victorias"] = 0;
                        $resultados_liga[$equipo1]["derrotas"] = 0;
                        $resultados_liga[$equipo1]["empates"] = 0;
                    }
                    
                    if (!array_key_exists($equipo2, $resultados_liga)) {
                        $resultados_liga[$equipo2] = [];

                        $resultados_liga[$equipo2]["goles_conseguidos"] = 0;
                        $resultados_liga[$equipo2]["goles_encajados"] = 0;
                        $resultados_liga[$equipo2]["gol_average"] = 0;
                        $resultados_liga[$equipo2]["puntos"] = 0;
                        $resultados_liga[$equipo2]["victorias"] = 0;
                        $resultados_liga[$equipo2]["derrotas"] = 0;
                        $resultados_liga[$equipo2]["empates"] = 0;
                    }
                    
                    $resultados_liga[$equipo1]["goles_conseguidos"] += $goles1;
                    $resultados_liga[$equipo2]["goles_conseguidos"] += $goles2;
                    
                    $resultados_liga[$equipo1]["goles_encajados"] += $goles2;
                    $resultados_liga[$equipo2]["goles_encajados"] += $goles1;
                    
                    $resultados_liga[$equipo1]["gol_average"] += $goles1;
                    $resultados_liga[$equipo2]["gol_average"] -= $goles1;
                    
                    $resultados_liga[$equipo1]["gol_average"] -= $goles2;
                    $resultados_liga[$equipo2]["gol_average"] += $goles2;
                    
                    if ($goles1 > $goles2) {
                        $resultados_liga[$equipo1]["puntos"] += PUNTOS_VICTORIA;

                        $resultados_liga[$equipo1]["victorias"] += 1;
                        $resultados_liga[$equipo2]["derrotas"] += 1;
                    }
                    elseif ($goles2 > $goles1) {
                        $resultados_liga[$equipo2]["puntos"] += PUNTOS_VICTORIA;

                        $resultados_liga[$equipo2]["victorias"] += 1;
                        $resultados_liga[$equipo1]["derrotas"] += 1;
                    }
                    else {
                        $resultados_liga[$equipo1]["puntos"] += PUNTOS_EMPATE;
                        $resultados_liga[$equipo2]["puntos"] += PUNTOS_EMPATE;
                        
                        $resultados_liga[$equipo1]["empates"] += 1;
                        $resultados_liga[$equipo2]["empates"] += 1;
                    }
                }
            }
            
            $equipos = array_keys($resultados_liga);
            
            $puntos = array_column($resultados_liga, "puntos");
            $goles_conseguidos = array_column($resultados_liga, "goles_conseguidos");
            $goles_encajados = array_column($resultados_liga, "goles_encajados");
            $victorias = array_column($resultados_liga, "victorias");
            $derrotas = array_column($resultados_liga, "derrotas");
            $empates = array_column($resultados_liga, "empates");
            
            $gol_average = array_column($resultados_liga, "gol_average");
            
            array_multisort(
                $puntos, SORT_NUMERIC, SORT_DESC,
                $gol_average, SORT_NUMERIC, SORT_DESC,
                $equipos, SORT_STRING, SORT_ASC,
                $goles_conseguidos,
                $goles_encajados,
                $victorias,
                $derrotas,
                $empates,
                $resultados_liga
            );
            
            $titulo = "Clasificación";
            echo $blade->run("clasificacion_liga", ["titulo" => "$titulo", "resultados_liga" => $resultados_liga]);
        }
    }
    else {
        $titulo = "Formulario equipos";
        echo $blade->run("formulario_equipos", ["titulo" => $titulo]);
    }
?>
