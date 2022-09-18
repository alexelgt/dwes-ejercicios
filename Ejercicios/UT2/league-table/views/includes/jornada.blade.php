<section>
    <h2>Jornada {{$num_jornada + 1}}</h2>
    
    <div class="flex-container flex-space">
@for ($i = 0; $i < count($partidos_jornada); $i++)
        @include("includes.marcador", ["num_jornada" => $num_jornada, "equipo1" => $equipos[$partidos_jornada[$i][0]], "equipo2" => $equipos[$partidos_jornada[$i][1]], "index_partido" => $i])
@endfor
    </div>
</section>