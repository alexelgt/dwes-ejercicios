<?php
    function valid_input($variable): bool {
        if ($variable === null || $variable === false) {
            return false;
        }

        return true;
    }
    
    function excede_max_input_permitido($num_equipos) {
        $num_jornadas = ($num_equipos - 1) * 2;
        $num_partidos_jornada = $num_equipos / 2;
        
        $num_inputs = $num_jornadas * $num_partidos_jornada * 2 + 2; // +2: input con string equipos y el botón
        
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
    
    function generar_partidos($equipos_indexes) {
        $num_equipos = count($equipos_indexes);
        $num_jornadas = ($num_equipos - 1) * 2;
        
        $partidos = [];
        
        for ($jornada = 0; $jornada < $num_jornadas; $jornada++) {
            for ($index = 0; $index < $num_equipos / 2; $index++) {
                $equipo1 = $equipos_indexes[$index];
                $equipo2 = $equipos_indexes[$index + $num_equipos / 2];

                $partido = ($jornada < $num_jornadas / 2) ? [$equipo1, $equipo2] : [$equipo2, $equipo1];
                $partidos[$jornada][] = $partido;
            }
            
            rotar_round_robin($equipos_indexes);
        }
        
        return $partidos;
    }
    
    function rotar_round_robin(array &$elementos) {
        // Se asume que es par.
        // Info: https://es.wikipedia.org/wiki/Sistema_de_todos_contra_todos#Algoritmos_de_selección
        
        // Se que se puede hacer más eficiente pero lo prefiero así para tener una mejor idea mental para aplicar la rotación
        
        $num_elementos = count($elementos);

        $primera_mitad = array_slice($elementos, 0, $num_elementos / 2);
        $segunda_mitad = array_slice($elementos, $num_elementos / 2, $num_elementos);
        
        $ultimo_elemento_primera_mitad = $primera_mitad[count($primera_mitad) - 1];
        $primer_elemento_segunda_mitad = $segunda_mitad[0];
        
        $primera_mitad_new = array_merge(
            [$primera_mitad[0], $primer_elemento_segunda_mitad],
            array_slice($primera_mitad, 1, count($primera_mitad) - 2)
        );
        
        $segunda_mitad_new = array_merge(
            array_slice($segunda_mitad, 1),
            [$ultimo_elemento_primera_mitad]
        );
        
        $elementos = array_merge($primera_mitad_new, $segunda_mitad_new);
    }
?>