<?php
    if (!empty($_POST)) {
        // VALOR MÍNIMO
        $min_value = filter_input(INPUT_POST, "min_value");

        $min_value_valido = isset($min_value) && (filter_var($min_value, FILTER_VALIDATE_INT) || $min_value === "0");

        if ($min_value_valido) {
            $min_value = intval($min_value);
        }

        // VALOR MÁXIMO
        $max_value = filter_input(INPUT_POST, "max_value");

        $max_value_valido = isset($max_value) && (filter_var($max_value, FILTER_VALIDATE_INT) || $max_value === "0");

        if ($max_value_valido) {
            $max_value = intval($max_value);
        }
        
        // COMPROBACIÓN MAX > MIN
        $max_min_correctos = $max_value >= $min_value;

        // INTENTOS
        $num_intentos = filter_input(INPUT_POST, "num_intentos");

        $num_intentos_valido = isset($num_intentos) && filter_var($num_intentos, FILTER_VALIDATE_INT);

        if ($num_intentos_valido) {
            $num_intentos = intval($num_intentos);
            
            if ($num_intentos < 1) {
                $num_intentos_valido = false;
            }
        }

        $datos_correctos = $min_value_valido && $max_value_valido && $num_intentos_valido && $max_min_correctos;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Adivina un número</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body class="flex-page">
        <h1>Adivina un número</h1>
<?php if (!isset($datos_correctos) || (isset($datos_correctos) && !$datos_correctos)): ?>
        <form class="form-font capaform" name="Formrdatosjuego" 
              action="index.php" method="POST">
            <div class="flex-outer">
                <div class="form-section">
                    <label for="min_value"<?php if ((isset($min_value_valido) && !$min_value_valido) || (isset($max_min_correctos) && !$max_min_correctos)) {echo(' style="color:red"');}?>>Valor mínimo:</Label> 
                    <input id="min_value" type="text" name="min_value" placeholder="Escribe el valor mínimo" value="<?php if (isset($min_value_valido) && $min_value_valido && isset($max_min_correctos) && $max_min_correctos) {echo($min_value);} ?>"/> 
                </div>
                <div class="form-section">
                    <label for="max_value"<?php if ((isset($max_value_valido) && !$max_value_valido) || (isset($max_min_correctos) && !$max_min_correctos)) {echo(' style="color:red"');}?>>Valor máximo:</Label> 
                    <input id="max_value" type="number" name="max_value" placeholder="Escribe el valor máximo" value="<?php if (isset($max_value_valido) && $max_value_valido && isset($max_min_correctos) && $max_min_correctos) {echo($max_value);} ?>"/> 
                </div>
                <div class="form-section">
                    <label for="num_intentos"<?php if (isset($num_intentos_valido) && !$num_intentos_valido) {echo(' style="color:red"');}?>>Número de intentos:</Label> 
                    <input id="num_intentos" type="text" name="num_intentos" min="0" placeholder="Escribe el número de intentos" value="<?php if (isset($num_intentos_valido) && $num_intentos_valido) {echo($num_intentos);} ?>"/> 
                </div>
                <div class="form-section">
                    <div class="submit-section">
                        <input class="submit" type="submit" 
                               value="Enviar" name="botonenvio" /> 
                    </div>
                </div>
            </div>
<?php if (isset($datos_correctos) && !$datos_correctos): ?>
            <div class="form-font capaform" style="border-color:red">
<?php if (isset($min_value_valido) && !$min_value_valido): ?>
                <p class="form-section">
                    Valor mínimo no válido
                </p>
<?php endif; ?>
<?php if (isset($max_value_valido) && !$max_value_valido): ?>
                <p class="form-section">
                    Valor máximo no válido
                </p>
<?php endif; ?>
<?php if (isset($max_min_correctos) && !$max_min_correctos): ?>
                <p class="form-section">
                    El valor máximo es inferior al valor mínimo
                </p>
<?php endif; ?>
<?php if (isset($num_intentos_valido) && !$num_intentos_valido): ?>
                <p class="form-section">
                    Número de intentos no válido
                </p>
<?php endif; ?>
            </div>
<?php endif; ?>
        </form>  
<?php else: ?>
        <form class="form-font capaform" name="Formpartida" 
              action="guess_a_number.php" method="POST">
            <div class="flex-outer">
                <p class="form-section">
                    <?php
                        echo "Valor mínimo: $min_value\n";
                    ?>
                </p>
                <p class="form-section">
                    <?php
                        echo "Valor máximo: $max_value\n";
                    ?>
                </p>
                <p class="form-section">
                    <?php
                        echo "Número de intentos: $num_intentos\n";
                    ?>
                </p>
                <hr>
                <div class="form-section" style="display:none">
                    <label for="min_value_send">Valor mínimo (oculto):</Label> 
                    <input id="min_value_send" type="text" name="min_value_send" value="<?php echo($min_value);?>"/> 
                </div>
                <div class="form-section" style="display:none">
                    <label for="max_value_send">Valor máximo (oculto):</Label> 
                    <input id="max_value_send" type="text" name="max_value_send" value="<?php echo($max_value);?>"/> 
                </div>
                <div class="form-section" style="display:none">
                    <label for="num_intentos_send">Número de intentos (oculto):</Label> 
                    <input id="num_intentos_send" type="text" name="num_intentos_send" value="<?php echo($num_intentos);?>"/> 
                </div>
                <div class="form-section">
                    <label for="numero">Número:</Label> 
                    <input id="numero" type="text" name="numero"/> 
                </div>
                <div class="form-section" style="display:none">
                    <label for="num_adivinar">Número a adivinar (oculto):</Label> 
                    <input id="num_adivinar" type="text" name="num_adivinar" value="no_establecido"/> 
                </div>
                <div class="form-section">
                    <div class="submit-section">
                        <button class="submit" type="submit" value="Enviar" name="botonenvio">Jugar</button>
                    </div>
                </div>
                <div class="form-section">
                    <div class="submit-section">
                        <button class="submit" type="submit" value="reset" name="botonenvio">Volver a introducir datos</button> 
                    </div>
                </div>
            </div>
        </form> 
<?php endif; ?>
    </body>
</html>