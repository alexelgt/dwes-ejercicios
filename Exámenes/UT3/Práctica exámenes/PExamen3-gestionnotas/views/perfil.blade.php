@extends("master")
@section("content")
<form class="card" name="perfil" action="" method="POST">
    <h2>Bienvenido {{$profesor->getName()}}</h2>
    
    <div>
        <button name="tipo_formulario" type="submit" value="exportar_xml">Exportar a XML</button>
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="menu_inicial">Cerrar sesión</button>
    </div>
</form>

<form class="card" name="notas" action="" method="POST">
    <h2>Notas</h2>
    
    <table>
<tr>
<th></th>
@foreach ($asignaturas as $asignatura)
<th>
    {{$asignatura->getName()}}
</th>
@endforeach
</tr>
@foreach ($alumnos as $alumno)
<tr>
<td>
    {{$alumno->getNombre()}}
</td>
@foreach ($asignaturas as $asignatura)
<td>
    <input type="text" name="notas[{{$alumno->getId()}}][{{$asignatura->getId()}}]" placeholder="" value="{{$alumno->obtenerValorNota($bd, $asignatura->getId())}}">
</td>
@endforeach
<td>
    <input type="checkbox" name="test[{{$alumno->getId()}}][{{$asignatura->getId()}}][aaaa]" />
</td>
</tr>
@endforeach
    </table>
    
    <div>
        <button name="tipo_formulario" type="submit" value="actualizar_notas">Actualizar</button>
    </div>
</form>
@endsection