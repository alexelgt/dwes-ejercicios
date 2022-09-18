<?php

define("VALUE_VACIO", 0);
define("VALUE_JUGADOR", 1);
define("VALUE_MAQUINA", -1);

define("VALUE_JUGADOR_GANA", 3);
define("VALUE_MAQUINA_GANA", -3);


function generar_tablero() {
    session_start();
    
    $_SESSION["tablero"] = array_fill(0, 3, array_fill(0, 3, VALUE_VACIO));
    
    session_write_close();
}

function realizar_jugada(int $x, int $y) {
    session_start();
    
    if ($_SESSION["tablero"][$y][$x] === VALUE_VACIO) {
        $_SESSION["tablero"][$y][$x] = VALUE_JUGADOR;

        $jugada_correcta = true;
    }
    else {
        $jugada_correcta = false;
    }

    session_write_close();
    
    return $jugada_correcta;
}

function realizar_jugada_maquina() {
    session_start();
    
    $jugada_correcta = false;
    
    while (!$jugada_correcta) {
        $x = mt_rand(0, 2);
        $y = mt_rand(0, 2);
        
        if ($_SESSION["tablero"][$y][$x] === VALUE_VACIO) {
            $jugada_correcta = true;
            
            $_SESSION["tablero"][$y][$x] = VALUE_MAQUINA;
        }
    }
    
    session_write_close();
    
    return [$x, $y];
}

function comprobar_jugada($juega_jugador = true) {
    if ($juega_jugador) {
        $value = VALUE_JUGADOR_GANA;
        $value_return = VALUE_JUGADOR;
    }
    else {
        $value = VALUE_MAQUINA_GANA;
        $value_return = VALUE_MAQUINA;
    }
    
    session_start();
    
    if (
        $_SESSION["tablero"][0][0] + $_SESSION["tablero"][1][0] + $_SESSION["tablero"][2][0] === $value ||
        $_SESSION["tablero"][0][1] + $_SESSION["tablero"][1][1] + $_SESSION["tablero"][2][1] === $value ||
        $_SESSION["tablero"][0][2] + $_SESSION["tablero"][1][2] + $_SESSION["tablero"][2][2] === $value ||
            
        $_SESSION["tablero"][0][0] + $_SESSION["tablero"][0][1] + $_SESSION["tablero"][0][2] === $value ||
        $_SESSION["tablero"][1][0] + $_SESSION["tablero"][1][1] + $_SESSION["tablero"][1][2] === $value ||
        $_SESSION["tablero"][2][0] + $_SESSION["tablero"][2][1] + $_SESSION["tablero"][2][2] === $value ||
            
            
        $_SESSION["tablero"][0][0] + $_SESSION["tablero"][1][1] + $_SESSION["tablero"][2][2] === $value ||
        $_SESSION["tablero"][0][2] + $_SESSION["tablero"][1][1] + $_SESSION["tablero"][2][0] === $value
    ) {
        session_write_close();
        return $value_return;
    }
    
    $casilla_libre = false;
    foreach ($_SESSION["tablero"] as $fila) {
        $casilla_libre = $casilla_libre || in_array(VALUE_VACIO, $fila);
    }
    
    session_write_close();
    return $casilla_libre;
}
