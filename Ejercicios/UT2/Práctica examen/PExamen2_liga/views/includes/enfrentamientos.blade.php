@foreach ($equipos as $equipo2)
@if ($equipo1 !== $equipo2)
<div>
    <label>{{$equipo1}} - {{$equipo2}}</label>
    <input type="number" name="resultados[{{$equipo1}}][{{$equipo2}}][0]" value="{{mt_rand(0, 7)}}">
    <input type="number" name="resultados[{{$equipo1}}][{{$equipo2}}][1]" value="{{mt_rand(0, 7)}}">
</div>
@endif
@endforeach