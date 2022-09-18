@extends("master")
@section("content")
<form class="card" name="fequipos" action="/" method="POST">
    <div>
        <label for="equipos">Equipo</label>
        <input id="equipos" type="text" name="equipos" placeholder="Introduce los equipos">
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="equipos">Enviar</button>
    </div>
</form>

@if (isset($equipos_string_valido) && !$equipos_string_valido)
<div class="card">
    <h2 class="no-valido">Equipos no válido. Introduce algún equipo o no introduzcas demasiados.</h2>
</div>
@endif
@endsection