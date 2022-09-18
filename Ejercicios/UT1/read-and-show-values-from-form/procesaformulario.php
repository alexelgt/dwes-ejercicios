<!DOCTYPE html>
<html>
    <head>
        <title>Procesa formulario</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body>
        <div class="flex-page">
            <h1>Datos recibidos</h1>
            <div class="form-font capaform">
                <p class="form-section">
                    <?php
                        $nombre = $_POST["nombre"];
                        echo "Nombre: $nombre\n";
                    ?>
                </p>
                <p class="form-section">
                    <?php
                        $contrasenia = $_POST["contrasenia"];
                        echo "Contraseña: $contrasenia\n";
                    ?>
                </p>
                <p class="form-section">
                    <?php
                        $email = $_POST["email"];
                        echo "Email: $email\n";
                    ?>
                </p>
                <p class="form-section">
                    <?php
                        $fecha = $_POST["fecha"];
                        echo "Fecha de Nacimiento: $fecha\n";
                    ?>
                </p>
                <p class="form-section">
                    <?php
                        $tel = $_POST["tel"];
                        echo "Teléfono Móvil: $tel\n";
                    ?>
                </p>
                <p class="form-section">
                    <?php
                        $poblacion = $_POST["poblacion"];
                        echo "Tienda: $poblacion\n";
                    ?>
                </p>
                <p class="form-section">
                    <?php
                        if (isset($_POST["edad"])) {
                            $edad = $_POST["edad"];
                        }
                        else {
                            $edad = null;
                        }

                        $edad_texto = null;

                        if (strcmp($edad, "-25") == 0) {
                            $edad_texto = "Menor de 25";
                        }
                        elseif (strcmp($edad, "25-50") == 0) {
                            $edad_texto = "Entre 25 y 50";
                        }
                        elseif (strcmp($edad, "50-") == 0) {
                            $edad_texto = "Mayor de 50";
                        }

                        echo "Edad: $edad_texto\n";
                    ?>
                </p>
                <p class="form-section">
                    <?php
                        if (isset($_POST["suscripcion"])) {
                            $suscripcion = true;
                        }
                        else {
                            $suscripcion = false;
                        }

                        $suscripcion_texto = $suscripcion ? "Sí" : "No";

                        echo "Suscripción a la revista semanal: $suscripcion_texto\n";
                    ?>
                </p>
            </div>
        </div>
    </body>
</html>