@extends("master")
@section("content")
<form class="card" name="perfil1" action="" method="POST">
    <h2>Bienvenido {{$usuario->getName()}}</h2>
    
    <h3>Saldo: {{$saldo}}</h3>
    
    <div>
        <button name="tipo_formulario" type="submit" value="descargar_xml">Descargar XML</button>
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="menu_inicial">Cerrar sesión</button>
    </div>
</form>

<form class="card" name="perfilapuntes" action="" method="POST">
    <h2>Apuntes</h2>
    
    <div>
        <button name="tipo_formulario" type="submit" value="perfilMostrar_todos">Mostrar todos</button>
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="perfilMostrar_ingresos">Mostrar ingresos</button>
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="perfilMostrar_gastos">Mostrar gastos</button>
    </div>
    
@if (count($apuntes) > 0)
<table>
@foreach ($apuntes as $apunte)
<tr>
    <td>
        {{$apunte->getConcepto()}}
    </td>
    <td>
        {{$apunte->getFecha()}}
    </td>
    @if ($apunte->getIngreso())
    <td style="color:green">
    @else
    <td style="color:red">
    @endif
        {{$apunte->getCantidad()}}
    </td>
    <td>
        <button name="tipo_formulario" type="submit" value="ok">Enviar</button>
    </td>
</tr>
@endforeach
</table>
@endif
</form>

<form class="card" name="perfiladdapunte" action="" method="POST">
    <h2>Añadir apunte</h2>
    
    <div>
        <label for="ingreso">Tipo de apunte</label>
        <select id="ingreso" name="ingreso">
            <option value="1">Ingreso</option>
            <option value="0">Gasto</option>
        </select>
    </div>
    
    <div>
        <label for="concepto">Concepto</label>
        <input id="concepto" type="text" name="concepto" placeholder="" value="">
    </div>
    
    <div>
        <label for="cantidad">Cantidad</label>
        <input id="cantidad" type="text" name="cantidad" placeholder="" value="">
    </div>
    
    <div>
        <label for="fecha">Fecha</label>
        <input id="fecha" type="date" name="fecha" placeholder="" value="">
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="addPunte_{{$usuario->getId()}}">Añadir</button>
    </div>
    
@if (isset($mensaje_error))
    <h3>{{$mensaje_error}}</h3>
@endif
</form>
@endsection