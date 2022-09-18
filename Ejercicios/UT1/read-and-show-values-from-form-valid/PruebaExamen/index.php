<!-- Para esta prueba solo quería hacer desde 0 el formulario (con estilos) ya que es el más completo -->
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>Holis</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <header>
            <h1>Holis</h1>
        </header>
        
        <main>
            <form class="card" name="datos" action="nope.php" method="POST">
                <div>
                    <label for="nombre">Nombre de usuario:</label>
                    <input id="nombre" type="text" name="nombre" placeholder="Introduce tu nombre de usuario" value="">
                </div>
                
                <div>
                    <label for="pass">Contraseña</label>
                    <input id="pass" type="password" name="pass" placeholder="Introduce tu contraseña" value="">
                </div>
                
                <div>
                    <label for="email">Email:</label>
                    <input id="email" type="text" name="email" placeholder="Introduce tu email" value="">
                </div>
                
                <div>
                    <label for="fecha">Fecha de nacimiento:</label>
                    <input id="fecha" type="date" name="fecha" placeholder="Introduce tu fecha de nacimiento" value="">
                </div>
                
                <div>
                    <label for="tienda">Tienda:</label>
                    <select id="tienda" name="tienda">
                        <option value="first">text1</option>
                        <option value="second">text2</option>
                        <option value="third">text3</option>
                    </select>
                </div>
                
                <div>
                    <label>Edad:</label>
                    <div>
                        <input id="-25" type="radio" name="edad" value="-25">
                        <label for="-25">Menor de 25</label>
                    </div>
                    
                    <div>
                        <input id="25-" type="radio" name="edad" value="25-">
                        <label for="25-">Mayor de 25</label>
                    </div>
                </div>
                
                <div class="checkbox">
                    <label for="suscripcion">Dame tu vida</label>
                    <input id="suscripcion" type="checkbox" name="suscripcion">
                </div>
                
                <div>
                    <button type="submit">Enviar</button>
                </div>
            </form>
        </main>
    </body>
</html>
