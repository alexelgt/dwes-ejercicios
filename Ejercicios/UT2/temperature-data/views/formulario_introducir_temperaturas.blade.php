@extends("master")
@section("content")
            <form class="card" name="introducir_temperaturas" action="index.php" method="POST">
@for ($index_ciudad = 0; $index_ciudad < count($ciudades); $index_ciudad++)
    @include("includes.tabla_introducir_datos", ["nombre_ciudad" => $ciudades[$index_ciudad], "index_ciudad" => $index_ciudad])
@endfor

                
                <div class="one-row">
                    <button name="boton" type="submit" value="introducir_temperaturas">Enviar</button>
                </div>
            </form>
@endsection