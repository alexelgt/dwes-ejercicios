@extends("master")
@section("content")
<form class="card" name="formciudades" action="/" method="POST">
@foreach ($ciudades as $ciudad)
    @include("includes.datos_ciudad", ["ciudad" => $ciudad])
@endforeach
    
    <div>
        <button name="tipo_formulario" type="submit" value="temperaturas">Enviar</button>
    </div>
</form>
@endsection