<?php
    require 'vendor/autoload.php';
    require 'funciones.php';
    
    use eftec\bladeone\BladeOne;
    
    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';
    
    $blade = new BladeOne($views, $cache);
    
    session_start();
    
    if (!empty($_POST)) {
        if (isset($_POST["botoncito"])) {
            session_destroy();
            session_start();
            generar_tablero();

            echo $blade->run("tablero", ["size_tablero" => SIZE_TABLERO, "num_minas" => NUM_MINAS]);
            exit();
        }
        
        header("Content-type: application/json");

        $x = (int) filter_input(INPUT_POST, "x");
        $y = (int) filter_input(INPUT_POST, "y");
        
        $casillas_actualizadas = actualizar_tablero_visible($y, $x);
        
        $response["casillas_actualizadas"] = $casillas_actualizadas;
        
        $estado = estado_partida();
        
        if ($estado !== VALUE_SIGUE) {
            if ($estado === VALUE_GANADO) {
                $response["minas"] = obtener_minas();
            }
            session_destroy();
            $response["gameRes"] = $estado;
        }
        
        echo json_encode($response);
    }
    else {
        session_destroy();
        session_start();
        generar_tablero();

        echo $blade->run("tablero", ["size_tablero" => SIZE_TABLERO, "num_minas" => NUM_MINAS]);
        exit();
    }
    
?>