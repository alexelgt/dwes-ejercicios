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
            <form class="card" name="adivinaelnumero_datos" action="index.php" method="POST">
                <div>
@if (!isset($data["numero_intentos"]) || (isset($data["numero_intentos"]) && $data["numero_intentos_valido"]))
                    <label for="intentos">Número intentos</label>
@else
                    <label class="no-valido" for="intentos">Número intentos</label>
@endif
@if (isset($data["numero_intentos"]) && $data["numero_intentos_valido"])
                    <input id="intentos" type="text" name="intentos" placeholder="Introduce el número de intentos" value="{{$data['numero_intentos']}}">
@else
                    <input id="intentos" type="text" name="intentos" placeholder="Introduce el número de intentos">
@endif
                </div>
                
                <div>
@if (!isset($data["limite_inferior"]) || (isset($data["limite_inferior"]) && $data["limite_inferior_valido"]))
                    <label for="minimo">Límite inferior</label>
@else
                    <label class="no-valido" for="minimo">Límite inferior</label>
@endif
@if (isset($data["limite_inferior"]) && $data["limite_inferior_valido"])
                    <input id="minimo" type="text" name="minimo" placeholder="Introduce el límite inferior" value="{{$data['limite_inferior']}}">
@else
                    <input id="minimo" type="text" name="minimo" placeholder="Introduce el límite inferior">
@endif
                </div>
                
                <div>
@if (!isset($data["limite_superior"]) || (isset($data["limite_superior"]) && $data["limite_superior_valido"]))
                    <label for="minimo">Límite superior</label>
@else
                    <label class="no-valido" for="maximo">Límite superior</label>
@endif
@if (isset($data["limite_superior"]) && $data["limite_superior_valido"])
                    <input id="maximo" type="text" name="maximo" placeholder="Introduce el límite superior" value="{{$data['limite_superior']}}">
@else
                    <input id="maximo" type="text" name="maximo" placeholder="Introduce el límite superior">
@endif
                </div>
                
                <div>
                    <button type="submit">Enviar</button>
                </div>
            </form>
        </main>
    </body>
</html>