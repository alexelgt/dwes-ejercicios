function iniciarContador() {
    intervaloContador = setInterval(() => {
        segundos = parseInt(document.getElementById("contador").innerHTML);
        document.getElementById("contador").innerHTML = segundos + 1;
        
    }, 1000);
}

function checkPosition(e) {
    //console.log(e);
    if ((e.target.nodeName === 'DIV') && (e.target.childElementCount === 0)) {
        if (intervaloContador === undefined) {
            iniciarContador();
        }
        var box = e.target;

        $.ajax({//Jquery ajax implementation
            type: 'POST',
            url: 'index.php',
            dataType: "json",
            data: {
                x: box.dataset.x,
                y: box.dataset.y
            },
            success: function (result) {
                //console.log(result);
                
                if (result.casillas_actualizadas !== undefined) {
                    $deltaMinas = 0; // Corrección en el contador de minas por si se marca una casillaque no contiene mina y luego se desoculta

                    for (const casilla of result.casillas_actualizadas) {
                        if ((casilla.value !== -1) && $(`#${casilla.y}-${casilla.x}`).children().length > 0) {
                            $deltaMinas++;
                        }
                        $(`#${casilla.y}-${casilla.x}`).html(`<img src="public/assets/img/${casilla.value}.png">`);
                        $(`#${casilla.y}-${casilla.x}`).addClass("pulsado");

                        if ((casilla.value === -1) && (casilla.y == box.dataset.y) && (casilla.x == box.dataset.x)) {
                            $(`#${casilla.y}-${casilla.x}`).addClass("mina-explotada");
                        }
                        else {
                            
                        }
                    }
                    
                    if ($deltaMinas !== 0) {
                        $contadorMinas = parseInt($("#marcador").html());
                        $("#marcador").html($contadorMinas + $deltaMinas);
                    }
                }
                if (result.gameRes !== undefined) {
                    clearInterval(intervaloContador);

                    switch (result.gameRes) {
                        case 1:
                            $("#cara").html(`<img class="flag" src="public/assets/img/win.png">`);
                            $("#marcador").html("0");
                            
                            //console.log(result.minas);
                            if (result.minas !== undefined) {
                                for (const mina of result.minas) {
                                    $(`#${mina.y}-${mina.x}`).html(`<img class="flag" src="public/assets/img/flag.png">`);
                                }
                            }
                            break;
                        case -1:
                            $("#cara").html(`<img class="flag" src="public/assets/img/loose.png">`);
                            break;
                    }
                    $('#tablero').unbind('click'); //Disables the click callback
                    $('#tablero').unbind('contextmenu');
                }
            }
        });
    }
}

function addFlag(e) {
    //console.log(e);
    if ((e.target.nodeName === 'DIV') && (e.target.childElementCount === 0)) {
        e.preventDefault();
        var box = e.target;
        
        $(`#${box.dataset.y}-${box.dataset.x}`).html(`<img class="flag" src="public/assets/img/flag.png">`);
        
        $contadorMinas = parseInt($("#marcador").html());
        $("#marcador").html($contadorMinas - 1);
    }
    else if ((e.target.nodeName === 'IMG')) {
        e.preventDefault();
        var box = e.target;
        
        //console.log(box.classList.contains("flag"));
        
        if (box.classList.contains("flag")) {
            box.parentNode.removeChild(box);
            
            $contadorMinas = parseInt($("#marcador").html());
        $("#marcador").html($contadorMinas + 1);
        }
    }
}

let intervaloContador;

// Function to add event listener to clicks on the table node element
function load() {
    $('#tablero').click(checkPosition);
    $('#tablero').contextmenu(addFlag);
}

// Function to run the load callback when the page is loaded

document.addEventListener("DOMContentLoaded", load);

