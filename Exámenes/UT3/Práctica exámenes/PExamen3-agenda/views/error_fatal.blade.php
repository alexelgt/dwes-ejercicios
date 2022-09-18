@extends("master")
@section("content")
<form class="card" name="errorfatal" action="" method="POST">
    <div class="error">
        <h2>{{$mensaje_error}}</h2>
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="menu_inicial">Volver al intentar</button>
    </div>
</form>
@endsection