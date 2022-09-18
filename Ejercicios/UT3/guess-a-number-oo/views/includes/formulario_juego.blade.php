            <form class="card" name="adivinaelnumero" action="index.php" method="POST">
                <p>Número de intentos: {{$partida->getNumeroIntentos()}}</p>

                <p>Límite inferior: {{$partida->getLimiteInferior()}}</p>

                <p>Límite superior: {{$partida->getLimiteSuperior()}}</p>
                
                <div>
@if (!isset($numero) || (isset($numero) && $numero_valido))
                    <label for="numero">Número</label>
@else
                    <label class="no-valido" for="numero">Número</label>
@endif
                    <input id="numero" type="text" name="numero" placeholder="Introduce un número">
                </div>
                
                <div>
                    <button name="tipo_formulario" type="submit" value="juego">Enviar</button>
                </div>
@if (!$partida->getPartidaIniciada())
                <div>
                    <button name="tipo_formulario" type="submit" value="reset">Volver a empezar</button>
                </div>
@endif
            </form>