<?php
    function valid_input($input) {
        if ($input === null || $input === false) {
            return false;
        }
        
        return true;
    }
    
    function comienza_mayus($palabra) {
        return preg_match("/^[A-ZÁÉÍÓÚ]/u", $palabra);
    }
    
    function tiene_caracteres($palabra, $min, $max) {
        return mb_strlen($palabra) >= $min && mb_strlen($palabra) <= $max;
    }
    
    function tiene_n_vocales($palabra, $numero_vocales) {
        return preg_match_all("/[aeiouáéíóú]/iu", $palabra) == $numero_vocales;
    }
    
    function termina_en_secuencia($palabra, $secuencia) {
        return preg_match("/$secuencia$/i", $palabra);
    }
    
    function palabra_cumple_condiciones($palabra) {
        return comienza_mayus($palabra) && tiene_caracteres($palabra, 8, 10) && tiene_n_vocales($palabra, 4) && termina_en_secuencia($palabra, "ero");
    }
    
    function criterio_sort($a, $b) {
        $diferencias_len = mb_strlen($b) - mb_strlen($a);
        
        if ($diferencias_len != 0) {
            return $diferencias_len;
        }
        
        return strcoll($a, $b);
    }
    
    if (!empty($_POST)) {
        $cadena_palabras_texto = filter_input(INPUT_POST, "cadena_palabras");
        $cadena_palabras_texto_valida = valid_input($cadena_palabras_texto) && strlen($cadena_palabras_texto) > 0;

        $palabras = preg_split("/[ (\n\r)\t.,:;]/", $cadena_palabras_texto);
        
        $palabras_filtradas = array_filter($palabras, "palabra_cumple_condiciones");
        
        $palabras_filtradas_mayus = array_map("strtoupper", $palabras_filtradas);
        
        $palabras_ocurrencias = array_count_values($palabras_filtradas_mayus);
        
        uksort($palabras_ocurrencias, "criterio_sort");
        
        if (count($palabras_ocurrencias) == 0) {
            $texto_output = "Ninguna palabra cumple las condiciones.";
        }
        else {
            $texto_output = "";
            
            foreach ($palabras_ocurrencias as $palabra => $ocurrencias) {
                $texto_output .= "$palabra ($ocurrencias) - ";
            }

            $texto_output = trim($texto_output, " - ");
        }
    }

    
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>Selected Words</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <header>
            <h1>Selected Words</h1>
        </header>
        
        <main>
            <form class="card" name="selectedwords" action="index.php" method="POST">
                <div>
                    <label for="cadena_palabras"<?php if (isset($cadena_palabras_texto) && !$cadena_palabras_texto_valida) {echo(' class="no-valido"');} ?>>Cadena de palabras</label>
                    <textarea id="cadena_palabras" name="cadena_palabras" rows="5" cols="10"><?php if (isset($cadena_palabras_texto) && $cadena_palabras_texto_valida) {echo($cadena_palabras_texto);} ?></textarea>
                </div>
                
                <div>
                    <button type="submit">Enviar</button>
                </div>
            </form>
<?php if (isset($texto_output) && $cadena_palabras_texto_valida): ?>
            <div class="card">
                <p><?php echo($texto_output); ?></p>
            </div>
<?php endif; ?>
<?php if (isset($texto_output) && !$cadena_palabras_texto_valida): ?>
            <div class="card">
                <p>Cadena no válida.</p>
            </div>
<?php endif; ?>
        </main>
    </body>
</html>