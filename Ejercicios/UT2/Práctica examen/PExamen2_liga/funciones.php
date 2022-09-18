<?php
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }

        return true;
    }
    
    function excede_max_input($num_equipos) {
        $num_inputs = $num_equipos * ($num_equipos - 1) + 1;

        return $num_inputs > ini_get("max_input_vars");
    }
    
    function set_array_datos(&$datos) {
        $datos["puntos"] = 0;
        $datos["goles_conseguidos"] = 0;
        $datos["goles_encajados"] = 0;
        $datos["gol_average"] = 0;
    }