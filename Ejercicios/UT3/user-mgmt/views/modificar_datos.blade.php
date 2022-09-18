@extends("master")
@section("content")
<form class="card" name="modificardatos" action="" method="POST">
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
        <label for="mail">Email</label>
@if ((isset($mail_valido) && $mail_valido))
        <input id="mail" type="email" name="mail" value="{{$mail}}">
@else
        <input id="mail" type="email" name="mail">
@endif
    </div>
    
    <div>
        <label for="pintor">Pintor favorito</label>
        <select id="pintor" name="pintor">
@foreach ($pintores as $pintor)
@if ((isset($pintor_favorito_valido) && $pintor_favorito_valido) && $pintor_favorito == $pintor->getId())
            <option value="{{$pintor->getId()}}" selected>{{$pintor->getName()}}</option>
@else
            <option value="{{$pintor->getId()}}">{{$pintor->getName()}}</option>
@endif
@endforeach
        </select>
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="modificar_usuario">Modificar datos</button>
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="volver_perfil">Cancelar</button>
    </div>
</form>
@if (isset($mensaje))
<div class="card error">
    <h2>{{$mensaje}}</h2>
</div>
@endif
@if ((isset($nombre_valido) && !$nombre_valido) || (isset($clave_valido) && !$clave_valido) || (isset($mail_valido) && !$mail_valido) || (isset($pintor_favorito_valido) && !$pintor_favorito_valido))
<div class="card error">
@if (!$nombre_valido)
    <h2>Has introducido un nombre incorrecto</h2>
@endif
@if (!$clave_valido)
    <h2>Has introducido una contraseña incorrecta</h2>
@endif
@if (!$mail_valido)
    <h2>Has introducido un email incorrecto</h2>
@endif
@if (!$pintor_favorito_valido)
    <h2>Has introducido un pintor favorito incorrecto</h2>
@endif
</div>
@endif
@endsection