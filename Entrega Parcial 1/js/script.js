function openForm() {
    window.open('/html/form.html', '_blank');
}

document.getElementById("formulario").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita el envío del formulario    
    alert("¡Registro realizado!"); // Muestra el popup
});

function validarFormulario() {
    var nombre = document.getElementById("nombre").value;
    var apellido = document.getElementById("apellido").value;
    var email = document.getElementById("email").value;
    var aceptar = document.getElementById("aceptar").checked;

    if (nombre === "" || apellido === "" || email === "" || !aceptar) {
        alert("Por favor, complete todos los campos y acepte recibir actualizaciones.");
        return false; 
    }

    var mensaje = document.getElementById("mensaje");
    mensaje.style.display = "block";

    return false;
}

function openA7X() {
    window.open('/html/a7x.html', '_blank');
}
function openIronMaiden() {
    window.open('/html/ironmaiden.html', '_blank');
}
function openLambOfGod() {
    window.open('/html/lambofgod.html', '_blank');
}
function openMetallica() {
    window.open('/html/metallica.html', '_blank');
}
function openSlipknot() {
    window.open('/html/slipknot.html', '_blank');
}

$(document).ready(function() {
    $('#carouselPagInicio').carousel({
        interval: 3000, // ms
        pause: false
    });
});

function openLinkedin(){
    window.open('https://www.linkedin.com/in/igor-imdasilva/', '_blank');
}

function openGitHub(){
    window.open('https://github.com/igor-ims', '_blank');
}

function searchBand() {
    var input = document.getElementById('searchInput').value.toLowerCase();
    switch(input) {
      case 'avenged sevenfold':
        openA7X();
        break;
      case 'iron maiden':
        openIronMaiden();
        break;
      case 'lamb of god':
        openLambOfGod();
        break;
      case 'metallica':
        openMetallica();
        break;
      case 'slipknot':
        openSlipknot();
        break;
      default:
        alert('Banda no encontrada');
    }
    return false;
}

function openPlaylist(){
    window.open('https://open.spotify.com/playlist/6060vJkgXGjx9hysHol8Jb?si=LPoVhi11TYuZfwr9QNznDQ', '_blank');
}
