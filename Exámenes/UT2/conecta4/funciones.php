<?php
require "configuracion.php";

define("VALUE_VACIO", 0);

define("VALUE_JUGADOR", 1);
define("VALUE_MAQUINA", -1);
define("VALUE_EMPATE", 0);

define("WIN_JUGADOR", CASILLAS_SEGUIDAS);
define("WIN_MAQUINA", -CASILLAS_SEGUIDAS);

function generar_tablero() {
    $_SESSION["tablero"] = array_fill(0, SIZE, array_fill(0, SIZE, VALUE_VACIO));
}

function poner_ficha_jugador($x) {
    for ($i = 0; $i < count($_SESSION["tablero"][$x]); $i++) {
        if ($_SESSION["tablero"][$x][$i] === VALUE_VACIO) {
            $_SESSION["tablero"][$x][$i] = VALUE_JUGADOR;
            
            return $i;
        }
    }
    
    return null;
}

function poner_ficha_maquina() {
    $size = count($_SESSION["tablero"]);
    
    $x = mt_rand(0, $size - 1);
    
    while (true) { // No se tiene bucle infinito ya que si el tablero se llena se termina la partida
        for ($i = 0; $i < count($_SESSION["tablero"][$x]); $i++) {
            if ($_SESSION["tablero"][$x][$i] === VALUE_VACIO) {
                $_SESSION["tablero"][$x][$i] = VALUE_MAQUINA;

                return [$x, $i];
            }
        }
        
        $x = mt_rand(0, $size - 1);
    }
}

function algunas_casilla_vacia() {
    for ($i = 0; $i < count($_SESSION["tablero"]); $i++) {
        for ($j = 0; $j < count($_SESSION["tablero"][$i]); $j++) {
            if ($_SESSION["tablero"][$i][$j] === VALUE_VACIO) {
                return true;
            }
        }
    }
    
    return false;
}

function comprobar_jugada($es_jugador=true) {
    if ($es_jugador) {
        $value_return = VALUE_JUGADOR;
        $value_win = WIN_JUGADOR;
    }
    else {
        $value_return = VALUE_MAQUINA;
        $value_win = WIN_MAQUINA;
    }
    
    for ($i = 0; $i < count($_SESSION["tablero"]); $i++) {
        for ($j = 0; $j < count($_SESSION["tablero"][$i]); $j++) {
            $horizontal = $_SESSION["tablero"][$i][$j];
            $vertical = $_SESSION["tablero"][$i][$j];
            $diagonal1 = $_SESSION["tablero"][$i][$j];
            $diagonal2 = $_SESSION["tablero"][$i][$j];
            
            for ($avance = 1; $avance < abs($value_win); $avance++) {
                if (isset($_SESSION["tablero"][$i + $avance][$j])) {
                    $horizontal += $_SESSION["tablero"][$i + $avance][$j];
                }
                
                if (isset($_SESSION["tablero"][$i][$j + $avance])) {
                    $vertical += $_SESSION["tablero"][$i][$j + $avance];
                }
                
                if (isset($_SESSION["tablero"][$i + $avance][$j + $avance])) {
                    $diagonal1 += $_SESSION["tablero"][$i + $avance][$j + $avance];
                }
                
                if (isset($_SESSION["tablero"][$i + $avance][$j - $avance])) {
                    $diagonal2 += $_SESSION["tablero"][$i + $avance][$j - $avance];
                }
            }
            
            if (($horizontal === $value_win) || ($vertical === $value_win) || ($diagonal1 === $value_win) || ($diagonal2 === $value_win)) {
                return $value_return;
            }
            
        }
    }
    
    if (!algunas_casilla_vacia()) {
        return VALUE_EMPATE;
    }
    
    return false;
}