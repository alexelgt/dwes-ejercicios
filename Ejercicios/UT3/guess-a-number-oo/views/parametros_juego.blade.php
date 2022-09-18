@extends("master")
@section("content")
            <form class="card" name="adivinaelnumero_datos" action="index.php" method="POST">
                <div>
@if (!isset($numero_intentos) || (isset($numero_intentos) && $numero_intentos_valido))
                    <label for="intentos">Número intentos</label>
@else
                    <label class="no-valido" for="intentos">Número intentos</label>
@endif
@if (isset($numero_intentos) && $numero_intentos_valido)
                    <input id="intentos" type="text" name="intentos" placeholder="Introduce el número de intentos" value="{{$numero_intentos}}">
@else
                    <input id="intentos" type="text" name="intentos" placeholder="Introduce el número de intentos">
@endif
                </div>
                
                <div>
@if (!isset($limite_inferior) || (isset($limite_inferior) && $limite_inferior_valido))
                    <label for="minimo">Límite inferior</label>
@else
                    <label class="no-valido" for="minimo">Límite inferior</label>
@endif
@if (isset($limite_inferior) && $limite_inferior_valido)
                    <input id="minimo" type="text" name="minimo" placeholder="Introduce el límite inferior" value="{{$limite_inferior}}">
@else
                    <input id="minimo" type="text" name="minimo" placeholder="Introduce el límite inferior">
@endif
                </div>
                
                <div>
@if (!isset($limite_superior) || (isset($limite_superior) && $limite_superior_valido))
                    <label for="minimo">Límite superior</label>
@else
                    <label class="no-valido" for="maximo">Límite superior</label>
@endif
@if (isset($limite_superior) && $limite_superior_valido)
                    <input id="maximo" type="text" name="maximo" placeholder="Introduce el límite superior" value="{{$limite_superior}}">
@else
                    <input id="maximo" type="text" name="maximo" placeholder="Introduce el límite superior">
@endif
                </div>
                
                <div>
                    <button name="tipo_formulario" type="submit" value="parametros">Enviar</button>
                </div>
            </form>
@endsection