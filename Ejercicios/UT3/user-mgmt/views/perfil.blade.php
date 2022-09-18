@extends("master")
@section("content")
<div class="card">
@foreach ($cuadros as $cuadro)
<form name="cuadro-{{$cuadro->getId()}}" action="" method="POST">
    <h2>{{$cuadro->getTitle()}}</h2>

    <div>
        <button name="tipo_formulario" type="submit" value="infoCuadro-{{$cuadro->getId()}}">Más información</button>
    </div>
</form>
@endforeach
</div>
<form class="card" name="opcionesperfil" action="" method="POST">
    <h2>Opciones perfil</h2>
    <div>
        <button name="tipo_formulario" type="submit" value="menu_inicial">Cerrar sesión</button>
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="modificar_usuario_form">Modificar datos</button>
    </div>

    <div>
        <button name="tipo_formulario" type="submit" value="baja_usuario">Dar de baja</button>
    </div>
</form>
@endsection