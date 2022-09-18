<?php
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }
        
        return true;
    }
    
    function is_prime($numero) {
        if ($numero < 1) {
            return false;
        }

        if ($numero == 1 || $numero == 2) {
            return true;
        }

        // Números mayor o igual a 3
        for ($i = 2; $i < $numero; $i++) {
            if ($numero % $i == 0) {
                return false;
            }
        }

        return true;
    }

    if (!empty($_POST)) {
        $numero = filter_input(INPUT_POST, "numero", FILTER_VALIDATE_INT);
        $numero_valido = valid_input($numero);
        
        if ($numero_valido) {
            $numero = intval($numero);
            
            $texto_is_prime = is_prime($numero) ? "$numero SÍ es un número primo" : "$numero NO es un número primo";
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Es primo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body class="flex-page">
        <h1>Es primo</h1>
        <form class="form-font capaform" name="Formnumeroprimo" 
              action="index.php" method="POST">
            <div class="flex-outer">
                <div class="form-section">
                    <label for="numero"<?php if (isset($numero_valido) && !$numero_valido) {echo(' style="color:red"');}?>>Número:</Label> 
                    <input id="numero" type="text" name="numero" placeholder="Introduce un número" value="<?php if (isset($numero) && $numero_valido) {echo($numero);}?>"/> 
                </div>
                <div class="form-section">
                    <div class="submit-section">
                        <input class="submit" type="submit" 
                               value="Enviar" name="botonenvio" /> 
                    </div>
                </div>
            </div>
<?php if (isset($numero_valido) && !$numero_valido): ?>
            <div class="form-font capaform" style="border-color: red">
                <p class="form-section">
                    Número no válido
                </p>
            </div>
<?php endif ?>
        </form>  
<?php if (isset($numero_valido) && $numero_valido): ?>  
        <div class="form-font capaform" style="margin-top: 20px">
            <p class="form-section">
                <?php
                    echo("$texto_is_prime\n");
                ?>
            </p>
        </div>
<?php endif ?>
    </body>
</html>

