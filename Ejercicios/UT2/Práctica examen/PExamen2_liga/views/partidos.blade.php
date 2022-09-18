@extends("master")
@section("content")
<form class="card" name="partidos" action="/" method="POST">
    @foreach ($equipos as $equipo)
    <div>
        @include("includes.enfrentamientos", ["equipo1" => $equipo, "equipos" => $equipos])
    </div>
    @endforeach
    
    <div>
        <button name="botoncito" type="submit" value="partidos">Enviar</button>
    </div>
</form>
@endsection