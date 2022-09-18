<?php
    require 'vendor/autoload.php';
    require 'funciones.php';
    
    use eftec\bladeone\BladeOne;
    
    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';
    
    $blade = new BladeOne($views, $cache);
    
    function empezar_partida() {
        global $blade;

        generar_tablero();
        echo $blade->run("tablero");
        exit();
    }


    if (!empty($_POST)) {
        if (isset($_POST["botoncito"])) {
            empezar_partida();
        }

        header("Content-type: application/json");

        $x = (int) filter_input(INPUT_POST, "x");
        $y = (int) filter_input(INPUT_POST, "y");
        
        $jugada_correcta = realizar_jugada($x, $y);
        
        if ($jugada_correcta) {
            $resultado_jugador = comprobar_jugada();
        
            if ($resultado_jugador === VALUE_JUGADOR) {
                $response = ["gameRes" => VALUE_JUGADOR];
                echo json_encode($response);
                exit();
            }
            elseif ($resultado_jugador === false) {
                $response["gameRes"] = 0;
                echo json_encode($response);
                exit();
            }


            [$x_maquina, $y_maquina] = realizar_jugada_maquina();
            $response = ["x" => $x_maquina, "y" => $y_maquina];

            $resultado_maquina = comprobar_jugada($juega_jugador = false);

            if ($resultado_maquina === VALUE_MAQUINA) {
                $response["gameRes"] = VALUE_MAQUINA;
                echo json_encode($response);
                exit();
            }
            elseif ($resultado_maquina === true) {
                echo json_encode($response);
                exit();
            }
        }
        else {
            echo json_encode([]);
            exit();
        }
        
        
    }
    else {
        empezar_partida();
    }


?>