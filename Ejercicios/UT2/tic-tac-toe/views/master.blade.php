<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>3 en raya</title>
        <link rel="stylesheet" href="style.css" />
        <script src="jquery-3.6.0.min.js"></script>
        <script src="public/assets/js/move.js"></script>
    </head>
    <body>
        <header>
            <h1>3 en raya</h1>
        </header>
        
        <main>
            @yield("content")
            
            <h2 id="message"></h2>
            
            <form name="" action="/" method="POST">
                <button type="submit" name="botoncito" value="ok">Reiniciar partida</button>
            </form>
        </main>
    </body>
</html>