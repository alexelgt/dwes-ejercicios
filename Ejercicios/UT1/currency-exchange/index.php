<?php
    function filter_var_boolean($variable, $validate_filter) {
        if (filter_var($variable, $validate_filter) === false) {
            return false;
        }
        
        return true;
    }

    if (!empty($_POST)) {
        $cantidad = filter_input(INPUT_POST, "cantidad");
        $cantidad_valida = $cantidad !== false && filter_var_boolean($cantidad, FILTER_VALIDATE_FLOAT);
        
        $divisa_original = filter_input(INPUT_POST, "divisaoriginal");
        $divisa_original_valida = $divisa_original != false && (strcmp($divisa_original, "EUR") == 0 || strcmp($divisa_original, "USD") == 0 || strcmp($divisa_original, "GBP") == 0 || strcmp($divisa_original, "CNY") == 0);
            
        $divisaaconvertir = filter_input(INPUT_POST, "divisaaconvertir");
        $divisaaconvertir_valida = $divisaaconvertir != false && (strcmp($divisaaconvertir, "EUR") == 0 || strcmp($divisaaconvertir, "USD") == 0 || strcmp($divisaaconvertir, "GBP") == 0 || strcmp($divisaaconvertir, "CNY") == 0);
        
        $datos_correctos = $cantidad_valida && $divisa_original_valida && $divisaaconvertir_valida;
        
        if ($datos_correctos) {
            $exchange_coeffs = [
                "EUR" => [
                    "EUR" => 1,
                    "USD" => 1.1734,
                    "GBP" => 0.86075,
                    "CNY" => 7.5875
                ],
                "USD" => [
                    "EUR" => 0.85224,
                    "USD" => 1,
                    "GBP" => 0.73360,
                    "CNY" => 6.4664
                ],
                "GBP" => [
                    "EUR" => 1.1618,
                    "USD" => 1.3631,
                    "GBP" => 1,
                    "CNY" => 8.8141
                ],
                "CNY" => [
                    "EUR" => 0.13180,
                    "USD" => 0.15465,
                    "GBP" => 0.11345,
                    "CNY" => 1
                ]
            ];
            
            $cantidad_convertida = $cantidad * $exchange_coeffs[$divisa_original][$divisaaconvertir];
            
            $texto_conversion = "$cantidad $divisa_original = $cantidad_convertida $divisaaconvertir";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Cambio de divisas</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body class="flex-page">
        <h1>Cambio de divisas</h1>
        <form class="form-font capaform" name="Formdivisas" 
              action="index.php" method="POST">
            <div class="flex-outer">
                <div class="form-section">
                    <label for="cantidad"<?php if (isset($cantidad) && !$cantidad_valida) {echo(' style="color:red"');}?>>Cantidad:</Label> 
                    <input id="nombre" type="text" name="cantidad" placeholder="Introduce una cantidad" value="<?php if (isset($cantidad) && $cantidad_valida) {echo($cantidad);}?>"/> 
                </div>
                <div class="form-section">
                    <label for="divisaoriginal"<?php if (isset($divisa_original) && !$divisa_original_valida) {echo(' style="color:red"');}?>>Divisa:</Label> 
                    <select id="nomtienda" name="divisaoriginal">
                        <option value="EUR"<?php if (isset($divisa_original) && $divisa_original_valida && strcmp($divisa_original, "EUR") == 0) {echo(" selected");}?>>Euro (EUR)</option>
                        <option value="USD"<?php if (isset($divisa_original) && $divisa_original_valida && strcmp($divisa_original, "USD") == 0) {echo(" selected");}?>>Dólar (USD)</option>
                        <option value="GBP"<?php if (isset($divisa_original) && $divisa_original_valida && strcmp($divisa_original, "GBP") == 0) {echo(" selected");}?>>Libra Esterlina (GBP)</option>
                        <option value="CNY"<?php if (isset($divisa_original) && $divisa_original_valida && strcmp($divisa_original, "CNY") == 0) {echo(" selected");}?>>Yuan (CNY)</option>
                    </select> 
                </div>
                <div class="form-section">
                    <label for="divisaaconvertir"<?php if (isset($divisaaconvertir) && !$divisaaconvertir_valida) {echo(' style="color:red"');}?>>Cambiar a:</Label> 
                    <select id="nomtienda" name="divisaaconvertir">
                        <option value="EUR"<?php if (isset($divisaaconvertir) && $divisaaconvertir_valida && strcmp($divisaaconvertir, "EUR") == 0) {echo(" selected");}?>>Euro (EUR)</option>
                        <option value="USD"<?php if (isset($divisaaconvertir) && $divisaaconvertir_valida && strcmp($divisaaconvertir, "USD") == 0) {echo(" selected");}?>>Dólar (USD)</option>
                        <option value="GBP"<?php if (isset($divisaaconvertir) && $divisaaconvertir_valida && strcmp($divisaaconvertir, "GBP") == 0) {echo(" selected");}?>>Libra Esterlina (GBP)</option>
                        <option value="CNY"<?php if (isset($divisaaconvertir) && $divisaaconvertir_valida && strcmp($divisaaconvertir, "CNY") == 0) {echo(" selected");}?>>Yuan (CNY)</option>
                    </select> 
                </div>
                <div class="form-section">
                    <div class="submit-section">
                        <input class="submit" type="submit" 
                               value="Enviar" name="botonenvio" /> 
                    </div>
                </div>
            </div>
<?php if (isset($datos_correctos) && !$datos_correctos): ?>
            <div class="form-font capaform" style="border-color: red">
<?php if (!$cantidad_valida): ?>
                <p class="form-section">
                    Cantidad no válida
                </p>
<?php endif ?>
<!-- En principio los 2 últimos campos son siempre válidos pero hago la comprobación por si acaso se modifica el contenido del POST -->
<?php if (!$divisa_original_valida): ?>
                <p class="form-section">
                    Divisa original no válida
                </p>
<?php endif ?>
<?php if (!$divisaaconvertir_valida): ?>
                <p class="form-section">
                    Divisa a convertir no válida
                </p>
<?php endif ?>
            </div>
<?php endif ?>
        </form>  
<?php if (isset($datos_correctos) && $datos_correctos): ?>  
        <div class="form-font capaform" style="margin-top: 20px">
            <p class="form-section">
                <?php
                    echo("$texto_conversion\n");
                ?>
            </p>
        </div>
<?php endif ?>
    </body>
</html>

