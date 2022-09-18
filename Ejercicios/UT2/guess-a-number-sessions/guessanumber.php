<?php
    require "vendor/autoload.php";
    require "funciones.php";
    
    use Philo\Blade\Blade;
    
    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';
    
    $blade = new Blade($views, $cache);
    
    session_start();
    
    if (empty($_SESSION["data"]) || (!empty($_POST) && strcmp($_POST["boton"], "reset") == 0)) {
        session_destroy();
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: /");
        exit();
    }

    $data = [];

    $data["titulo"] = "Adivina el número - Juego";
    
    $data["numero_intentos"] = $_SESSION["data"]["numero_intentos"];
    $data["limite_superior"] = $_SESSION["data"]["limite_superior"];
    $data["limite_inferior"] = $_SESSION["data"]["limite_inferior"];
    
    if (empty($_POST)) {
        $data["mostrar_volver_jugar"] = $_SESSION["data"]["mostrar_volver_jugar"];
        echo $blade->view()->make("vista_juego", ["data" => $data])->render();
    }
    else {
        if ($_SESSION["data"]["mostrar_volver_jugar"]) {
            $_SESSION["data"]["mostrar_volver_jugar"] = false;
        }

        $num_aleatorio = $_SESSION["data"]["num_aleatorio"];

        $data["numero"] = filter_input(INPUT_POST, "numero", FILTER_VALIDATE_INT);

        $data["numero_valido"] = valid_input($data["numero"]);

        $data["numero_adivinado"] = false;

        if ($data["numero_valido"]) {
            $data["numero"] = intval($data["numero"]);

            if ($data["numero"] == $num_aleatorio) {
                $data["numero_adivinado"] = true;
            }
            else {
                if ($data["numero"] > $num_aleatorio) {
                    $data["output_text"] = $data["numero"] . " es mayor que el número a adivinar";

                    $data["limite_superior"] = $data["numero"];
                }
                else {
                    $data["output_text"] = $data["numero"] . " es menor que el número a adivinar";

                    $data["limite_inferior"] = $data["numero"];
                }

                $data["numero_intentos"]--;
            }

            if ($data["numero_adivinado"]) {
                session_destroy();
                $data["mensaje_fin"] = "Ganaste";
                echo $blade->view()->make("vista_juego_terminado", ["data" => $data])->render();
            }
            elseif ($data["numero_intentos"] === 0) {
                session_destroy();
                $data["mensaje_fin"] = "Perdiste";
                echo $blade->view()->make("vista_juego_terminado", ["data" => $data])->render();
            }
            else {
                $_SESSION["data"]["numero_intentos"] = $data["numero_intentos"];
                $_SESSION["data"]["limite_superior"] = $data["limite_superior"];
                $_SESSION["data"]["limite_inferior"] = $data["limite_inferior"];

                echo $blade->view()->make("vista_juego_completa", ["data" => $data])->render();
            }
        }
        else {
            echo $blade->view()->make("vista_juego_error", ["data" => $data])->render();
        }
    }
?>