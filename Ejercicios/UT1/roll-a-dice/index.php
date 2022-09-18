<?php
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }
        
        return true;
    }
    
    function tiradasDado($numero_lados, $numero_tiradas) {
        if ($numero_lados < 2) {
            $numero_lados = 6;
        }

        $tiradas = [];
        
        for ($i = 0; $i < $numero_tiradas; $i++) {
            array_push($tiradas, mt_rand(1, $numero_lados));
        }

        return $tiradas;
    }

    if (!empty($_POST)) {
        $numero_tiradas = filter_input(INPUT_POST, "intentos", FILTER_VALIDATE_INT);
        $numero_tiradas_valido = valid_input($numero_tiradas) && $numero_tiradas >= 1;
        
        if ($numero_tiradas_valido) {
            $numero_tiradas = intval($numero_tiradas);

            $texto_output = "";

            $tiradas = tiradasDado(6, $numero_tiradas);
            
            $tiradas_ocurrencias = array_count_values($tiradas);
            
            asort($tiradas_ocurrencias);
            
            foreach ($tiradas_ocurrencias as $tirada => $numero_ocurrencias) {
                $texto_output .= "            <p class=\"form-section\">$tirada: $numero_ocurrencias</p>\n";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Lanzar un dado</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body class="flex-page">
        <h1>Lanzar un dado</h1>
        <form class="form-font capaform" name="Formlanzarundado" 
              action="index.php" method="POST">
            <div class="flex-outer">
                <div class="form-section">
                    <label for="intentos"<?php if (isset($numero_tiradas_valido) && !$numero_tiradas_valido) {echo(' style="color:red"');}?>>Número de tiradas:</Label> 
                    <input id="intentos" type="text" name="intentos" placeholder="Introduce el número de tiradas" value="<?php if (isset($numero_tiradas) && $numero_tiradas_valido) {echo($numero_tiradas);} ?>"/>
                </div>
                <div class="form-section">
                    <div class="submit-section">
                        <input class="submit" type="submit" 
                               value="Enviar" name="botonenvio" /> 
                    </div>
                </div>
            </div>
<?php if (isset($numero_tiradas_valido) && !$numero_tiradas_valido): ?>
            <div class="form-font capaform" style="border-color: red">
                <p class="form-section">
                    Número de tiradas no válida. Introduce un número igual o mayor a 1.
                </p>
            </div>
<?php endif ?>
        </form>  
<?php if (isset($numero_tiradas_valido) && $numero_tiradas_valido): ?>  
        <div class="form-font capaform" style="margin-top: 20px">
<?php
    echo("$texto_output\n");
?>
        </div>
<?php endif ?>
    </body>
</html>

