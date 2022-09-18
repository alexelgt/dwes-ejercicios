<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>{{$titulo}}</title>
        <link rel="stylesheet" href="style.css" />
        <script src="jquery-3.6.0.min.js"></script>
        <script src="public/js/actualizarDatos.js"></script>
    </head>
    <body>
        <header>
            <h1>{{$titulo}}</h1>
        </header>

        <main>
            @yield("content")
        </main>
    </body>
</html>