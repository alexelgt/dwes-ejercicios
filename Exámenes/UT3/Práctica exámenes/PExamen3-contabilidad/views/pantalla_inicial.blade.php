@extends("master")
@section("content")
<form class="card" name="pantallainicial" action="" method="POST">
    <div>
        <button name="tipo_formulario" type="submit" value="form_inicio_sesion">Iniciar sesión</button>
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="form_registro">Registro</button>
    </div>
</form>
@endsection