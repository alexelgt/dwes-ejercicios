@extends("master")
@section("content")
<form class="card" name="ciudades" action="/" method="POST">
@foreach ($ciudades as $ciudad)
    <div>
        <div>
            <label>{{$ciudad}}</label>
        </div>
        
    @foreach ($meses as $mes)
        <div>
            <label>{{$mes}}</label>
            <input type="text" name="temperaturas[{{$ciudad}}][{{$mes}}][min]" value="{{mt_rand(-30, 0)}}">
            <input type="text" name="temperaturas[{{$ciudad}}][{{$mes}}][max]" value="{{mt_rand(0, 40)}}">
        </div>
    @endforeach
    </div>
@endforeach
    
    <div>
        <button name="tipo_formulario" type="submit" value="temperaturas">Enviar</button>
    </div>
</form>
@endsection