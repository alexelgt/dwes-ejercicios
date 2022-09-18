let provinciaSaved, municipioSaved, tipoViaSaved, viaTextoSaved, numeroSaved;

$(document).ready(function() {
    $("#provincia").on("change", obtenerMunicipios);
    $('#municipio').on("change", comprobarMunicipio);
    $('#viaTexto').on("change", comprobarViaTexto);
    $('#tipoVia').on("change", comprobarTiaVia);

    $('#viaTexto').on("change", obtenerNumero);
    $('#numero').on("blur", obtenerNumero);
})

function obtenerMunicipios(e) {
    let provincia = this.value;
    
    $("#municipio").val("").prop("disabled", true);
    
    $("#tipoVia").prop('selectedIndex',0).prop("disabled", true);
    $("#viaTexto").val("").prop("disabled", true).prop("placeholder", "");
    $("#numero").val("").prop("disabled", true);
    
    $(".output").css("display", "none");
    $("#container-tabla").html("");
    $("#mapholder").css("display", "none").html("");
    
    $("#loading-municipio").css("display", "inline-block");
    
    $.ajax({
        type: "POST",
        url: "obtener_datos_catastro.php",
        dataType: "json",

        data: {
            obtener: "municipios",
            provincia: provincia
        },

        success: function (result) {

            $("#datalistMunicipios").html("");
            $("#municipio").val("").prop("disabled", false).prop("placeholder", "Escriba el nombre de un municipio");
            
            result["municipios"].forEach(municipio => {
                $("#datalistMunicipios").append(`<option>${municipio[0]}</option>`);
            });
            
            $("#loading-municipio").css("display", "none");
        }
    })
}

function comprobarMunicipio() {
    let municipio = this.value;
    
    if (municipio !== "") {
        if($($("#datalistMunicipios option")).filter(function(){
            return this.value === municipio;
        }).length) {
            $("#tipoVia").prop("disabled", false).prop('selectedIndex',0);
        }
        else {
            $("#tipoVia").prop('selectedIndex',0).prop("disabled", true);
        }
    }
    else {
        $("#tipoVia").prop('selectedIndex',0).prop("disabled", true);
    }
    
    $("#viaTexto").val("").prop("disabled", true).prop("placeholder", "");
    $("#numero").val("").prop("disabled", true);
    
    $(".output").css("display", "none");
    $("#container-tabla").html("");
    $("#mapholder").css("display", "none").html("");
}

function comprobarTiaVia() {
    let provincia = $("#provincia").val();
    let municipio = $("#municipio").val();
    let tipoVia = $("#tipoVia").val();
    
    $("#viaTexto").val("").prop("disabled", true).prop("placeholder", "");
    $("#loading-via").css("display", "inline-block");
    
    $.ajax({
        type: "POST",
        url: "obtener_datos_catastro.php",
        dataType: "json",

        data: {
            obtener: "vias",
            provincia: provincia,
            municipio: municipio,
            tipoVia: tipoVia,
        },

        success: function (result) {
            $("#datalistViaTexto").html("");
            $("#viaTexto").val("").prop("disabled", false).prop("placeholder", "Escriba el nombre de una vía");
            
            result["vias"].forEach(via => { 
                $("#datalistViaTexto").append(`<option>${via[0]}</option>`);
            });
            
            $("#viaTexto").prop("disabled", false);
            $("#numero").val("").prop("disabled", true);
            
            $(".output").css("display", "none");
            $("#container-tabla").html("");
            $("#mapholder").css("display", "none").html("");
            
            $("#loading-via").css("display", "none");
        }
    })
}

function comprobarViaTexto() {
    let viaTexto = this.value;
    
    if (viaTexto !== "") {
        if($($("#datalistViaTexto option")).filter(function(){
            return this.value === viaTexto;
        }).length) {
            $("#numero").prop("disabled", false);
        }
        else {
            $("#numero").val("").prop("disabled", true);
        }
    }
    else {
        $("#numero").val("").prop("disabled", true);
    }
}

function obtenerNumero() {
    let provincia = $("#provincia").val();
    let municipio = $("#municipio").val();
    let tipoVia = $("#tipoVia").val();
    let viaTexto = $("#viaTexto").val();
    let numero = $("#numero").val();
    
    if (((viaTexto !== viaTextoSaved) || (numero !== numeroSaved)) &&
        (
            (provincia !== "") && (municipio !== "") && (tipoVia !== "") && (viaTexto !== "") && (numero !== "")
        )
    ) {
        $.ajax({
            type: "POST",
            url: "obtener_datos_catastro.php",
            dataType: "json",

            data: {
                obtener: "numeros",
                provincia: provincia,
                municipio: municipio,
                tipoVia: tipoVia,
                viaTexto: viaTexto,
                numero: numero,
            },

            success: function (result) {
                $(".output").css("display", "flex");
                $("#container-tabla").html("");

                if (result.error !== undefined) {
                    $("#mapholder").css("display", "none").html("");
                    $("#container-tabla").html(`<h2>${result.error}</h2>`);
                }
                else {
                    let img_url="https://www.google.com/maps?width=400&height=400&hl=es&q="+provincia+","+municipio+","+viaTexto+","+numero+"&t=k&z=20&ie=UTF8&iwloc=B&output=embed";
                    $("#mapholder").css("display", "block").html("<iframe width='400' height='400' src='"+img_url+"'></iframe>");

                    let $tabla = $("<table>").attr("id", "info1").attr("border", "1px");
                    
                    let $filaCabecera = $("<tr>")
                        .append(
                            $("<th>").html("Número")
                        )
                        .append(
                            $("<th>").html("Hoja del catastro")
                        );
                
                    $tabla.append($filaCabecera);
                    
                    result.forEach(elemento => {
                        let filaDatos = $("<tr>")
                        .append(
                            $("<td>").html(elemento["num"])
                        )
                        .append(
                            $("<td>").html(elemento["pc"]).on("click", obtenerReferenciasCatastrales).addClass("pulsable")
                        );
                
                        $tabla.append(filaDatos);
                    });
                    
                    $("#container-tabla").append($tabla);
                }
                
                
                $('html, body').animate({
                    scrollTop: $(".output").offset().top
                }, 1500);
            }
        })
    }
    else {
        $(".output").css("display", "none");
        $("#container-tabla").html("");
        $("#mapholder").css("display", "none").html("");
    }
}

function obtenerReferenciasCatastrales(e) {
    let provincia = $("#provincia").val();
    let municipio = $("#municipio").val();
    let viaTexto = $("#viaTexto").val();
    let numero = $("#numero").val();
    
    let rc = $(this).text();
    
    $.ajax({
        type: "POST",
        url: "obtener_datos_catastro.php",
        dataType: "json",

        data: {
            obtener: "referencia_catastral",
            provincia: provincia,
            municipio: municipio,
            viaTexto: viaTexto,
            numero: numero,
            rc: rc
        },

        success: function (result) {
            $("#container-tabla").html("");

            if (result.error !== undefined) {
                $("#container-tabla").html(`<h2>${result.error}</h2>`);
            }
            else {
                let $tabla = $("<table>").attr("id", "info2").attr("border", "1px");

                let $filaCabecera = $("<tr>")
                    .append(
                        $("<th>").html("Vía")
                    )
                    .append(
                        $("<th>").html("Número")
                    )
                    .append(
                        $("<th>").html("Escalera")
                    )
                    .append(
                        $("<th>").html("Planta")
                    )
                    .append(
                        $("<th>").html("Puerta")
                    )
                    .append(
                        $("<th>").html("Referencia Catastral")
                    );

                $tabla.append($filaCabecera);

                result.forEach(elemento => {
                    let filaDatos = $("<tr>")
                    .append(
                        $("<td>").html(elemento["via"])
                    )
                    .append(
                        $("<td>").html(elemento["numero"])
                    )
                    .append(
                        $("<td>").html(elemento["escalera"])
                    )
                    .append(
                        $("<td>").html(elemento["planta"])
                    )
                    .append(
                        $("<td>").html(elemento["puerta"])
                    )
                    .append(
                        $("<td>").html(elemento["rc"]).on("click", obtenerDatosInmuelbe).addClass("pulsable")
                    );

                    $tabla.append(filaDatos);
                });

                $("#container-tabla").append($tabla);
            }
        }
    })
}

function obtenerDatosInmuelbe(e) {
    let provincia = $("#provincia").val();
    let municipio = $("#municipio").val();
    
    let rc = $(this).text();
    
    $.ajax({
        type: "POST",
        url: "obtener_datos_catastro.php",
        dataType: "json",

        data: {
            obtener: "datos_inmueble",
            provincia: provincia,
            municipio: municipio,
            rc: rc
        },

        success: function (result) {
            $("#container-tabla").html("");

            if (result.error !== undefined) {
                $("#container-tabla").html(`<h2>${result.error}</h2>`);
            }
            else {
                let $tabla = $("<table>").attr("id", "info3").attr("border", "1px");

                let $filaCabecera = $("<tr>")
                    .append(
                        $("<th>").html("Datos Descriptivos del Inmueble").attr('colspan', 2)
                    );

                $tabla.append($filaCabecera);
                
                let infoFilas = {
                    "rc": "Referencia Catastral",
                    "localizacion": "Localización",
                    "clase": "Clase",
                    "uso": "Uso",
                    "superficie": "Superficie construida",
                    "year": "Año",
                };
                
                for (const dato in infoFilas) {
                    let filaDatos = $("<tr>")
                    .append(
                        $("<td>").html(infoFilas[dato])
                    )
                    .append(
                        $("<td>").html(result[dato])
                    );
            
                    $tabla.append(filaDatos);
                }
                
                incluirPrecio(provincia, municipio, result["superficie"]);
                

                $("#container-tabla").append($tabla);
            }
        }
    })
}

function incluirPrecio(nombre_prov, nombre_mun, superficie) {
    
    $.ajax({
        type: "GET",
        url: `http://localhost/DWES/rest-service-alexelgt/provincia/${nombre_prov}/municipio/${nombre_mun}`,

        success: function (result) {
            let precioTotal = result.precio * superficie;
            let precioTotalString = Intl.NumberFormat("es-ES").format(precioTotal) + "€";
            
            let $tabla = $("#info3");
            
            let filaPrecio = $("<tr>")
            .append(
                $("<td>").html("Precio estimado")
            )
            .append(
                $("<td>").html(precioTotalString)
            );

            $tabla.append(filaPrecio);
        },
        error: function() {
            return null;
        }
    })
}