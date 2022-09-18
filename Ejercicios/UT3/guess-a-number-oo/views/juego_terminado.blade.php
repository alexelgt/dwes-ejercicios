@extends("master")
@section("content")
            <form class="card" name="adivinaelnumero" action="index.php" method="POST">
                <p>{{$mensaje_fin}}</p>

                <div>
                    <button name="tipo_formulario" type="submit" value="reset">Volver a empezar</button>
                </div>
            </form>
@endsection