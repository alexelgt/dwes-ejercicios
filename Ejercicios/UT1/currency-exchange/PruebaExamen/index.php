<?php
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }

        return true;
    }
    
    function convertir_moneda($cantidad, $divisa_origen, $divisa_destino) {
        $datos_monedas = [
            "EUR" => [
                    "EUR" => 1.0,
                    "USD" => 1.1601,
                    "GBP" => 0.84505,
                    "CNY" => 7.4651
                ],
            "USD" => [
                    "EUR" => 0.86197,
                    "USD" => 1.0,
                    "GBP" => 0.72840,
                    "CNY" => 6.4347
                ],
            "GBP" => [
                    "EUR" => 1.1834,
                    "USD" => 1.3729,
                    "GBP" => 1.0,
                    "CNY" => 8.8339
                ],
            "CNY" => [
                    "EUR" => 0.13396,
                    "USD" => 0.15541,
                    "GBP" => 0.11320,
                    "CNY" => 1.0
                ]
        ];
        
        return $cantidad * $datos_monedas[$divisa_origen][$divisa_destino];
    }

    if (!empty($_POST)) {
        $cantidad = filter_input(INPUT_POST, "cantidad", FILTER_VALIDATE_FLOAT);
        $cantidad_valida = valid_input($cantidad) && $cantidad > 0;
        
        $divisa_origen = filter_input(INPUT_POST, "divisa_origen");
        $divisa_origen_valida = valid_input($divisa_origen) && preg_match("/^(EUR|USD|GBP|CNY)$/", $divisa_origen);
        
        $divisa_destino = filter_input(INPUT_POST, "divisa_destino");
        $divisa_destino_valida = valid_input($divisa_destino) && preg_match("/^(EUR|USD|GBP|CNY)$/", $divisa_destino);
        
        $datos_correctos = $cantidad_valida && $divisa_origen_valida && $divisa_destino_valida;
        
        if ($datos_correctos) {
            $cantidad_convertida = convertir_moneda($cantidad, $divisa_origen, $divisa_destino);
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>Cambio de monedas</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <header>
            <h1>Cambio de monedas</h1>
        </header>
        
        <main>
            <form class="card" name="cambiomonedas" action="index.php" method="POST">
                <div>
                    <label for="cantidad"<?php if(isset($cantidad) && !$cantidad_valida) {echo('class="no-valido"');} ?>>Cantidad</label>
                    <input id="cantidad" type="text" name="cantidad" placeholder="Introduce una cantidad" value="<?php if(isset($cantidad) && $cantidad_valida) {echo($cantidad);} ?>">
                </div>
                
                <div>
                    <label for="divisa_origen"<?php if(isset($divisa_origen) && !$divisa_origen_valida) {echo('class="no-valido"');} ?>>Divisa origen</label>
                    <select id="divisa_origen" name="divisa_origen">
                        <option value="EUR"<?php if (isset($divisa_origen) && $divisa_origen_valida && strcmp($divisa_origen, "EUR") == 0) {echo(" selected");} ?>>EUR</option>
                        <option value="USD"<?php if (isset($divisa_origen) && $divisa_origen_valida && strcmp($divisa_origen, "USD") == 0) {echo(" selected");} ?>>USD</option>
                        <option value="GBP"<?php if (isset($divisa_origen) && $divisa_origen_valida && strcmp($divisa_origen, "GBP") == 0) {echo(" selected");} ?>>GBP</option>
                        <option value="CNY"<?php if (isset($divisa_origen) && $divisa_origen_valida && strcmp($divisa_origen, "CNY") == 0) {echo(" selected");} ?>>CNY</option>
                    </select>
                </div>
                
                <div>
                    <label for="divisa_destino"<?php if(isset($divisa_destino) && !$divisa_destino_valida) {echo('class="no-valido"');} ?>>Divisa destino</label>
                    <select id="divisa_destino" name="divisa_destino">
                        <option value="EUR"<?php if (isset($divisa_destino) && $divisa_destino_valida && strcmp($divisa_destino, "EUR") == 0) {echo(" selected");} ?>>EUR</option>
                        <option value="USD"<?php if (isset($divisa_destino) && $divisa_destino_valida && strcmp($divisa_destino, "USD") == 0) {echo(" selected");} ?>>USD</option>
                        <option value="GBP"<?php if (isset($divisa_destino) && $divisa_destino_valida && strcmp($divisa_destino, "GBP") == 0) {echo(" selected");} ?>>GBP</option>
                        <option value="CNY"<?php if (isset($divisa_destino) && $divisa_destino_valida && strcmp($divisa_destino, "CNY") == 0) {echo(" selected");} ?>>CNY</option>
                    </select>
                </div>
                
                <div>
                    <button type="submit" value="ok">Enviar</button>
                </div>
            </form>
<?php if (isset($datos_correctos) && $datos_correctos): ?>
            <div class="card">
                <h2>
                    <?php echo("$cantidad ($divisa_origen) = $cantidad_convertida ($divisa_destino)\n"); ?>
                </h2>
            </div>
<?php endif; ?>
            
<?php if (isset($datos_correctos) && !$datos_correctos): ?>
            <div class="card no-valido">
<?php if (!$cantidad_valida) {
    echo("                <p>Cantidad no válida.</p>\n");
} ?>
<?php if (!$divisa_origen_valida) {
    echo("                <p>Divisa origen no válida.</p>\n");
} ?>
<?php if (!$divisa_destino_valida) {
    echo("                <p>Divisa destino no válida.</p>\n");
} ?>
            </div>
<?php endif; ?>
        </main>
    </body>
</html>
