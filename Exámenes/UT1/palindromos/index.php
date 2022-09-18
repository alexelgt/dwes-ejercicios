<?php
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }

        return true;
    }

    function es_palindromo($palabra) {
        // Como no se distingue entre mayúsculas y minúsculas paso la palabra a mayúsculas
        $palabra_mayus = strtoupper($palabra);

        // Si la palabra es igual a sí misma invertida es que es un palíndromo
        return $palabra_mayus === strrev($palabra_mayus);
    }

    function criterio_ordenacion($palabra1, $palabra2) {
        $diferencia_len = strlen($palabra2) - strlen($palabra1);

        if ($diferencia_len !== 0) {
            return $diferencia_len;
        }

        // Aunque no lo pide, si 2 palabras tienen la misma longitud uso strcmp para ordenar alfabéticamente
        return strcmp($palabra1, $palabra2);
    }

    if (!empty($_POST)) {
        $cadena = filter_input(INPUT_POST, "cadena");
        $cadena_valida = valid_input($cadena) && strlen($cadena) > 0;

        if ($cadena_valida) {
            $palabras = preg_split("/[ .,:;(\r\n)\t]+/", $cadena);

            $palabras_palindromo_filtrado = array_filter($palabras, "es_palindromo");

            if (count($palabras_palindromo_filtrado) === 0) {
                $texto_output = "No hay palíndromos.";
            }
            else {
                $palabras_palindromo_mayus = array_map("strtoupper", $palabras_palindromo_filtrado);

                $palabras_palindromo = array_unique($palabras_palindromo_mayus);

                usort($palabras_palindromo, "criterio_ordenacion");

                $texto_output = implode(" - ", $palabras_palindromo);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>Encuentra palíndromos</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <header>
            <h1>Encuentra palíndromos</h1>
        </header>
        
        <main>
            <form class="card" name="palindromos" action="index.php" method="POST">
                <div>
                    <label for="cadena"<?php if(isset($cadena) && !$cadena_valida) {echo(' class="no-valido"');} ?>>Cadena de palabras</label>
                    <textarea id="cadena" name="cadena" rows="5" cols="10"><?php if(isset($cadena) && $cadena_valida) {echo($cadena);} ?></textarea>
                </div>
                
                <div>
                    <button type="submit" value="ok">Enviar</button>
                </div>
            </form>
<?php if(isset($cadena) && $cadena_valida): ?>
            <div class="card">
                <h2><?php echo($texto_output); ?></h2>
            </div>
<?php endif; ?>
<?php if(isset($cadena) && !$cadena_valida): ?>
            <div class="card">
                <h2 class="no-valido">Cadena introducida no válida. Introduce algún carácter.</h2>
            </div>
<?php endif; ?>
        </main>
    </body>
</html>
