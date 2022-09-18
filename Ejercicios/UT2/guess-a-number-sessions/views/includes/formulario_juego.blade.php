            <form class="card" name="adivinaelnumero" action="guessanumber.php" method="POST">
                <p>Número de intentos: {{$data["numero_intentos"]}}</p>

                <p>Límite inferior: {{$data["limite_inferior"]}}</p>

                <p>Límite superior: {{$data["limite_superior"]}}</p>
                
                <div>
@if (!isset($data["numero"]) || (isset($data["numero"]) && $data["numero_valido"]))
                    <label for="numero">Número</label>
@else
                    <label class="no-valido" for="numero">Número</label>
@endif
                    <input id="numero" type="text" name="numero" placeholder="Introduce un número">
                </div>
                
                <div>
                    <button type="submit" value="ok" name="boton">Enviar</button>
                </div>
@if (isset($data["mostrar_volver_jugar"]) && $data["mostrar_volver_jugar"])
                <div>
                    <button type="submit" value="reset"  name="boton">Volver a empezar</button>
                </div>
@endif
            </form>
