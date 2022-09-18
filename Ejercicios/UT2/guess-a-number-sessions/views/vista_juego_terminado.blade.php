<!DOCTYPE html>
<html lang="es">
    <head>
        @include("includes.head")
    </head>
    <body>
        <header>
            <h1>{{$data["titulo"]}}</h1>
        </header>
        
        <main>
            <form class="card" name="adivinaelnumero" action="guessanumber.php" method="POST">
                <p>{{$data["mensaje_fin"]}}</p>

                <div>
                    <button type="submit" value="reset"  name="boton">Volver a empezar</button>
                </div>
            </form>
        </main>
    </body>
</html>