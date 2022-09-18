<?php
    require "vendor/autoload.php";
    require "funciones.php";

    use eftec\bladeone\BladeOne;

    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';

    $blade = new BladeOne($views, $cache);
    
    session_start();

    if (empty($_POST)) {
        session_destroy();
        session_start();

        generar_tablero();
        echo $blade->run("tablero", ["size" => SIZE, "casillas_seguidas" => CASILLAS_SEGUIDAS]);
        exit();
    }
    else {
        if (isset($_POST["reiniciar_partida"])) {
            session_destroy();
            session_start();

            generar_tablero();
            echo $blade->run("tablero", ["size" => SIZE, "casillas_seguidas" => CASILLAS_SEGUIDAS]);
            exit();
        }
        
        header("Content-type: application/json");
        
        $x = (int) filter_input(INPUT_POST, "x");
        
        $y = poner_ficha_jugador($x);
        
        $response = [];

        if ($y === null) {
            echo(json_encode($response)); // respuesta vacia
            exit();
        }
        else {
            $response["x"] = $x;
            $response["y"] = $y;
            
            $resultado_jugador = comprobar_jugada();
            
            if ($resultado_jugador !== false) {
                session_destroy();
                $response["gameRes"] = $resultado_jugador;
                
                echo(json_encode($response));
                exit();
            }

            [$x_maquina, $y_maquina] = poner_ficha_maquina();
            
            $response["x_maquina"] = $x_maquina;
            $response["y_maquina"] = $y_maquina;
            
            $resultado_maquina = comprobar_jugada(false);
            
            if ($resultado_maquina !== false) {
                session_destroy();
                $response["gameRes"] = $resultado_maquina;
            }
            
            echo(json_encode($response));
            exit();
        }
    }