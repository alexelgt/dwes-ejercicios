@extends("master")
@section("content")
<form class="card" name="registro" action="" method="POST">
    <div>
        <label for="nombre">Nombre</label>
@if ((isset($nombre_valido) && $nombre_valido))
        <input id="nombre" type="text" name="nombre" value="{{$nombre}}">
@else
        <input id="nombre" type="text" name="nombre">
@endif
    </div>
    
    <div>
        <label for="clave">Contraseña</label>
        <input id="clave" type="password" name="clave">
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="iniciar_sesion">Iniciar sesión</button>
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="menu_inicial">Volver menú inicial</button>
    </div>
</form>
@if (isset($mensaje))
<div class="card error">
    <h2>{{$mensaje}}</h2>
</div>
@endif
@if ((isset($nombre_valido) && !$nombre_valido) || (isset($clave_valido) && !$clave_valido))
<div class="card error">
@if (!$nombre_valido)
    <h2>Has introducido un nombre incorrecto</h2>
@endif

@if (!$clave_valido)
    <h2>Has introducido una contraseña incorrecta</h2>
@endif
</div>
@endif
@endsection