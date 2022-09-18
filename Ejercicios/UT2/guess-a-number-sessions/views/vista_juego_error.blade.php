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
            @include("includes.formulario_juego")
            
            <div class="card">
                <p class="no-valido">El número introducido es incorrecto</p>
            </div>
        </main>
    </body>
</html>