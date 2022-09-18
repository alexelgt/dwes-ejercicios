<?php
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }
        
        return true;
    }
    
    function tabla_multiplicar($numero) {
        $texto_tabla = "\n            <table>\n                <thead>\n                    <tr><th>Tabla del $numero</th></tr>\n                </thead>\n                    <tbody>";
        
        for ($i = 1; $i <= 10; $i++) {
            $texto_tabla .= "\n                    <tr><td>$numero x $i = " . $numero * $i . "</td></tr>";
        }
        
        return "$texto_tabla\n                </tbody>\n            </table>\n";
    }

    if (!empty($_POST)) {
        $cadena_numeros = filter_input(INPUT_POST, "cadena_numeros");
        $cadena_numeros_valida = valid_input($cadena_numeros) && preg_match("/^([1-9](\-[1-9])?,)*[1-9](\-[1-9])?$/", $cadena_numeros);
        
        if ($cadena_numeros_valida) {
            $texto_tablas = "";
            
            $cadena_numeros_array = explode(",", $cadena_numeros);
            
            $numeros_array = [];
            
            for ($i = 0; $i < count($cadena_numeros_array); $i++) {
                if (preg_match("/[1-9]\-[1-9]/", $cadena_numeros_array[$i])) {
                    [$min, $max] = array_map("intval", explode("-", $cadena_numeros_array[$i]));
                    
                    if ($max < $min) {
                        $tmp = $max;
                        
                        $max = $min;
                        $min = $tmp;
                    }
                    
                    $numeros_array = array_merge($numeros_array, range($min, $max));
                    $numeros_array = array_unique($numeros_array);
                }
                else {
                    $num = intval($cadena_numeros_array[$i]);
                    if (!in_array($num, $numeros_array)) {
                        array_push($numeros_array, $num);
                    }
                }
            }
            
            sort($numeros_array);
            
            for ($i = 0; $i < count($numeros_array); $i++) {
                $texto_tablas .= tabla_multiplicar($numeros_array[$i]);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Tabla de multiplicación</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body class="flex-page">
        <h1>Tabla de multiplicación</h1>
        <form class="form-font capaform" name="Formtablas" 
              action="index.php" method="POST">
            <div class="flex-outer">
                <div class="form-section">
                    <label for="cadena_numeros"<?php if (isset($cadena_numeros_valida) && !$cadena_numeros_valida) {echo(' style="color:red"');}?>>Cadena de números:</Label> 
                    <input id="cadena_numeros" type="text" name="cadena_numeros" placeholder="Introduce una cadena de números" value="<?php if (isset($cadena_numeros) && $cadena_numeros_valida) {echo($cadena_numeros);}?>"/> 
                </div>
                <div class="form-section">
                    <div class="submit-section">
                        <input class="submit" type="submit" 
                               value="Enviar" name="botonenvio" /> 
                    </div>
                </div>
            </div>
<?php if (isset($cadena_numeros_valida) && !$cadena_numeros_valida): ?>
            <div class="form-font capaform" style="border-color: red">
                <p class="form-section">
                    Cadena de números no válida
                </p>
            </div>
<?php endif ?>
        </form>  
<?php if (isset($cadena_numeros_valida) && $cadena_numeros_valida): ?>  
        <div class="form-font capaform flex-tablas" style="margin-top: 20px">
            <?php
                echo("$texto_tablas\n");
            ?>
        </div>
<?php endif ?>
    </body>
</html>

