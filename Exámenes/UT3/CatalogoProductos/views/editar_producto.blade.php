@extends("master")
@section("content")
<form class="card" name="editarproducto" action="" method="POST">
    <div>
        <label for="nombre">Nombre</label>
        <input id="nombre" type="text" name="nombre" placeholder="" value="{{$nombre}}">
    </div>

    <div>
        <label for="precio">Precio</label>
        <input id="precio" type="number" name="precio" min="0" max="999.99" step="0.01" placeholder="" value="{{$precio}}">
    </div>
    
    <div>
        <label for="categoria">Categoría</label>
        <select id="categoria" name="categoria">
@foreach ($categorias as $categoria)
@if ($categoria->getId() === $categoria_id)
<option value="{{$categoria->getId()}}" selected>{{$categoria->getNombre()}}</option>
@else
<option value="{{$categoria->getId()}}">{{$categoria->getNombre()}}</option>
@endif
@endforeach
        </select>
    </div>

    <div>
        <button name="tipo_formulario" type="submit" value="editarProducto_{{$id_producto}}">Editar</button>
    </div>

    <div>
        <button name="tipo_formulario" type="submit" value="volver_perfil">Volver al perfil</button>
    </div>
</form>

@if (isset($mensajes_error))
<div class="card error">
    @foreach ($mensajes_error as $mensaje_error)
    <h2>{{$mensaje_error}}</h2>
    @endforeach
</div>
@endif
@endsection