<?php
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }

        return true;
    }
    
    function excede_max_input($num_ciudades) {
        $num_inputs = $num_ciudades * 12 * 2 + 1;

        return $num_inputs > ini_get("max_input_vars");
    }