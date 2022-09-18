$(document).ready(function() {
    $("#tablero").click(comprobarPulsacion);
})

function comprobarPulsacion(e) {
    let box = e.target;
    
    if (box.nodeName === "IMG") {
        box = box.parentNode;
    }

    if (box.nodeName === "DIV" && box.dataset.x !== undefined) {
        $.ajax({
            type: "POST",
            url: "index.php",
            dataType: "json",

            data: {
                x: box.dataset.x
            },

            success: function (result) {
                if (result.x !== undefined) {
                    $(`#${result.y}-${result.x}`).html('<img src="public/assets/img/amarillo.png"  alt="jugador">');
                }
                
                if (result.x_maquina !== undefined) {
                    $(`#${result.y_maquina}-${result.x_maquina}`).html('<img src="public/assets/img/rojo.png"  alt="maquina">');
                }
                
                if (result.gameRes !== undefined) {
                    let mensajesResultado = {
                        "-1": "Perdiste",
                        "0": "Empate",
                        "1": "Ganaste"
                    };
                    
                    $("#mensaje").text(mensajesResultado[result.gameRes]);
                    
                    $("#tablero").unbind("click");
                }
            }
        })
    }
}