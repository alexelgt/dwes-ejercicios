<?php
    if (empty($_POST)) {
        header("Location: /index.php");
    }
    
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }
        
        return true;
    }

    // NOMBRE
    $nombre = filter_input(INPUT_POST, "nombre");
    $nombre_valido = valid_input($nombre) && preg_match("/[A-Z]/", $nombre) && preg_match("/[a-z]/", $nombre) && preg_match("/ /", $nombre) && (strlen($nombre) >= 3 && strlen($nombre) <= 25);
    
    // CONTRASEÑA
    $contrasenia = filter_input(INPUT_POST, "contrasenia");
    $contrasenia_valida = valid_input($contrasenia) && preg_match("/[a-z]/i", $contrasenia) && preg_match("/[0-9]/", $contrasenia) && (strlen($contrasenia) >= 6 && strlen($contrasenia) <= 8);
    
    // EMAIL
    $email = filter_input(INPUT_POST, "email");
    $email_valido = valid_input($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    
    // FECHA NACIMIENTO Y EDAD
    // Hay 2 campos que determinan la edad pero pueden no coincidir ambas respuestas.
    // Debido a esto se usa la fecha de nacimiento como campo principal y el de rango de edad como verificación.
    // Por lo que el comportamiento es el siguiente:
    // - Si no se introduce una fecha válida (nada o menor de edad), el rango de edad no se considera válido.
    // - Si se introduce la fecha pero no el rango de edad, se establece de forma automática el rango de edad.
    // - Si se introducen una fecha y rango de edad válidos, se verifica que ambas respuestas coinciden.
    
    function calcular_edad($fecha_nacimiento) {
        $current_year = intval(date("Y"));
        $current_month = intval(date("m"));
        $current_day = intval(date("d"));

        $year = intval(substr($fecha_nacimiento, 0, 4));
        $month = intval(substr($fecha_nacimiento, 5, 2));
        $day = intval(substr($fecha_nacimiento, 8, 2));

        $edad = $current_year - $year;

        if ($month - $current_month >= 0 && $day - $current_day > 0) {
            $edad--;
        }

        return $edad;
    }

    $fecha = filter_input(INPUT_POST, "fecha");
                
    $edad_rango = false;
    $edad_texto = null;
    
    // Fecha
    $fecha_valida = false;
    $mayor_edad = false;
    if (valid_input($fecha) && preg_match("/^\d{1,4}\-(0[1-9]|1[012])\-(0[1-9]|[12]\d|3[01])$/", $fecha)) {
        $fecha_valida = true;

        $edad_necesaria = 18;
        $edad_valor = calcular_edad($fecha);

        $mayor_edad = $edad_valor >= $edad_necesaria;
        
        if ($mayor_edad) {
            if ($edad_valor < 25) {
                $edad_rango = "-25";
                $edad_texto = "Menor de 25";
            }
            elseif ($edad_valor >= 25 && $edad_valor <= 60) {
                $edad_rango = "25-50";
                $edad_texto = "Entre 25 y 50";
            }
            elseif ($edad_valor > 50) {
                $edad_rango = "50-";
                $edad_texto = "Mayor de 50";
            }
        }
    }
    
    // Edad
    $edad = filter_input(INPUT_POST, "edad");
    
    $edad_valida = false;
    $rango_edad_auto = false;

    if (!valid_input($edad)) {
        if ($edad_rango != null) {
            $edad_valida = true;
            $rango_edad_auto = true;
        }
    }
    else {
        if ($fecha_valida) {
            if (strcmp($edad, $edad_rango) == 0) {
                $edad_valida = true;
            }
        }
    }
    
    // TELÉFONO MÓVIL
    $tel = filter_input(INPUT_POST, "tel");
    $tel_valido = valid_input($tel) && preg_match("/^(\+\d{1,3})?( )?\d{9}$/", $tel);
    
    // TIENDA
    $poblacion = filter_input(INPUT_POST, "poblacion");
    
    $poblacion_valida = valid_input($poblacion);
    
    // SUSCRIPCIÓN
    $suscripcion = filter_input(INPUT_POST, "suscripcion");
    
    if (valid_input($suscripcion)) {
        $suscripcion = true;
        $suscripcion_texto = "Sí";
    }
    else {
        $suscripcion = false;
        $suscripcion_texto = "No";
    }

    // DATOS CORRECTOS
    $datos_correctos = $nombre_valido && $contrasenia_valida && $email_valido && $fecha_valida && $mayor_edad && $edad_valida && $tel_valido && $poblacion_valida;
    
    if ($datos_correctos) {
        $titulo = "Procesa formulario";
    }
    else {
        $titulo = "Formulario de Registro";
   }
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo($titulo);?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body class="flex-page">
<?php if (!$datos_correctos): ?>
        <h1>Registro de Cliente</h1>
        <form class="form-font capaform" name="Formregistro" 
              action="procesaformulario.php" method="POST">
            <div class="flex-outer">
                <div class="form-section">
                    <label for="nombre"<?php if (isset($nombre_valido) && !$nombre_valido) {echo(' style="color:red"');} ?>>Nombre de Usuario:</Label> 
                    <input id="nombre" type="text" name="nombre" placeholder="Escribe tu nombre" value="<?php if (valid_input($nombre) && $nombre_valido) {echo($nombre);} ?>"/> 
                </div>
                <div class="form-section">
                    <label for="contrasenia"<?php if (isset($contrasenia_valida) && !$contrasenia_valida) {echo(' style="color:red"');} ?>>Contraseña:</Label> 
                    <input id="contrasenia" type="password" name="contrasenia" placeholder="Escribe tu contraseña"/> 
                </div>
                <div class="form-section">
                    <label for="email"<?php if (isset($email_valido) && !$email_valido) {echo(' style="color:red"');} ?>>Email:</Label> 
                    <input id="email" type="text"  name="email" placeholder="Escribe tu correo" value="<?php if (valid_input($email) && $email_valido) {echo($email);} ?>">
                </div>
                <div class="form-section">
                    <label for="fechanac"<?php if (isset($fecha_valida) && (!$fecha_valida || ($fecha_valida && !$mayor_edad))) {echo(' style="color:red"');} ?>>Fecha de Nacimiento:</Label> 
                    <input id="fechanac" type="date" name="fecha" placeholder="Escribe tu fecha de nacimiento" value="<?php if (valid_input($fecha) && $mayor_edad) {echo($fecha);} ?>">
                </div>
                <div class="form-section">
                    <label for="telefono"<?php if (isset($tel_valido) && !$tel_valido) {echo(' style="color:red"');} ?>>Telefono Móvil:</Label> 
                    <input id="telefono" type="tel" name="tel" placeholder="Escribe tu telefono" value="<?php if (valid_input($tel) && $tel_valido) {echo($tel);} ?>">
                </div>
                <div class="form-section">
                    <label for="nomtienda"<?php if (isset($poblacion_valida) && !$poblacion_valida) {echo(' style="color:red"');} ?>>Tienda:</Label> 
                    <select id="nomtienda" name="poblacion">
                        <option value="Madrid"<?php if (strcmp($poblacion, "Madrid") == 0) {echo(" selected");} ?>>Madrid</option>
                        <option value="Barcelona"<?php if (strcmp($poblacion, "Barcelona") == 0) {echo(" selected");} ?>>Barcelona</option>
                        <option value="Valencia"<?php if (strcmp($poblacion, "Valencia") == 0) {echo(" selected");} ?>>Valencia</option>
                    </select> 
                </div>
                <div class="form-section">
                    <div class="form-inner">
                        <label<?php if (isset($edad_valida) && !$edad_valida) {echo(' style="color:red"');} ?>>Edad:</label>
                        <div class="select-section">
                            <input id="-25" type="radio" name="edad" value="-25"<?php if (valid_input($edad_rango) && $edad_valida && strcmp($edad_rango, "-25") == 0) {echo(" checked");} ?> /> 
                            <label for="-25">Menor de 25</label>
                            <input id="25-50" type="radio" name="edad" value="25-50"<?php if (valid_input($edad_rango) && $edad_valida && strcmp($edad_rango, "25-50") == 0) {echo(" checked");} ?> /> 
                            <label for="25-50">Entre 25 y 50</label>
                            <input id="50-" type="radio" name="edad" value="50-"<?php if (valid_input($edad_rango) && $edad_valida && strcmp($edad_rango, "50-") == 0) {echo(" checked");} ?> />
                            <label for="50-">Mayor de 50</label>
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <label for="suscripcion">Suscripción a la revista semanal</label>
                    <input id="suscripcion" type="checkbox"  name="suscripcion"<?php if (valid_input($suscripcion) && $suscripcion) {echo(" checked");} ?>/> 
                </div>
                <div class="form-section">
                    <div class="submit-section">
                        <input class="submit" type="submit" 
                               value="Enviar" name="botonenvio" /> 
                    </div>
                </div>
            </div>
<?php if ($rango_edad_auto): ?>
            
            <div class="form-font capaform">
                <p class="form-section">
                    Se ha establecido el rango de edad automáticamente
                </p>
            </div>
            
<?php endif; ?>
            <div class="form-font capaform" style="border-color: red">
<?php if (!$nombre_valido): ?>
                <p class="form-section">
                    Nombre no válido
                </p>
<?php endif; ?>
<?php if (!$contrasenia_valida): ?>
                <p class="form-section">
                    Contraseña no válida
                </p>
<?php endif; ?>
<?php if (!$email_valido): ?>
                <p class="form-section">
                    Email no válido
                </p>
<?php endif; ?>
<?php if (!$fecha_valida): ?>
                <p class="form-section">
                    Fecha de Nacimiento no válida
                </p>
<?php endif; ?>
<?php if ($fecha_valida && !$mayor_edad): ?>
                <p class="form-section">
                    No eres mayor de edad
                </p>
<?php endif; ?>
<?php if (!$tel_valido): ?>
                <p class="form-section">
                    Teléfono no válido
                </p>
<?php endif; ?>
<?php if (!$poblacion_valida): ?>
                <p class="form-section">
                    Tienda no válida
                </p>
<?php endif; ?>
<?php if (!$edad_valida): ?>
                <p class="form-section">
                    Edad no válida
                </p>
<?php endif; ?>
            </div>
        </form>
<?php else: ?>
        <h1>Datos recibidos</h1>
        <div class="form-font capaform">
            <p class="form-section">
                <?php
                    echo "Nombre: $nombre\n";
                ?>
            </p>
            <p class="form-section">
                <?php
                    echo "Contraseña: $contrasenia\n";
                ?>
            </p>
            <p class="form-section">
                <?php
                    echo "Email: $email\n";
                ?>
            </p>
            <p class="form-section">
                <?php
                    echo "Fecha de Nacimiento: $fecha\n";
                ?>
            </p>
            <p class="form-section">
                <?php
                    echo "Teléfono Móvil: $tel\n";
                ?>
            </p>
            <p class="form-section">
                <?php
                    echo "Tienda: $poblacion\n";
                ?>
            </p>
            <p class="form-section">
                <?php
                    echo "Edad: $edad_texto\n";
                ?>
            </p>
            <p class="form-section">
                <?php
                    echo "Suscripción a la revista semanal: $suscripcion_texto\n";
                ?>
            </p>
        </div>
<?php endif; ?>
    </body>
</html>