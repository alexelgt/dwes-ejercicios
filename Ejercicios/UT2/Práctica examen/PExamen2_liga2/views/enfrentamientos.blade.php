@extends("master")
@section("content")
<form class="card" name="fenfrentamientos" action="/" method="POST">
@foreach ($equipos as $equipo1)
@foreach ($equipos as $equipo2)
@if ($equipo1 !== $equipo2)
    <div>
        <label>{{$equipo1}} - {{$equipo2}}</label>
        <input type="number" name="enfrentamientos[{{$equipo1}}][{{$equipo2}}][0]" value="{{mt_rand(0,7)}}">
        <input type="number" name="enfrentamientos[{{$equipo1}}][{{$equipo2}}][1]" value="{{mt_rand(0,7)}}">
    </div>
@endif
@endforeach
@endforeach
    
    <div>
        <button name="tipo_formulario" type="submit" value="enfrentamientos">Enviar</button>
    </div>
</form>
@endsection