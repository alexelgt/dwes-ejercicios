@extends("master")
@section("content")
            <div id="tablero" style="width: {{$size_tablero * 40}}px; height: {{$size_tablero * 40}}px">
            @for($y = 0; $y < $size_tablero; $y++)
                @for($x = 0; $x < $size_tablero; $x++)
                    <div id="{{$y}}-{{$x}}" data-y="{{$y}}" data-x="{{$x}}"></div>
                @endfor
            @endfor
            </div>
@endsection

