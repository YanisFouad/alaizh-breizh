const modal = document.getElementById('modalValidation');
const btnCheckbox = document.getElementById("boutonOnOff");
const btnOui = document.getElementById('boutonOui');
const btnNon = document.getElementById('boutonNon');
const closeModal = document.querySelector('.close');
const urlParams = new URLSearchParams(window.location.search);
const idLogement = urlParams.get('id_logement');
const switchOnOff = document.querySelector('.switch');
const etat = switchOnOff.getAttribute('data-php-variable');

document.addEventListener('DOMContentLoaded', function () {
    var etatLogement = document.getElementById('etatLogement');
    var messageValidation = document.getElementById('dispo');

    updateText(etat);

    btnCheckbox.onclick = function () {
        modal.style.display = "block";
        messagePopUp(etat);
    }

    function messagePopUp(isChecked) {
        if (isChecked) {
            messageValidation.textContent = "Voulez-vous vraiment rendre ce logement indisponible ?";
        }
        else {
            messageValidation.textContent = "Voulez-vous vraiment rendre ce logement disponible ?";
        }
    }

    //met a jour le texte à côté du bouton switch
    function updateText(isChecked) {
        if (isChecked) {
            etatLogement.textContent = "Rendre indisponible :";
        } else {
            etatLogement.textContent = "Rendre disponible :";
        }
    }

});

// Fonction pour envoyer l'état du checkbox au serveur
function updateStatus(id) {
    fetch('/controllers/backoffice/accommodations/DispoLogementController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'idLogement=' + id
    })
        .then(response => response.text())
        .then(response => {
            console.log(response);
        })
        .catch(error => {
            console.error('Erreur de mise à jour du statut:', error);
        });
}
btnOui.onclick = function () {
    console.log(idLogement);
    updateStatus(idLogement);
    modal.style.display = "none";
};

btnNon.onclick = function () {
    modal.style.display = "none";
};
closeModal.onclick = function () {
    modal.style.display = "none";
    if (etat) {
        boutonOnOff.setAttribute("checked", "checked");
    }
    else {
        boutonOnOff.setAttribute("checked");
    }
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

