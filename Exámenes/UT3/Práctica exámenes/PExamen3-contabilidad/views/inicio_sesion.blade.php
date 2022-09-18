@extends("master")
@section("content")
<form class="card" name="iniciosesion" action="" method="POST">
    <div>
        <label for="nombre">Nombre</label>
        <input id="nombre" type="text" name="nombre" placeholder="" value="">
    </div>
    
    <div>
        <label for="clave">Contraseña</label>
        <input id="clave" type="password" name="clave" placeholder="" value="">
    </div>

    <div>
        <button name="tipo_formulario" type="submit" value="inicio_sesion">Iniciar sesión</button>
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="menu_inicial">Volver al menú inicial</button>
    </div>
</form>

@if (isset($mensajes_error))
<div class="card error">
@foreach ($mensajes_error as $mensaje_error)
    <h2>{{$mensaje_error}}</h2>
@endforeach
</div>
@endif
@endsection