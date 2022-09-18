@extends("master")
@section("content")
<form class="card" name="perfil" action="" method="POST">
    <h2>Bienvenido {{$usuario->getNombre()}}</h2>

    <div>
        <button name="tipo_formulario" type="submit" value="menu_inicial">Cerrar sesión</button>
    </div>
</form>

<form class="card" name="elegircategoria" action="" method="POST">
    <h2>Elegir categoría</h2>
@foreach ($categorias as $categoria)
    <div>
        <button name="tipo_formulario" type="submit" value="elegirCategoria_{{$categoria->getId()}}">{{$categoria->getNombre()}}</button>
    </div>
@endforeach
</form>

@if (!is_null($categoria_elegida))
<form class="card" name="productos" action="" method="POST">
    <h2>Productos de la categoría {{$categoria_elegida->getNombre()}}</h2>
    
    <div>
        <button name="tipo_formulario" type="submit" value="form_add_producto">Añadir producto</button>
    </div>
    
    <table>
        <tr>
            <th>Nombre</th>
            <th>Precio</th>
            <th></th>
        </tr>
@foreach ($productos as $producto)
        <tr>
            <td>{{$producto->getNombre()}}</td>
            <td>{{$producto->getPrecio()}}</td>
            <td><button name="tipo_formulario" type="submit" value="formEditarProducto_{{$producto->getId()}}">Modificar</button></td>
        </tr>
@endforeach
    </table>

</form>
@endif
@endsection