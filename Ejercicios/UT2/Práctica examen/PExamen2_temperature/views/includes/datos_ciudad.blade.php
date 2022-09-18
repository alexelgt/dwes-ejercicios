<div>
    <h2>{{$ciudad}}</h2>
    
@foreach ($meses as $mes)
    <div>
        <h3>{{$mes}}</h3>

        <label>T. min</label>
        <input type="text" name="temperaturas[{{$ciudad}}][{{$mes}}][min]" value="{{mt_rand(-33, 0)}}">
        
        <label>T. max</label>
        <input type="text" name="temperaturas[{{$ciudad}}][{{$mes}}][max]" value="{{mt_rand(0, 40)}}">
    </div>
@endforeach
</div>