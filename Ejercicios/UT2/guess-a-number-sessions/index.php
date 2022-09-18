<?php
    require "vendor/autoload.php";
    require "funciones.php";
    
    use Philo\Blade\Blade;
    
    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';
    
    $blade = new Blade($views, $cache);
    
    session_start();
    
    if (!empty($_SESSION["data"])) {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: /guessanumber.php");
        exit();
    }
    
    $data = [];
    
    $data["titulo"] = "Adivina el número";
    
    if (!empty($_POST)) {
        $data["numero_intentos"] = filter_input(INPUT_POST, "intentos", FILTER_VALIDATE_INT);
        $data["numero_intentos_valido"] = valid_input($data["numero_intentos"]) && $data["numero_intentos"] > 0;
        
        $data["limite_superior"] = filter_input(INPUT_POST, "maximo", FILTER_VALIDATE_INT);
        $data["limite_superior_valido"] = valid_input($data["limite_superior"]);
        
        $data["limite_inferior"] = filter_input(INPUT_POST, "minimo", FILTER_VALIDATE_INT);
        $data["limite_inferior_valido"] = valid_input($data["limite_inferior"]);
        
        $data["correcto"] = $data["numero_intentos_valido"] && $data["limite_superior_valido"] && $data["limite_inferior_valido"];
        
        if ($data["correcto"]) {
            // Decido que si max < min también sea correcto pero intercambio los valores
            if ($data["limite_superior"] < $data["limite_inferior"]) {
                $tmp = $data["limite_superior"];

                $data["limite_superior"] = $data["limite_inferior"];
                $data["limite_inferior"] = $tmp;
            }
            
            $data["titulo"] = "Adivina el número - Juego";
            $data["mostrar_volver_jugar"] = true;
            
            $_SESSION["data"] = [];
            
            $_SESSION["data"]["numero_intentos"] = $data["numero_intentos"];
            $_SESSION["data"]["limite_superior"] = $data["limite_superior"];
            $_SESSION["data"]["limite_inferior"] = $data["limite_inferior"];
            $_SESSION["data"]["mostrar_volver_jugar"] = $data["mostrar_volver_jugar"];
            $_SESSION["data"]["num_aleatorio"] = rand($data["limite_inferior"], $data["limite_superior"]);

            echo $blade->view()->make("vista_juego", ["data" => $data])->render();
        }
        else {
            echo $blade->view()->make("vista_inputs", ["data" => $data])->render();
        }
    }
    else {
        session_destroy();
        echo $blade->view()->make("vista_inputs", ["data" => $data])->render();
    }
?>