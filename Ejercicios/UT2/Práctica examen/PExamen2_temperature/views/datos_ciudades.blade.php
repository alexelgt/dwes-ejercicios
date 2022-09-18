@extends("master")
@section("content")
<table>
    <thead>
        <tr>
            <th>Ciudad</th>
            <th>T. min</th>
            <th>T. max</th>
            <th>T. avg</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($datos_ciudades as $ciudad => $datos)
        <tr>
            <td>{{$ciudad}}</td>
            <td>{{$datos["min"]}}</td>
            <td>{{$datos["max"]}}</td>
            <td>{{$datos["avg"]}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection