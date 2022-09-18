@extends("master")
@section("content")
<div id="tablero" style="width: {{40 * $size}}px; height: {{40 * $size}}px">
    @for ($x = 0; $x < $size; $x++)
    <div>
        @for ($y = $size - 1; $y >= 0; $y--)
        <div id="{{$y}}-{{$x}}" data-x="{{$x}}"><img src="public/assets/img/blanco.png" alt="vacio"></div>
        @endfor
    </div>
    @endfor
</div>
@endsection