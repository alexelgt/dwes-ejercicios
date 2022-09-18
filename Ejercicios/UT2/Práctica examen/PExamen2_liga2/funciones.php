<?php
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }

        return true;
    }
    
    function excede_max_input($num_equipos) {
        $num_inputs = $num_equipos * ($num_equipos - 1) * 2 + 1;

        return $num_inputs > ini_get("max_input_vars");
    }
    
    function empty_resultados($equipos) {
        $resultados = [];
        
        foreach ($equipos as $equipo) {
            $resultados[$equipo]["puntos"] = 0;
            $resultados[$equipo]["goles_conseguidos"] = 0;
            $resultados[$equipo]["goles_encajados"] = 0;
            $resultados[$equipo]["gol_average"] = 0;
        }
        
        return $resultados;
    }