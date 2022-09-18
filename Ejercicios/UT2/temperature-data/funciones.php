<?php
    function valid_input($variable): bool {
        if ($variable === null || $variable === false) {
            return false;
        }

        return true;
    }
    
    function excede_max_input_permitido($num_ciudades) {
        $num_inputs_por_ciudad = 12 * 2;
        
        $num_inputs = $num_inputs_por_ciudad * $num_ciudades + 1; // +1: botón
        
        return $num_inputs > ini_get("max_input_vars");
    }
    
    function obtener_palabras(string $cadena): array {
        $palabras = preg_split("/[.,:;\t((\r\n))]+/", $cadena);
        
        $palabras = array_map("mb_strtolower", $palabras);
        
        $palabras = array_map("trim", $palabras);
        
        $palabras = array_map("ucwords", $palabras);
        
        $palabras = array_unique($palabras);
        
        return $palabras;
    }
    
    function obtener_numero_ciudades(): int {
        $numero_ciudades = 0;
        
        while (true) {
            $name_input = "ciudad_" . $numero_ciudades;
            $input = filter_input(INPUT_POST, $name_input);
            
            if (!valid_input($input)) {
                return $numero_ciudades;
            }
            else {
                $numero_ciudades++;
            }
        }
    }
?>