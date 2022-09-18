@extends("master")
@section("content")
<form class="card perfil" name="perfil" action="/" method="POST">
    <div>
        <img src="{{$picture}}" />
        <h2>{{$name}}</h2>
    </div>
    
    <button name="tipo_formulario" type="submit" value="cerrar_sesion">Cerrar sesión</button>
</form>
<form class="card" name="catastro" action="" method="POST">
    <h2>Consulta de datos catastrales</h2>

    <div>
        <label for="provincia">Provincias</label>
        <select id="provincia" name="provincia">
            <option selected="selected" disabled>Seleccione una provincia</option>
@foreach ($provincias as $provincia)
            <option value="{{$provincia}}">{{$provincia}}</option>
@endforeach
        </select>
    </div>

    <div>
        <div><label for="municipio">Municipio</label><img id="loading-municipio" class="loading" src="public/assets/img/loading.gif" alt="loading"/></div>
        <input id="municipio" type="text" name="municipio" list="datalistMunicipios" disabled>
        <datalist id="datalistMunicipios"></datalist>
    </div>
    
    <div>
        <div><label for="tipoVia">Vía</label><img id="loading-via" class="loading" src="public/assets/img/loading.gif" alt="loading"/></div>
        <div class="multi">
            <select id="tipoVia" name="tipoVia" disabled>
                <option selected="selected" disabled>Seleccione una via</option>
                <option value="CL">CALLE</option>
                <option value="AV">AVENIDA</option>
                <option value="PZ">PLAZA</option>
                <option value="PS">PASEO</option>
                <option value="CR">CARRETERA, CARRERA</option>
                <option value="AC">ACCESO</option>
                <option value="AG">AGREGADO</option>
                <option value="AL">ALDEA, ALAMEDA</option>
                <option value="AN">ANDADOR</option>
                <option value="AR">AREA, ARRABAL</option>
                <option value="AY">ARROYO</option>
                <option value="AU">AUTOPISTA</option>
                <option value="BJ">BAJADA</option>
                <option value="BL">BLOQUE</option>
                <option value="BR">BARRANCO</option>
                <option value="BQ">BARRANQUIL</option>
                <option value="BO">BARRIO</option>
                <option value="BV">BULEVAR</option>
                <option value="CY">CALEYA</option>
                <option value="CJ">CALLEJA, CALLEJON</option>
                <option value="CZ">CALLIZO</option>
                <option value="CM">CAMINO, CARMEN</option>
                <option value="CP">CAMPA, CAMPO</option>
                <option value="CA">CAÑADA</option>
                <option value="CS">CASERIO</option>
                <option value="CH">CHALET</option>
                <option value="CI">CINTURON</option>
                <option value="CG">COLEGIO, CIGARRAL</option>
                <option value="CN">COLONIA</option>
                <option value="CO">CONCEJO, COLEGIO</option>
                <option value="CU">CONJUNTO</option>
                <option value="CT">CUESTA, COSTANILLA</option>
                <option value="DE">DETRAS</option>
                <option value="DP">DIPUTACION</option>
                <option value="DS">DISEMINADOS</option>
                <option value="ED">EDIFICIOS</option>
                <option value="EN">ENTRADA, ENSANCHE</option>
                <option value="ES">ESCALINATA</option>
                <option value="ES">ESPALDA</option>
                <option value="EX">EXPLANADA</option>
                <option value="EM">EXTRAMUROS</option>
                <option value="ER">EXTRARRADIO</option>
                <option value="FC">FERROCARRIL</option>
                <option value="FN">FINCA</option>
                <option value="GL">GLORIETA</option>
                <option value="GV">GRAN VIA</option>
                <option value="GR">GRUPO</option>
                <option value="HT">HUERTA, HUERTO</option>
                <option value="JR">JARDINES</option>
                <option value="LD">LADO, LADERA</option>
                <option value="LA">LAGO</option>
                <option value="LG">LUGAR</option>
                <option value="MA">MALECON</option>
                <option value="MZ">MANZANA</option>
                <option value="MS">MASIAS</option>
                <option value="MC">MERCADO</option>
                <option value="MT">MONTE</option>
                <option value="ML">MUELLE</option>
                <option value="MN">MUNICIPIO</option>
                <option value="PM">PARAMO</option>
                <option value="PQ">PARROQUIA, PARQUE</option>
                <option value="PI">PARTICULAR</option>
                <option value="PD">PARTIDA</option>
                <option value="PU">PASADIZO</option>
                <option value="PJ">PASAJE, PASADIZO</option>
                <option value="PC">PLACETA</option>
                <option value="PB">POBLADO</option>
                <option value="PL">POLIGONO</option>
                <option value="PR">PROLONGACION, CONTINUAC.</option>
                <option value="PT">PUENTE</option>
                <option value="QT">QUINTA</option>
                <option value="RA">RACONADA</option>
                <option value="RM">RAMAL</option>
                <option value="RB">RAMBLA</option>
                <option value="RC">RINCON, RINCONA</option>
                <option value="RD">RONDA</option>
                <option value="RP">RAMPA</option>
                <option value="RR">RIERA</option>
                <option value="RU">RUA</option>
                <option value="SA">SALIDA</option>
                <option value="SN">SALON</option>
                <option value="SC">SECTOR</option>
                <option value="SD">SENDA</option>
                <option value="SL">SOLAR</option>
                <option value="SU">SUBIDA</option>
                <option value="TN">TERRENOS</option>
                <option value="TO">TORRENTE</option>
                <option value="TR">TRAVESIA</option>
                <option value="UR">URBANIZACION</option>
                <option value="VA">VALLE</option>
                <option value="VR">VEREDA</option>
                <option value="VI">VIA</option>
                <option value="VD">VIADUCTO</option>
                <option value="VL">VIAL</option>
                <option value="--">--</option>
            </select>

            <input id="viaTexto" type="text" name="viaTexto" list="datalistViaTexto" disabled>
            <datalist id="datalistViaTexto"></datalist>
        </div>
    </div>
    
    <div>
        <label for="numero">Número</label>
        <input id="numero" type="number" name="numero" disabled>
    </div>
</form>

<div class="card output">
    <div id="container-tabla"></div>
    <div id="mapholder"></div>
</div>
@endsection