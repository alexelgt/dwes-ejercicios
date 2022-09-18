<?php
    if (empty($_POST) || strcmp(filter_input(INPUT_POST, "botonenvio"), "reset") == 0) {
        header("Location: /");
    }
    else {
        $min_value = intval(filter_input(INPUT_POST, "min_value_send"));
        $max_value = intval(filter_input(INPUT_POST, "max_value_send"));
        $num_intentos = intval(filter_input(INPUT_POST, "num_intentos_send"));

        // NUMERO A ADIVINAR
        $num_adivinar = filter_input(INPUT_POST, "num_adivinar");
        
        if (isset($num_adivinar)) {
            if (strcmp($num_adivinar, "no_establecido") == 0) {
                $num_adivinar = rand($min_value, $max_value);
            }
            else {
                $num_adivinar = intval($num_adivinar);
            }

            // NUMERO
            $numero = filter_input(INPUT_POST, "numero");

            $numero_valido = isset($numero) && (filter_var($numero, FILTER_VALIDATE_INT) || $numero === "0");

            if ($numero_valido) {
                $numero = intval($numero);
                
                if ($numero == $num_adivinar) {
                    $numero_adivinado = true;
                }
                else {
                    $numero_adivinado = false;
                    $num_intentos--;

                    if ($numero > $num_adivinar) {
                        if ($numero < $max_value) {
                            $max_value = $numero;
                        }
                    }
                    else {
                        if ($numero > $min_value) {
                            $min_value = $numero;
                        }
                    }
                }
            }
            else {
                $numero_adivinado = false;
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Adivina un número (Juego)</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body class="flex-page">
        <h1>Adivina un número (Juego)</h1>
<?php if ($numero_adivinado): ?>
        <form class="form-font capaform" name="Formpartida" 
              action="guess_a_number.php" method="POST">
            <p class="form-section">
            <?php
                echo("¡Ganaste!<br>El número a adivinar era $num_adivinar\n");
            ?>
            </p>
            <div class="form-section">
                <div class="submit-section">
                    <button class="submit" type="submit" value="reset" name="botonenvio">Volver a jugar</button> 
                </div>
            </div>
        </form>
<?php elseif ($num_intentos <= 0): ?>
        <form class="form-font capaform" name="Formpartida" 
              action="guess_a_number.php" method="POST">
            <p class="form-section">
            <?php
                echo("¡Te quedaste sin intentos!<br>El número a adivinar era $num_adivinar\n");
            ?>
            </p>
            <div class="form-section">
                <div class="submit-section">
                    <button class="submit" type="submit" value="reset" name="botonenvio">Volver a jugar</button> 
                </div>
            </div>
        </form>
<?php else:?>
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
                    <label for="numero"<?php if (isset($numero_valido) && !$numero_valido) {echo(' style="color:red"');}?>>Número:</Label> 
                    <input id="numero" type="text" name="numero"/> 
                </div>
                <div class="form-section" style="display:none">
                    <label for="num_adivinar">Número a adivinar (oculto):</Label> 
                    <input id="num_adivinar" type="text" name="num_adivinar" value="<?php echo($num_adivinar);?>"/> 
                </div>
                <div class="form-section">
                    <div class="submit-section">
                        <button class="submit" type="submit" value="Enviar" name="botonenvio">Jugar</button>
                    </div>
                </div>
            </div>
        </form>
<?php if ($numero_valido): ?>
        <div class="form-font capaform" style="margin-top: 20px">
            <p class="form-section">
            <?php
                if ($numero > $num_adivinar) {
                    echo("$numero es mayor que el número a adivinar\n");
                }
                else {
                    echo("$numero es menor que el número a adivinar\n");
                }
            ?>
            </p>
        </div>
<?php else:?>
        <div class="form-font capaform" style="border-color: red; margin-top: 20px">
            <p class="form-section">
                No has introducido un número correcto
            </p>
        </div>
<?php endif;?>
<?php endif;?>
    </body>
</html>