<?php
    require 'vendor/autoload.php';
    require 'funciones.php';
    require 'src/classes/PartidaGuessANumber.php';

    use eftec\bladeone\BladeOne;
    use App\PartidaGuessANumber;

    $views = __DIR__ . '/views';
    $cache = __DIR__ . '/cache';

    $blade = new BladeOne($views, $cache);
    
    session_start();
    
    if (empty($_POST) && empty($_SESSION["partida"])) {
        session_destroy();
        echo $blade->run("parametros_juego", ["titulo" => "Guess a number - Parámetros"]);
        exit();
    }
    
    $tipo_formulario = filter_input(INPUT_POST, "tipo_formulario");

    if ($tipo_formulario === "parametros") {
        $numero_intentos = filter_input(INPUT_POST, "intentos", FILTER_VALIDATE_INT);
        $numero_intentos_valido = valid_input($numero_intentos) && $numero_intentos > 0;
        
        $limite_superior = filter_input(INPUT_POST, "maximo", FILTER_VALIDATE_INT);
        $limite_superior_valido = valid_input($limite_superior);
        
        $limite_inferior = filter_input(INPUT_POST, "minimo", FILTER_VALIDATE_INT);
        $limite_inferior_valido = valid_input($limite_inferior);
        
        $datos_validos = $numero_intentos_valido && $limite_superior_valido && $limite_inferior_valido;
        
        if ($datos_validos) {
            $partida = new PartidaGuessANumber(
                $numeroIntentos=$numero_intentos,
                $limiteInferior=$limite_inferior,
                $limiteSuperior=$limite_superior
            );
            
            $_SESSION["partida"] = serialize($partida);

            echo $blade->run("juego", ["titulo" => "Guess a number - Juego", "partida" => $partida]);
            exit();
        }
        else {
            echo $blade->run(
                "parametros_juego",
                [
                    "titulo" => "Guess a number - Parámetros",
                    "numero_intentos" => $numero_intentos,
                    "numero_intentos_valido" => $numero_intentos_valido,
                    "limite_superior" => $limite_superior,
                    "limite_superior_valido" => $limite_superior_valido,
                    "limite_inferior" => $limite_inferior,
                    "limite_inferior_valido" => $limite_inferior_valido
                ]
            );
            exit();
        }
    }
    elseif ($tipo_formulario === "reset") {
        session_destroy();
        echo $blade->run("parametros_juego", ["titulo" => "Guess a number - Parámetros"]);
        exit();
    }
    elseif ($tipo_formulario === "juego") {
        $partida = unserialize($_SESSION["partida"]);

        $numero = filter_input(INPUT_POST, "numero", FILTER_VALIDATE_INT);
        $numero_valido = valid_input($numero);
        
        if ($numero_valido) {
            $estado_partida = $partida->realizarJugada($numero);
        
            if ($estado_partida === $partida::PARTIDA_GANADA) {
                echo $blade->run("juego_terminado", ["titulo" => "Guess a number - Fin", "mensaje_fin" => "Ganaste"]);
            }
            else if ($estado_partida === $partida::PARTIDA_PERDIDA) {
                echo $blade->run("juego_terminado", ["titulo" => "Guess a number - Fin", "mensaje_fin" => "Perdiste"]);
            }
            else if ($estado_partida === $partida::NUMERO_MAYOR) {
                $output_text = $numero . " es mayor que el número a adivinar";
                echo $blade->run("juego_completo", ["titulo" => "Guess a number - Juego", "partida" => $partida, "output_text" => $output_text]);
            }
            else if ($estado_partida === $partida::NUMERO_MENOR) {
                $output_text = $numero . " es menor que el número a adivinar";
                echo $blade->run("juego_completo", ["titulo" => "Guess a number - Juego", "partida" => $partida, "output_text" => $output_text]);
            }
            
            $_SESSION["partida"] = serialize($partida);
            exit();
        }
        else {
            echo $blade->run("juego_error", ["titulo" => "Guess a number - Error", "partida" => $partida, "numero" => $numero, "numero_valido" => $numero_valido]);
            exit();
        }
    }
    elseif (empty($_POST) && !empty($_SESSION["partida"])) {
        $partida = unserialize($_SESSION["partida"]);
        
        echo $blade->run("juego", ["titulo" => "Guess a number - Juego", "partida" => $partida]);
        exit();
    }