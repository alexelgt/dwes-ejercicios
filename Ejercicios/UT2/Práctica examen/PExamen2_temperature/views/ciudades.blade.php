@extends("master")
@section("content")
<form class="card" name="formciudades" action="/" method="POST">
    <div>
        <label for="ciudades">Ciudades</label>
        <input id="ciudades" type="text" name="ciudades" placeholder="Introduce las ciudades">
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="ciudades">Enviar</button>
    </div>
</form>
@if (isset($ciudades_string_valido) && !$ciudades_string_valido)
<div class="card">
    <h2>Cadena no válida. Introduce al menos una ciudad o no introduzcas demasiadas.</h2>
</div>
@endif
@endsection