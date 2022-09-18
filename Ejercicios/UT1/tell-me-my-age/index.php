<?php
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }
        
        return true;
    }

    function dividir_fecha($fechaString) {
        // Espera que el formato de la fecha sea dd/mm/yyyy
        $fechaArray = explode("/", $fechaString);
        return array_map("intval", $fechaArray);
    }
    
    function es_bisiesto($year) {
        if (($year % 4 == 0) && !(($year % 100 == 0) && !($year % 400 == 0))) {
            return true;
        }
        else {
            return false;
        }
    }
    
    function calcular_edad_sin_php($day, $month, $year) {
        // Fecha actual
        $fecha_actual_array = dividir_fecha(date("d/m/Y")); // date es necesario para obtener la fecha actual
        
        [$current_day, $current_month, $current_year] = dividir_fecha(date("d/m/Y"));

        // Calcular edad
        $edad = $current_year - $year;

        if ($month - $current_month > 0 || ($month - $current_month == 0 && $day - $current_day > 0)) {
            $edad--;
        }

        return $edad >= 0 ? $edad : false;
    }
    
    function calcular_edad_con_php($day, $month, $year) {
        $fecha_nacimiento = date_create("$year-$month-$day");
        $dia_actual = date_create();
        
        $diferencia_fechas = date_diff($fecha_nacimiento, $dia_actual);
        
        if ($diferencia_fechas->invert) {
            return false;
        }
        return $diferencia_fechas->format("%y");
    }

    if (!empty($_POST)) {
        $fecha = filter_input(INPUT_POST, "fecha");
        $fecha_valida = valid_input($fecha) && preg_match("/^(0[1-9]|[12]\d|3[01])\/(0[1-9]|1[012])\/\d{1,4}$/", $fecha);
        
        if ($fecha_valida) {
            // Fecha introducida
            $fecha_array = dividir_fecha($fecha);
            
            [$day, $month, $year] = dividir_fecha($fecha);
            
            // Paso extra por si se introduce 29 de febrero ver que el año sea bisiesto
            if ($day == 29 && $month == 2) {
                $fecha_valida = es_bisiesto($year);
            }
        }
        
        if ($fecha_valida) {
            $usar_php = valid_input(filter_input(INPUT_POST, "usar_php")); // Si devuelve false es que la opción no se ha marcado

            if ($usar_php) {
                $edad = calcular_edad_con_php($day, $month, $year);
                
                $texto_info_php = "Se han usado funciones de la librería date";
            }
            else {
                $edad = calcular_edad_sin_php($day, $month, $year);
                
                $texto_info_php = "NO se han usado funciones de la librería date";
            }

            if ($edad === false) {
                $texto_output = "¿Vienes del futuro?";
            }
            else {
                $texto_output = "Tienes $edad años";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Dime mi edad</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body class="flex-page">
        <h1>Dime mi edad</h1>
        <form class="form-font capaform" name="Formdimemiedad" 
              action="index.php" method="POST">
            <div class="flex-outer">
                <div class="form-section">
                    <label for="fecha"<?php if (isset($fecha_valida) && !$fecha_valida) {echo(' style="color:red"');}?>>Fecha de nacimiento:</Label> 
                    <input id="fecha" type="text" name="fecha" placeholder="Introduce tu fecha de nacimiento" value="<?php if (isset($fecha) && $fecha_valida) {echo($fecha);} ?>"/>
                </div>
                <div class="form-section">
                    <label for="usar_php">Usar funciones PHP</label>
                    <input id="usar_php" type="checkbox" name="usar_php"<?php if (isset($usar_php) && $usar_php) {echo(" checked");} ?>/> 
                </div>
                <div class="form-section">
                    <div class="submit-section">
                        <input class="submit" type="submit" 
                               value="Enviar" name="botonenvio" /> 
                    </div>
                </div>
            </div>
<?php if (isset($fecha_valida) && !$fecha_valida): ?>
            <div class="form-font capaform" style="border-color: red">
                <p class="form-section">
                    Fecha no válida. Formato correcto: dd/mm/yyyy
                </p>
            </div>
<?php endif ?>
        </form>  
<?php if (isset($fecha_valida) && $fecha_valida): ?>  
        <div class="form-font capaform" style="margin-top: 20px">
            <p class="form-section">
                <?php
                    echo("$texto_output\n");
                ?>
            </p>
            <p class="form-section">
                <?php
                    echo("<i>$texto_info_php</i>\n");
                ?>
            </p>
        </div>
<?php endif ?>
    </body>
</html>

