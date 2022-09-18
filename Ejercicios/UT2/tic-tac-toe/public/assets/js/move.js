// Function to capture the click on the board box,
// updates the board with the player's choice
// uses the info to send a message to the server via AJAX,
// Updates the board using the information coming from the server about the computer's choice
// The server can also send the winner information
// The board updates or the win message is shown in the page

function checkPosition(e) {
    console.log(e);
    if ((e.target.nodeName === 'DIV') && (e.target.childElementCount === 0)) {
        var box = e.target;

        box.innerHTML = '<img src="public/assets/img/o.svg">';
        $(`#${box.id}`).addClass("pulsado");
        $.ajax({//Jquery ajax implementation
            type: 'POST',
            url: 'index.php',
            dataType: "json",
            data: {
                x: box.dataset.x,
                y: box.dataset.y
            },
            success: function (result) {
                console.log(result);
                if (result.x !== undefined) {
                    $(`#casilla-${result.y}${result.x}`).html('<img src="public/assets/img/x.svg">');
                    $(`#casilla-${result.y}${result.x}`).addClass("pulsado");
                }
                if (result.gameRes !== undefined) {
                    switch (result.gameRes) {
                        case 0:
                            $("#message").text("Empate");
                            break;
                        case 1:
                            $("#message").text("¡Ganaste!");
                            break;
                        case -1:
                            $("#message").text("¡Perdiste!");
                            break;
                    }
                    $('#tablero').unbind('click'); //Disables the click callback
                }
            }
        });
    }
}
;

// Function to add event listener to clicks on the table node element
function load() {
    $('#tablero').click(checkPosition);
}

// Function to run the load callback when the page is loaded

document.addEventListener("DOMContentLoaded", load);

