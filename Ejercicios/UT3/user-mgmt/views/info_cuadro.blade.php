@extends("master")
@section("content")
<form class="card" name="info_cuadro" action="" method="POST">
    <h2>{{$cuadro->getTitle()}} ({{$cuadro->getYear()}})</h2>
    
    <p><b>Periodo:</b> {{$cuadro->getPeriod()}}</p>
    
    <p><b>Técnica:</b> {{$cuadro->getTechnique()}}</p>
    
    <p>{{$cuadro->getDescription()}}</p>
    <div>
        <button name="tipo_formulario" type="submit" value="volver_perfil">Volver al perfil</button>
    </div>
</form>
@endsection