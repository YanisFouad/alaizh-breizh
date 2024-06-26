const modalValidations = document.getElementById('modalValidation');
const btnCheckbox = document.getElementById("boutonOnOff");
const btnOui = document.getElementById('boutonOui');
const btnNon = document.getElementById('boutonNon');
const closeModal = document.querySelector('.close');
const urlParams = new URLSearchParams(window.location.search);
const idLogement = urlParams.get('id_logement');
const switchOnOff = document.querySelector('.switch');
const etatLogement = document.getElementById('etatLogement');
const messageValidation = document.getElementById('dispo');

updateText();

function messagePopUp() {
    console.log(btnCheckbox.checked);
    if (!btnCheckbox.checked) {
        messageValidation.textContent = "Voulez-vous vraiment rendre ce logement indisponible ?";
    }
    else {
        messageValidation.textContent = "Voulez-vous vraiment rendre ce logement disponible ?";
    }
}

//met a jour le texte à côté du bouton switch
function updateText() {
    if (btnCheckbox.checked) {
        console.log("rentre dans le if updateText");
        etatLogement.textContent = "En ligne";
    } else {
        etatLogement.textContent = "Hors ligne";
    }
}

// Fonction pour envoyer l'état du checkbox au serveur
function updateStatus(id) {
    const form = new FormData();
    form.append("idLogement", id);
    fetch('/controllers/backoffice/accommodations/DispoLogementController.php', {
        method: 'POST',
        body: form
    })
        .then(response => response.text())
        .then(response => {
            console.log(response);
            btnCheckbox.checked = !btnCheckbox.checked;
            updateText();
        })
        .catch(error => {
            console.error('Erreur de mise à jour du statut:', error);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    btnCheckbox.onclick = function (e) {
        e.preventDefault();
        modalValidations.style.display = "block";
        messagePopUp();
    }
});


btnOui.onclick = function () {
    updateStatus(idLogement);
    modalValidations.style.display = "none";
};

btnNon.onclick = function () {
    modalValidations.style.display = "none";
};

closeModal.onclick = function () {
    modalValidations.style.display = "none";
}

window.onclick = function (event) {
    if (event.target == modalValidations) {
        modalValidations.style.display = "none";
    }
}

