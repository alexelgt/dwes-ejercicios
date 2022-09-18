@extends("master")
@section("content")
            <form class="card flex-col" name="jornadas" action="index.php" method="POST">
                <input id="equipos" type="text" name="equipos" placeholder="" value="{{implode(',', $equipos)}}" hidden>
@for ($num_jornada = 0; $num_jornada < count($partidos); $num_jornada++)
                @include("includes.jornada", ["equipos" => $equipos, "num_jornada" => $num_jornada, "partidos_jornada" => $partidos[$num_jornada]])
@endfor

                
                <div>
                    <button name="boton" type="submit" value="introducir_jornadas">Enviar</button>
                </div>
            </form>
@endsection