@extends("master")
@section("content")
<form class="card" name="perfil" action="" method="POST">
    <h2>Bienvenido {{$usuario->getName()}}</h2>
    
    <div>
        <button name="tipo_formulario" type="submit" value="menu_inicial">Cerrar sesión</button>
    </div>
</form>

<form class="card" name="perfil" action="" method="POST">
    <h2>Exportar a XML</h2>
    
    <div>
        <label for="nombre">Grupo</label>
        <select id="grupo" name="grupo">
@foreach ($grupos as $grupo)
            <option value="{{$grupo->getId()}}">{{$grupo->getNombre()}}</option>
@endforeach
        </select>

    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="exportar_xml">Exportar</button>
    </div>
</form>

@foreach ($grupos as $grupo)
<form class="card" name="alumnos" action="" method="POST">
    <h2>Grupo {{$grupo->getNombre()}}</h2>
    
    <table>
        <tr>
            <th>Nombre</th>
            <th>Apellido 1</th>
            <th>Apellido 2</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th></th>
            <th></th>
        </tr>
@foreach ($grupo->obtenerAlumnos($bd) as $alumno)
        <tr>
            <td>
                <input type="text" name="alumnos[{{$alumno->getId()}}][nombre]" placeholder="" value="{{$alumno->getNombre()}}">
            </td>
            
            <td>
                <input type="text" name="alumnos[{{$alumno->getId()}}][apellido1]" placeholder="" value="{{$alumno->getApellido1()}}">
            </td>
            
            <td>
                <input type="text" name="alumnos[{{$alumno->getId()}}][apellido2]" placeholder="" value="{{$alumno->getApellido2()}}">
            </td>
            
            <td>
                <input type="text" name="alumnos[{{$alumno->getId()}}][edad]" placeholder="" value="{{$alumno->getEdad()}}">
            </td>
            
            <td>
                <input type="text" name="alumnos[{{$alumno->getId()}}][sexo]" placeholder="" value="{{$alumno->getSexo()}}">
            </td>
            
            <td>
                <button name="tipo_formulario" type="submit" value="modificar_{{$grupo->getId()}}_{{$alumno->getId()}}">Editar</button>
            </td>
            
            <td>
                <button name="tipo_formulario" type="submit" value="borrar_{{$alumno->getId()}}">Borrar</button>
            </td>
        </tr>
@endforeach
<tr>
    <td>
        <input id="nombre" type="text" name="nombre" placeholder="" value="">
    </td>
    
    <td>
        <input id="apellido1" type="text" name="apellido1" placeholder="" value="">
    </td>
    
    <td>
        <input id="apellido2" type="text" name="apellido2" placeholder="" value="">
    </td>
    
    <td>
        <input id="edad" type="text" name="edad" placeholder="" value="">
    </td>
    
    <td>
        <input id="sexo" type="text" name="sexo" placeholder="" value="">
    </td>
    
    <td colspan="2">
        <button name="tipo_formulario" type="submit" value="add_{{$grupo->getId()}}">Añadir</button>
    </td>
</tr>
    </table>
</form>
@endforeach
@endsection