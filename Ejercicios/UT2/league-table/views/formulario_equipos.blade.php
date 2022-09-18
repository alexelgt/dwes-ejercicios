@extends("master")
@section("content")
            <form class="card flex-col" name="elegir_equipos" action="index.php" method="POST">
                <div>
@if (!isset($equipos_string) || (isset($equipos_string) && $equipos_string_valido))
                    <label for="equipos">Equipos</label>
@else
                    <label class="no-valido" for="equipos">Equipos</label>
@endif
@if (isset($equipos_string) && $equipos_string_valido)
                    <input id="equipos" type="text" name="equipos" placeholder="Introduce los equipos" value="{{$equipos_string}}">
@else
                    <input id="equipos" type="text" name="equipos" placeholder="Introduce los equipos" value="">
@endif
                </div>

                <div>
                    <h5>Sugerencia:</h5>
                    <p>Deportivo Alavés,Athletic Club,Atlético de Madrid,FC Barcelona,Real Betis,Cádiz,Celta de Vigo,Elche,Espanyol,Getafe,Granada,Levante,Real Mallorca,Osasuna,Rayo Vallecano,Real Madrid,Real Sociedad,Sevilla,Valencia,Villarreal</p>
                </div>
                <div>
                    <button name="boton" type="submit" value="introducir_equipos">Enviar</button>
                </div>
            </form>
@if (isset($equipos_string) && !$equipos_string_valido)
            <div class="card no-valido">
@if ($equipos_string === "")
                <p>Cadena no válida. Introduce una palabra.</p>
@else
                <p>Cadena no válida. Has introducido demasiadas palabras.</p>
@endif
            </div>
@endif
@endsection