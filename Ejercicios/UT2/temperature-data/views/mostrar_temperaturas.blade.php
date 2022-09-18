@extends("master")
@section("content")
            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Ciudad</th>
                            <th>Temperatura mínima</th>
                            <th>Temperatura máxima</th>
                            <th>Temperatura mínima media</th>
                            <th>Temperatura máxima media</th>
                        </tr>
                    </thead>
                    <tbody>
                @for ($i = 0; $i < count($ciudades); $i++)
                        <tr>
                            <td>{{$ciudades[$i]}}</td>
                            <td>{{$temp_min_array[$i]}}</td>
                            <td>{{$temp_max_array[$i]}}</td>
                            <td>{{$temp_min_media_array[$i]}}</td>
                            <td>{{$temp_max_media_array[$i]}}</td>
                        </tr>
                @endfor
                    </tbody>
                </table>
            </div>
@endsection