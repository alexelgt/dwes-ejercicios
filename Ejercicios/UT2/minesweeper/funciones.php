<?php
require 'configuracion.php';

define("VALUE_VACIO", 0);
define("VALUE_MINA", -1);

define("VALUE_OCULTO", 0);
define("VALUE_VISIBLE", 1);

define("VALUE_PERDIDO", -1);
define("VALUE_GANADO", 1);
define("VALUE_SIGUE", 0);

function obtener_vecinos($y, $x) {
    $vecinos = [];

    for ($i_near = $y - 1; $i_near <= $y + 1; $i_near++) {
        for ($j_near = $x - 1; $j_near <= $x + 1; $j_near++) {
            if (isset($_SESSION["tablero"][$i_near][$j_near]) && !($i_near == $y && $j_near == $x)) {
                $vecinos[] = [$i_near, $j_near];
            }
        }
    }
    
    return $vecinos;
}

function obtener_minas() {
    $minas = [];

    for ($y = 0; $y < count($_SESSION["tablero"]); $y++) {
        for ($x = 0; $x < count($_SESSION["tablero"][$y]); $x++) {
            if ($_SESSION["tablero"][$y][$x] === VALUE_MINA) {
                $minas[] = [
                    "x" => $x,
                    "y" => $y,
                    "value" => $_SESSION["tablero"][$y][$x]
                ];
            }
        }
    }

    return $minas;
}

function generar_tablero() {
    $_SESSION["tablero_visible"] = array_fill(0, SIZE_TABLERO, array_fill(0, SIZE_TABLERO, VALUE_OCULTO));
    $_SESSION["tablero"] = array_fill(0, SIZE_TABLERO, array_fill(0, SIZE_TABLERO, VALUE_VACIO));
    
    $num_minas = NUM_MINAS;
    
    // Aunque el número de minas debería ser 8, añado una pequeña protección por si se pone un valor superior al número de casillas
    if ($num_minas > SIZE_TABLERO * SIZE_TABLERO) {
        $num_minas = SIZE_TABLERO * SIZE_TABLERO;
    }
    
    while ($num_minas != 0) {
        $x = mt_rand(0, SIZE_TABLERO - 1);
        $y = mt_rand(0, SIZE_TABLERO - 1);
        
        if ($_SESSION["tablero"][$y][$x] != VALUE_MINA) {
            $_SESSION["tablero"][$y][$x] = VALUE_MINA;
            
            $num_minas--;
        }
    }
    
    function minas_alrededor($y, $x) {
        $minas_alrededor = 0;
        
        $vecinos = obtener_vecinos($y, $x);
        
        foreach ($vecinos as $vecino) {
            if ($_SESSION["tablero"][$vecino[0]][$vecino[1]] == VALUE_MINA) {
                $minas_alrededor++;
            }
        }
        
        return $minas_alrededor;
    }
    
    for ($i = 0; $i < count($_SESSION["tablero"]); $i++) {
        for ($j = 0; $j < count($_SESSION["tablero"][$i]); $j++) {
            if ($_SESSION["tablero"][$i][$j] == VALUE_VACIO) {
                $_SESSION["tablero"][$i][$j] = minas_alrededor($i, $j);
            }
        }
    }
}

function actualizar_tablero_visible($y, $x, $posicion_elegida=true) {
    $posiciones_visisbles = [];

    if ($_SESSION["tablero"][$y][$x] === VALUE_MINA) {
        if ($posicion_elegida) {
            $minas = obtener_minas();
            
            foreach ($minas as $mina) {
                $_SESSION["tablero_visible"][$mina["y"]][$mina["x"]] = VALUE_VISIBLE;
            }

            $_SESSION["tablero_visible"] = array_fill(0, SIZE_TABLERO, array_fill(0, SIZE_TABLERO, VALUE_VISIBLE));
        
            return $minas;
        }
        
        return [];
    }
    else {
        if ($_SESSION["tablero_visible"][$y][$x] === VALUE_VISIBLE) {
            return [];
        }
        
        $posiciones_visisbles[] = [
            "x" => $x,
            "y" => $y,
            "value" => $_SESSION["tablero"][$y][$x]
        ];
        
        $_SESSION["tablero_visible"][$y][$x] = VALUE_VISIBLE;
        
        if ($_SESSION["tablero"][$y][$x] === VALUE_VACIO) {
            $vecinos = obtener_vecinos($y, $x);
            
            foreach ($vecinos as $vecino) {
                $datos_vecino = actualizar_tablero_visible($vecino[0], $vecino[1], $posicion_elegida=false);
                
                if (count($datos_vecino) !== 0) {
                    $posiciones_visisbles = array_merge($posiciones_visisbles, $datos_vecino);
                }
            }
        }
    }
    
    return $posiciones_visisbles;
}

function estado_partida() {
    $estado = VALUE_GANADO;
    for ($i = 0; $i < count($_SESSION["tablero"]); $i++) {
        for ($j = 0; $j < count($_SESSION["tablero"][$i]); $j++) {
            if ($_SESSION["tablero"][$i][$j] === VALUE_MINA && $_SESSION["tablero_visible"][$i][$j] === VALUE_VISIBLE) {
                return VALUE_PERDIDO;
            }
            
            if ($_SESSION["tablero"][$i][$j] !== VALUE_MINA && $_SESSION["tablero_visible"][$i][$j] === VALUE_OCULTO) {
                $estado = VALUE_SIGUE;
            }
        }
    }
    
    return $estado;
}

?>

