<?php
// Lo separo en otro archivo ya que estos parámetros son configurables pero los de funciones.php no
$size_tablero = 8;
$num_minas = 10;

if ($size_tablero < 2) {
    define("SIZE_TABLERO", 2);
}
else {
    define("SIZE_TABLERO", $size_tablero);
}

if ($num_minas < 1) {
    define("NUM_MINAS", 1);
}
else {
    if ($num_minas > SIZE_TABLERO * SIZE_TABLERO) {
        define("NUM_MINAS", SIZE_TABLERO * SIZE_TABLERO); // Todo el tablero lleno. No veo el problema ¯\_(ツ)_/¯
    }
    else {
        define("NUM_MINAS", $num_minas);
    }
}
?>

