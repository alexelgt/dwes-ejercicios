<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>Conecta {{$casillas_seguidas}}</title>
        <link rel="stylesheet" href="style.css" />
        <script src="jquery-3.6.0.min.js"></script>
        <script src="comprobarCasilla.js"></script>

    </head>
    <body>
        <header>
            <h1>Conecta {{$casillas_seguidas}}</h1>
        </header>

        <main>
            @yield("content")
            
            <h2 id="mensaje"></h2>
            
            <form name="reiniciarpartida" action="/" method="POST">
                <button name="reiniciar_partida" type="submit" value="ok">Reiniciar partida</button>
            </form>
        </main>
    </body>
</html>