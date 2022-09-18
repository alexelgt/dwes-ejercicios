@extends("master")
@section("content")
            <form class="card flex-col" name="elegir_ciudades" action="index.php" method="POST">
                <div>
@if (!isset($ciudades_string) || (isset($ciudades_string) && $ciudades_string_valido))
                    <label for="ciudades">Ciudades</label>
@else
                    <label class="no-valido" for="ciudades">Ciudades</label>
@endif
@if (isset($ciudades_string) && $ciudades_string_valido)
                    <input id="ciudades" type="text" name="ciudades" placeholder="Introduce las ciudades" value="{{$ciudades_string}}">
@else
                    <input id="ciudades" type="text" name="ciudades" placeholder="Introduce las ciudades">
@endif
                </div>

                
                <div>
                    <button name="boton" type="submit" value="introducir_ciudades">Enviar</button>
                </div>
            </form>
@if (isset($ciudades_string) && !$ciudades_string_valido)
            <div class="card no-valido">
@if ($ciudades_string === "")
                <p>Cadena no válida. Introduce una palabra.</p>
@else
                <p>Cadena no válida. Has introducido demasiadas palabras.</p>
@endif
            </div>
@endif
@endsection