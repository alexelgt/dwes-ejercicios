@extends("master")
@section("content")
<form class="card" name="perfil" action="" method="POST">
    <h2>Bienvenido {{$usuario->getNombre()}}</h2>
    
    <div>
        <button name="tipo_formulario" type="submit" value="menu_inicial">Cerrar sesión</button>
    </div>
</form>

<form class="card" name="contactos" action="" method="POST">
    <h2>Agenda</h2>
    
@foreach ($contactos as $contacto)
    <div>
        <b>Nombre:</b> {{$contacto->getNombre()}}<br>
        <b>Apellido:</b> {{$contacto->getApellido()}}<br>
        <b>Teléfono 1:</b> {{$contacto->getPhone1()}}<br>
        <b>Teléfono 2:</b> {{$contacto->getPhone2()}}<br>
        <b>Descripción:</b> {{$contacto->getDescripcion()}}<br><br>
        
        <button name="tipo_formulario" type="submit" value="borrarContacto_{{$contacto->getId()}}">Eliminar contacto</button>
    </div>
@endforeach

    <div>
        <button name="tipo_formulario" type="submit" value="exportar_agenda">Exportar agenda</button>
    </div>
</form>

<form class="card" name="addcontacto" action="" method="POST">
    <h2>Agregar contacto</h2>
    
    <div>
        <label for="nombre">Nombre</label>
        <input id="nombre" type="text" name="nombre" placeholder="" value="">
    </div>
    
    <div>
        <label for="apellido">Apellido</label>
        <input id="apellido" type="text" name="apellido" placeholder="" value="">
    </div>
    
    <div>
        <label for="phone1">Teléfono 1</label>
        <input id="phone1" type="text" name="phone1" placeholder="" value="">
    </div>
    
    <div>
        <label for="phone2">Teléfono 2</label>
        <input id="phone2" type="text" name="phone2" placeholder="" value="">
    </div>
    
    <div>
        <label for="descripcion">Descripción</label>
        <input id="descripcion" type="text" name="descripcion" placeholder="" value="">
    </div>
    
    <div>
        <button name="tipo_formulario" type="submit" value="agregar_contacto">Agregar contacto</button>
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