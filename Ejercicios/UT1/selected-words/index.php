<?php
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }
        
        return true;
    }

    function comienzaMayuscula($texto) {
        return preg_match("/^[A-ZÁÉÍÓÚ]/u", $texto);
    }
    
    function longitudDeterminada($texto, $min, $max) {
        return mb_strlen($texto) >= $min && mb_strlen($texto) <= $max;
    }
    
    function tieneNVocales($texto, $numero) {
        return preg_match_all("/[aeiouáéíóú]/iu", $texto) == $numero;
    }
    
    function terminaEnPalabra($texto, $palabra) {
        return preg_match("/$palabra$/", $texto);
    }
    
    function cumpleCondiciones($texto) {
        return comienzaMayuscula($texto) && longitudDeterminada($texto, 8, 10) && tieneNVocales($texto, 4) && terminaEnPalabra($texto, "ero");
    }
    
    function ordenarKeys($a, $b) {
        // EXPLICACION:
        // uksort permite poner una función para comparar keys de un array
        // $a representa a una key y $b representa a otra
        // Si la función retorna un número mayor o igual a 0 se considera que a > b
        // En caso contrario se considera que b > a
        
        // Se pide ordenar primero por longitud de palabras. Por como lo tengo hecho son las keys de un array.
        // Si la primera key tiene mayor longitud tengo que devolver un número positivo y si es menor un número negativo.
        // Si tienen longitudes distintas devuelvo directamente la diferencia de longitudes ya que cumple lo dicho.
        
        // En el caso que ambas keys tengan la misma longitud se pide ordenar alfabéticamente. Este comportamiento es el de la función strcmp.
        // NOTA: se considera que las letras con acento van después que todas las letras sin acento.
        // Ejemplo: las letras Z y Á se ordenarían como Z - Á
        $diferencia_longitudes = mb_strlen($b) - mb_strlen($a); // Se pone de forma inversa a lo explicado para que ordene de mayor a menor. En caso contrario se ordena de menor a mayor.
        
        if ($diferencia_longitudes != 0) {
            return $diferencia_longitudes;
        }
        else {
            return strcoll($a, $b);
        }
    }

    if (!empty($_POST)) {
        $texto = filter_input(INPUT_POST, "texto");
        $texto_valido = valid_input($texto) && strlen($texto) > 0;
        
        if ($texto_valido) {
            $palabras_sin_filtrar = preg_split("/[ \r\n\t\.,:;]+/", $texto);
            
            $palabras_filtradas = array_filter($palabras_sin_filtrar, "cumpleCondiciones");
            
            $palabras_filtradas_mayus = array_map("strtoupper", $palabras_filtradas);
            
            $palabras_ocurrencias = array_count_values($palabras_filtradas_mayus);
            
            if (count($palabras_ocurrencias) == 0) {
                $texto_output = "Ninguna palabra que cumpla los criterios";
            }
            else {
                $texto_output = "";
                
                uksort($palabras_ocurrencias, "ordenarKeys");
                foreach ($palabras_ocurrencias as $palabra => $num_ocurrencias) {
                    $texto_output .= "$palabra ($num_ocurrencias) - ";
                }
                
                $texto_output = substr($texto_output, 0, strlen($texto_output) - 3);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Palabras seleccionadas</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body class="flex-page">
        <h1>Palabras seleccionadas</h1>
        <form class="form-font capaform" name="Formpalabrasseleccionadas" 
              action="index.php" method="POST">
            <div class="flex-outer">
                <div class="form-section">
                    <label for="texto"<?php if (isset($texto_valido) && !$texto_valido) {echo(' style="color:red"');}?>>Texto:</Label> 
                    <textarea id="texto" name="texto" cols="30" rows="10" placeholder="Introduce un texto"><?php if (isset($texto) && $texto_valido) {echo($texto);}?></textarea>
                </div>
                <div class="form-section">
                    <div class="submit-section">
                        <input class="submit" type="submit" 
                               value="Enviar" name="botonenvio" /> 
                    </div>
                </div>
            </div>
<?php if (isset($texto_valido) && !$texto_valido): ?>
            <div class="form-font capaform" style="border-color: red">
                <p class="form-section">
                    Texto no válido
                </p>
            </div>
<?php endif ?>
        </form>  
<?php if (isset($texto_valido) && $texto_valido): ?>  
        <div class="form-font capaform" style="margin-top: 20px">
            <p class="form-section">
                <?php
                    echo("$texto_output\n");
                ?>
            </p>
        </div>
<?php endif ?>
    </body>
</html>

