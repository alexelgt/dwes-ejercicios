<div class="marcador">
    <h3>{{$equipo1}} - {{$equipo2}}</h3>
    <div class="flex-container">
        <input type="number" name="info_liga[{{$num_jornada}}][{{$index_partido}}][{{$equipo1}}]" min="0" value="{{rand(0, 7)}}">
        <input type="number" name="info_liga[{{$num_jornada}}][{{$index_partido}}][{{$equipo2}}]" min="0" value="{{rand(0, 7)}}">
    </div>
</div>