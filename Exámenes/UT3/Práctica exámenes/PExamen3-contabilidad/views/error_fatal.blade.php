@extends("master")
@section("content")
<form class="card error" name="errorfatal" action="" method="POST">
    <h2>{{$mensaje_error}}</h2>
    
    <div>
        <button name="tipo_formulario" type="submit" value="menu_inicial">Volver a intentar</button>
    </div>
</form>
@endsection