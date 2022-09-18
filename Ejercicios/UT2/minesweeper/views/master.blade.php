<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>Busca minas</title>
        <link rel="stylesheet" href="style.css" />
        <script src="jquery-3.6.0.min.js"></script>
        <script src="move.js"></script>
    </head>
    <body>
        <header>
            <h1>Busca minas</h1>
        </header>
        
        <main>
            <form name="" action="/" method="POST">
                <div id="marcador">{{$num_minas}}</div>
                <button id="cara" type="submit" name="botoncito" value="ok"><img src="public/assets/img/good.png"></button>
                <div id="contador">0</div>
            </form>

            @yield("content")
        </main>
    </body>
</html>
