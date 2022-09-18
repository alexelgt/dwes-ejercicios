@extends("master")
@section("content")
            <form class="card" name="eleccionformulario" action="" method="POST">
                <div class="error">{{$mensaje_error}}</div>

                <div>
                    <button name="tipo_formulario" type="submit" value="menu_inicial">Volver al menú inicial</button>
                </div>
            </form>
@endsection