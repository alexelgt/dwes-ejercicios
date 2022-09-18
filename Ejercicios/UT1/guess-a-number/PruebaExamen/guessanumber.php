<?php
    if (empty($_POST) || strcmp($_POST["boton"], "reset") == 0) {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: /");
        exit();
    }

    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }

        return true;
    }
    
    if (!empty($_POST)) {
        // Datos ocultos del formulario
        $numero_intentos = intval(filter_input(INPUT_POST, "intentos_input"));
        $limite_superior = intval(filter_input(INPUT_POST, "maximo_input"));
        $limite_inferior = intval(filter_input(INPUT_POST, "minimo_input"));
        $num_aleatorio = intval(filter_input(INPUT_POST, "numero_aleatorio"));
        
        $numero = filter_input(INPUT_POST, "numero", FILTER_VALIDATE_INT);
        
        $numero_valido = valid_input($numero);
        
        $numero_adivinado = false;
        
        if ($numero_valido) {
            $numero = intval($numero);

            if ($numero == $num_aleatorio) {
                $numero_adivinado = true;
            }
            else {
                if ($numero > $num_aleatorio) {
                    $output_text = "$numero es mayor que el número a adivinar";
                    
                    $limite_superior = $numero;
                }
                else {
                    $output_text = "$numero es menor que el número a adivinar";
                    
                    $limite_inferior = $numero;
                }
                
                $numero_intentos--;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>Adivina el número - Juego</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <header>
            <h1>Adivina el número - Juego</h1>
        </header>
        
        <main>
<?php if (!$numero_adivinado && $numero_intentos > 0): ?>
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
                    <label for="numero"<?php if (!$numero_valido) {echo(' class="no-valido"');} ?>>Número</label>
                    <input id="numero" type="text" name="numero" placeholder="Introduce un número">
                </div>
                
                <div>
                    <button type="submit" value="ok" name="boton">Enviar</button>
                </div>
            </form>
<?php if ($numero_valido): ?>   
            <div class="card">
                <p>
                    <?php echo($output_text); ?>
                </p>
            </div>
<?php endif;?>
<?php endif;?>
<?php if (!$numero_adivinado && $numero_intentos === 0): ?>
            <form class="card" name="adivinaelnumero" action="guessanumber.php" method="POST">
                <p>Palmaste pringao.</p>
                
                <div>
                    <button type="submit" value="reset"  name="boton">Volver a jugar</button>
                </div>
            </form>
<?php endif;?>       
<?php if ($numero_adivinado): ?>
            <form class="card" name="adivinaelnumero" action="guessanumber.php" method="POST">
                <p>Ganaste.</p>
                
                <div>
                    <button type="submit" value="reset"  name="boton">Volver a jugar</button>
                </div>
            </form>
<?php endif;?>
<?php if (!$numero_valido): ?>
            <div class="card">
                <p class="no-valido">El número introducido es incorrecto</p>
            </div>
<?php endif;?>
        </main>
    </body>
</html>
