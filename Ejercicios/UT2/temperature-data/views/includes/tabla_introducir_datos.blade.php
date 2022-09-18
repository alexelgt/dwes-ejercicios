<div class="table">
    <div class="tr">
        <div class="tc"></div>
        <div class="tc bold">{{$nombre_ciudad}}</div>
        <div class="tc"></div>
    </div>
    <div class="tr">
        <div class="tc bold">Mes</div>
        <div class="tc bold">Temperatura mínima</div>
        <div class="tc bold">Temperatura máxima</div>
    </div>
@for ($i = 0; $i < count($meses); $i++)
    <div class="tr">
        <div class="tc bold">{{$meses[$i]}}</div>
        <div class="tc"><input type="number" name="temperaturas[{{$nombre_ciudad}}][{{$i}}][min]" value="{{rand(-10, 0)}}"></div>
        <div class="tc"><input type="number" name="temperaturas[{{$nombre_ciudad}}][{{$i}}][max]" value="{{rand(0, 40)}}"></div>
    </div>
@endfor
</div>