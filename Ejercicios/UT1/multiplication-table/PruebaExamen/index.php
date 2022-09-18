<?php
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }

        return true;
    }
    
    function tabla_multiplicar($numero) {
        $texto = "<table><thead><tr><th>Tabla del $numero</th></tr></thead><tbody>";
        
        for ($i = 1; $i <= 10; $i++) {
            $texto .= "<tr><td>$numero x $i = " . ($numero * $i) . "</td></tr>";
        }
        
        $texto .= "</tbody></table>";
        
        return $texto;
    }

    if (!empty($_POST)) {
        $cadena = filter_input(INPUT_POST, "cadena");
        
        $cadena_valida = valid_input($cadena) & preg_match("/^([1-9](\-[1-9])?,)*[1-9](\-[1-9])?$/", $cadena);
        
        $numeros = [];
        
        if ($cadena_valida) {
            foreach (explode(",", $cadena) as $cadena_elemento) {
                if (preg_match("/[1-9]\-[1-9]/", $cadena_elemento)) {
                    [$rang_min, $rang_max] = explode("-", $cadena_elemento);
                    
                    $numeros = array_merge($numeros, range($rang_min, $rang_max));
                    
                    $numeros = array_unique($numeros);
                }
                else {
                    if (!in_array($cadena_elemento, $numeros)) {
                        array_push($numeros, $cadena_elemento);
                    }
                }
            }
            sort($numeros);
            
            $texto_tablas = "";
            
            foreach ($numeros as $numero) {
                $texto_tablas .= tabla_multiplicar($numero);
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tablas de multiplicar</title>
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <header>
            <h1>Tablas de multiplicar</h1>
        </header>
        <main>  
            <form class="card" name="tablasmultiplicacion" action="index.php" method="POST">
                <div>
                    <label for="cadena">Cadena de números</label>
                    <input id="cadena" type="text" name="cadena" placeholder="Introduce una cadena" value="<?php if(isset($cadena) && $cadena_valida) {echo($cadena);} ?>">
                </div>

                <div>
                    <button type="submit">Enviar</button>
                </div>
            </form>
<?php if (isset($cadena_valida) && !$cadena_valida): ?>
            <div class="card">
                <p>
                    Cadena de números no válida.
                </p>
            </div>
<?php endif; ?>
        
<?php if (isset($cadena_valida) && $cadena_valida): ?>
            <div class="card mostrar-tablas">
                <?php
                    echo($texto_tablas);
                ?>
            </div>
<?php endif; ?>
        </main>
    </body>
</html>
