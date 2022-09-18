@extends("master")
@section("content")
<form class="card" name="ciudades" action="/" method="POST">
    <div>
        <label for="ciudades">Ciudades</label>
        <textarea id="ciudades" name="ciudades" rows="5" cols="10"></textarea>
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="ciudades">Enviar</button>
    </div>
</form>
@endsection