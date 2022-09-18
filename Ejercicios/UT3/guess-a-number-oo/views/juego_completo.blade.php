@extends("master")
@section("content")
            @include("includes.formulario_juego")

            <div class="card">
                <p>{{$output_text}}</p>
            </div>
@endsection