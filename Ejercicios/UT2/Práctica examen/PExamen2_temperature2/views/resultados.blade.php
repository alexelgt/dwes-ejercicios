@extends("master")
@section("content")
<table>
    <thead>
        <tr>
            <th>Ciudad</th>
            <th>T. máx</th>
            <th>T. min</th>
            <th>T. avg</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($resumen_ciudades as $ciudad => $datos)
        <tr>
            <td>{{$ciudad}}</td>
            <td>{{$datos["max"]}}</td>
            <td>{{$datos["min"]}}</td>
            <td>{{$datos["avg"]}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection