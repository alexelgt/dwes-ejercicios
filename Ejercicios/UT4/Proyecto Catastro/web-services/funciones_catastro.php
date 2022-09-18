<?php


define("DATOS", [
    "PROVINCIAS" => [
        "SOAPAction" => "http://tempuri.org/OVCServWeb/OVCCallejero/ConsultaProvincia",
        "post_string" => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
       <soapenv:Header/>
       <soapenv:Body/>
    </soapenv:Envelope>'
    ],
    "MUNICIPIOS" => [
        "SOAPAction" => "http://tempuri.org/OVCServWeb/OVCCallejero/ConsultaMunicipio",
        "post_string" => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:cat="http://www.catastro.meh.es/">
   <soapenv:Header/>
   <soapenv:Body>
      <cat:Provincia>{PROVINCIA}</cat:Provincia>
      <cat:Municipio></cat:Municipio>
   </soapenv:Body>
</soapenv:Envelope>'
    ],
    "NUMEROS" => [
        "SOAPAction" => "http://tempuri.org/OVCServWeb/OVCCallejero/ConsultaNumero",
        "post_string" => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:cat="http://www.catastro.meh.es/">
   <soapenv:Header/>
   <soapenv:Body>
      <cat:Provincia>{PROVINCIA}</cat:Provincia>
      <cat:Municipio>{MUNICIPIO}</cat:Municipio>
      <cat:TipoVia>{TIPOVIA}</cat:TipoVia>
      <cat:NomVia>{NOMVIA}</cat:NomVia>
      <cat:Numero>{NUMERO}</cat:Numero>
   </soapenv:Body>
</soapenv:Envelope>'
    ],
    "RC" => [
        "SOAPAction" => "http://tempuri.org/OVCServWeb/OVCCallejero/Consulta_DNPRC",
        "post_string" => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:cat="http://www.catastro.meh.es/">
   <soapenv:Header/>
   <soapenv:Body>
      <cat:Provincia>{PROVINCIA}</cat:Provincia>
      <cat:Municipio>{MUNICIPIO}</cat:Municipio>
      <cat:RefCat>{RC}</cat:RefCat>
   </soapenv:Body>
</soapenv:Envelope>'
    ],
    "VIAS" => [
        "SOAPAction" => "http://tempuri.org/OVCServWeb/OVCCallejero/ConsultaVia",
        "post_string" => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:cat="http://www.catastro.meh.es/">
   <soapenv:Header/>
   <soapenv:Body>
      <cat:Provincia>{PROVINCIA}</cat:Provincia>
      <cat:Municipio>{MUNICIPIO}</cat:Municipio>
      <cat:TipoVia>{TIPOVIA}</cat:TipoVia>
      <cat:NombreVia></cat:NombreVia>
   </soapenv:Body>
</soapenv:Envelope>'
    ]
]);

function peticion_catastro($tipo_peticion, $parametros=[[],[]]) {
    $request = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx";
    $xml_post_string = DATOS[$tipo_peticion]["post_string"];
    
    $xml_post_string = str_replace($parametros[0], $parametros[1], $xml_post_string);
    
    $headers = [
        'Content-Type: text/xml;charset=UTF-8',
        'SOAPAction: ' . DATOS[$tipo_peticion]["SOAPAction"],
        'Content-lenght: ' . strlen($xml_post_string)
    ];
    
    
    $ch = curl_init();
    
    
    curl_setopt($ch, CURLOPT_URL, $request);
    
    curl_setopt($ch, CURLOPT_POST, 1);
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $output = curl_exec($ch);
    
    curl_close($ch);
    
    $respuesta = str_ireplace(["SOAP-ENV:", "SOAP:"], "", $output);
    
    return simplexml_load_string($respuesta);
}

function procesar_provincias($provincias_xml) {
    $num_provincias = $provincias_xml->Body->Provincias->consulta_provinciero->control->cuprov;
    
    $provincias = [];
    
    for ($i = 0; $i < $num_provincias; $i++) {
        $provincias[] = $provincias_xml->Body->Provincias->consulta_provinciero->provinciero->prov[$i]->np;
    }
    
    return $provincias;
}

function procesar_vias($vias_xml) {
    $num_vias = $vias_xml->Body->Callejero->consulta_callejero->control->cuca;
    
    $vias = [];
    
    for ($i = 0; $i < $num_vias; $i++) {
        $vias[] = $vias_xml->Body->Callejero->consulta_callejero->callejero->calle[$i]->dir->nv;
    }
    
    return $vias;
}

function procesar_municipios($municipios_xml) {
    $num_municipios = $municipios_xml->Body->Municipios->consulta_municipiero->control->cumun;
    
    $municipios = [];
    
    for ($i = 0; $i < $num_municipios; $i++) {
        $municipios[] = $municipios_xml->Body->Municipios->consulta_municipiero->municipiero->muni[$i]->nm;
    }
    
    return $municipios;
}

function procesar_numeros($numeros_xml) {
    if (isset($numeros_xml->Body->Callejero->consulta_numerero->control->cuerr)) {
        $num_errores = $numeros_xml->Body->Callejero->consulta_numerero->control->cuerr;
        
        $mensaje_error = "";
        
        for ($i = 0; $i < $num_errores; $i++) {
            $error = $numeros_xml->Body->Callejero->consulta_numerero->lerr->err[$i]->des;
            $mensaje_error .= "$error<br>";
        }
        
        return $mensaje_error;
    }
    $num_numerero = $numeros_xml->Body->Callejero->consulta_numerero->control->cunum;
    
    $numeros = [];
    
    for ($i = 0; $i < $num_numerero; $i++) {
        $num = $numeros_xml->Body->Callejero->consulta_numerero->numerero->nump[$i]->num->pnp;
        $pc1 = $numeros_xml->Body->Callejero->consulta_numerero->numerero->nump[$i]->pc->pc1;
        $pc2 = $numeros_xml->Body->Callejero->consulta_numerero->numerero->nump[$i]->pc->pc2;

        $numeros[] = ["num" => "$num", "pc" => "$pc1" . "$pc2"];
    }
    
    return $numeros;
}

function procesar_rc($ref_cat_xml, $viaOriginal, $numeroOriginal) {
    if (isset($ref_cat_xml->Body->Consulta_DNP->consulta_dnp->control->cuerr)) {
        $num_errores = $ref_cat_xml->Body->Consulta_DNP->consulta_dnp->control->cuerr;
        
        $mensaje_error = "";
        
        for ($i = 0; $i < $num_errores; $i++) {
            $error = $ref_cat_xml->Body->Consulta_DNP->consulta_dnp->lerr->err[$i]->des;
            $mensaje_error .= "$error<br>";
        }
        
        return $mensaje_error;
    }
    $num_rc = $ref_cat_xml->Body->Consulta_DNP->consulta_dnp->control->cudnp;
    
    $ref_cat = [];
    
    $datos = [];
    
    if ($num_rc == 1) {
        $rc_elemento = $ref_cat_xml->Body->Consulta_DNP->consulta_dnp->bico->bi->idbi->rc;
        $lourb_elemento = $ref_cat_xml->Body->Consulta_DNP->consulta_dnp->bico->bi->dt->locs->lous->lourb;
        
        $datos[] = ["rc_elemento" => $rc_elemento, "lourb_elemento" => $lourb_elemento];
    }
    else {
        for ($i = 0; $i < $num_rc; $i++) {
            $rc_elemento = $ref_cat_xml->Body->Consulta_DNP->consulta_dnp->lrcdnp->rcdnp[$i]->rc;
            $lourb_elemento = $ref_cat_xml->Body->Consulta_DNP->consulta_dnp->lrcdnp->rcdnp[$i]->dt->locs->lous->lourb;
            
            $datos[] = ["rc_elemento" => $rc_elemento, "lourb_elemento" => $lourb_elemento];
        }
    }
    
    for ($i = 0; $i < count($datos); $i++) {
        $rc_elemento = $datos[$i]["rc_elemento"];
        $lourb_elemento = $datos[$i]["lourb_elemento"];

        $rc = "" . $rc_elemento->pc1 . $rc_elemento->pc2 . $rc_elemento->car . $rc_elemento->cc1 . $rc_elemento->cc2;
        $via = "" . $lourb_elemento->dir->nv;
        $numero = "" . $lourb_elemento->dir->pnp;
        $escalera = "" . $lourb_elemento->loint->es;
        $planta = "" . $lourb_elemento->loint->pt;
        $puerta = "" . $lourb_elemento->loint->pu;
        
        if ($viaOriginal == $via && $numeroOriginal == $numero) {
            $ref_cat[] = ["rc" => $rc, "via" => $via, "numero" => $numero, "escalera" => $escalera, "planta" => $planta, "puerta" => $puerta];
        }
    }
    
    return $ref_cat;
}

function procesar_datos_inmueble($datos_inmueble_xml) {
    if (isset($datos_inmueble_xml->Body->Consulta_DNP->consulta_dnp->control->cuerr)) {
        $num_errores = $datos_inmueble_xml->Body->Consulta_DNP->consulta_dnp->control->cuerr;
        
        $mensaje_error = "";
        
        for ($i = 0; $i < $num_errores; $i++) {
            $error = $datos_inmueble_xml->Body->Consulta_DNP->consulta_dnp->lerr->err[$i]->des;
            $mensaje_error .= "$error<br>";
        }
        
        return $mensaje_error;
    }
    $num_inmueble = $datos_inmueble_xml->Body->Consulta_DNP->consulta_dnp->control->cudnp;
    
    if ($num_inmueble == 1) {
        $rc_elemento = $datos_inmueble_xml->Body->Consulta_DNP->consulta_dnp->bico->bi->idbi->rc;
        $lourb_elemento = $datos_inmueble_xml->Body->Consulta_DNP->consulta_dnp->bico->bi->dt->locs->lous->lourb;
        
        $localizacion = "" . $datos_inmueble_xml->Body->Consulta_DNP->consulta_dnp->bico->bi->ldt;
        $clase = "" . $datos_inmueble_xml->Body->Consulta_DNP->consulta_dnp->bico->bi->idbi->cn;
        
        $uso = "" . $datos_inmueble_xml->Body->Consulta_DNP->consulta_dnp->bico->bi->debi->luso;
        $superficie = "" . $datos_inmueble_xml->Body->Consulta_DNP->consulta_dnp->bico->bi->debi->sfc;
        $year = "" . $datos_inmueble_xml->Body->Consulta_DNP->consulta_dnp->bico->bi->debi->ant;
        
        $rc = "" . $rc_elemento->pc1 . $rc_elemento->pc2 . $rc_elemento->car . $rc_elemento->cc1 . $rc_elemento->cc2;
        
        $datos_inmueble = ["rc" => $rc, "localizacion" => $localizacion, "clase" => $clase, "uso" => $uso, "superficie" => $superficie, "year" => $year];
    }
    
    return $datos_inmueble;
}

