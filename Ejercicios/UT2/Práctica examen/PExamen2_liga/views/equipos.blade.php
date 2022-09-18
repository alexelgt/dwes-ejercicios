@extends("master")
@section("content")
<form class="card" name="equipos" action="/" method="POST">
    <div>
        <label for="equipos">Equipos</label>
        <input id="equipos" type="text" name="equipos" placeholder="" value="">
    </div>
    
    <div>
        <button name="botoncito" type="submit" value="equipos">Enviar</button>
    </div>
</form>
@if (isset($equipos_string_valido) && !$equipos_string_valido)
<div class="card">
    <h2 class="no-valido">Cadena de equipos no válida.</h2>
</div>
@endif
@endsection