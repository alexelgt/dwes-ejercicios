@extends("master")
@section("content")
<form class="card" name="eleccionformulario" action="" method="POST">
@if (isset($mensaje))
    <h2>{{$mensaje}}</h2>
@endif
    <div>
        <button name="tipo_formulario" type="submit" value="eleccion_inicio_session">Iniciar sesión</button>
    </div>

    <div>
        <button name="tipo_formulario" type="submit" value="eleccion_registro">Registrarse</button>
    </div>
</form>
@endsection