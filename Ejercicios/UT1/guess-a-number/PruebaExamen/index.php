<?php
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }

        return true;
    }
    
    if (!empty($_POST)) {
        $numero_intentos = filter_input(INPUT_POST, "intentos", FILTER_VALIDATE_INT);
        $numero_intentos_valido = valid_input($numero_intentos) && $numero_intentos > 0;
        
        $limite_superior = filter_input(INPUT_POST, "maximo", FILTER_VALIDATE_INT);
        $limite_superior_valido = valid_input($limite_superior);
        
        $limite_inferior = filter_input(INPUT_POST, "minimo", FILTER_VALIDATE_INT);
        $limite_inferior_valido = valid_input($limite_inferior);
        
        $datos_correctos = $numero_intentos_valido && $limite_superior_valido && $limite_inferior_valido;
        
        if ($datos_correctos) {
            // Decido que si max < min también sea correcto pero intercambio los valores
            if ($limite_superior < $limite_inferior) {
                $tmp = $limite_superior;

                $limite_superior = $limite_inferior;
                $limite_inferior = $tmp;
            }

            $num_aleatorio = rand($limite_inferior, $limite_superior);
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>Adivina el número</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <header>
            <h1>Adivina el número</h1>
        </header>
        
        <main>
<?php if (!isset($datos_correctos) || (isset($datos_correctos) && !$datos_correctos)): ?>
            <form class="card" name="adivinaelnumero_datos" action="index.php" method="POST">
                <div>
                    <label for="intentos"<?php if (isset($numero_intentos) && !$numero_intentos_valido) {echo('class="no-valido"');} ?>>Número intentos</label>
                    <input id="intentos" type="text" name="intentos" placeholder="Introduce el número de intentos" value="<?php if (isset($numero_intentos) && $numero_intentos_valido) {echo($numero_intentos);} ?>">
                </div>
                
                <div>
                    <label for="minimo"<?php if (isset($limite_inferior) && !$limite_inferior_valido) {echo('class="no-valido"');} ?>>Límite inferior</label>
                    <input id="minimo" type="text" name="minimo" placeholder="Introduce el límite inferior" value="<?php if (isset($limite_inferior) && $limite_inferior_valido) {echo($limite_inferior);} ?>">
                </div>
                
                <div>
                    <label for="maximo"<?php if (isset($limite_superior) && !$limite_superior_valido) {echo('class="no-valido"');} ?>>Límite superior</label>
                    <input id="maximo" type="text" name="maximo" placeholder="Introduce el límite superior" value="<?php if (isset($limite_superior) && $limite_superior_valido) {echo($limite_superior);} ?>">
                </div>
                
                <div>
                    <button type="submit">Enviar</button>
                </div>
            </form>
<?php endif; ?>
<?php if (isset($datos_correctos) && !$datos_correctos): ?>
            <div class="card">
<?php if (!$numero_intentos_valido): ?>
                <p class="no-valido">Número de intentos no válido. Introduce un número mayor a 0.</p>
<?php endif; ?>
<?php if (!$limite_inferior_valido): ?>
                <p class="no-valido">Límite inferior no válido.</p>
<?php endif; ?>
<?php if (!$limite_superior_valido): ?>
                <p class="no-valido">Límite superior no válido.</p>
<?php endif; ?>
            </div>
<?php endif; ?>
<?php if (isset($datos_correctos) && $datos_correctos): ?>
            <form class="card" name="adivinaelnumero" action="guessanumber.php" method="POST">
                <p>
                    <?php echo("Número de intentos: $numero_intentos") ?>
                </p>
                <p>
                    <?php echo("Límite inferior: $limite_inferior") ?>
                </p>
                <p>
                    <?php echo("Límite superior: $limite_superior") ?>
                </p>
                
                <input id="intentos_input" type="hidden" name="intentos_input" value="<?php echo($numero_intentos); ?>">

                <input id="minimo_input" type="hidden" name="minimo_input" value="<?php echo($limite_inferior); ?>">
                
                <input id="maximo_input" type="hidden" name="maximo_input" value="<?php echo($limite_superior); ?>">
                
                <input id="numero_aleatorio" type="hidden" name="numero_aleatorio" value="<?php echo($num_aleatorio); ?>">
                
                <div>
                    <label for="numero">Número</label>
                    <input id="numero" type="text" name="numero" placeholder="Introduce un número">
                </div>
                
                <div>
                    <button type="submit" value="ok" name="boton">Enviar</button>
                </div>

                <div>
                    <button type="submit" value="reset"  name="boton">Volver a empezar</button>
                </div>
            </form>
<?php endif; ?>
            
        </main>
    </body>
</html>
