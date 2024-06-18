
document.addEventListener('DOMContentLoaded', function () {
    var checkbox = document.querySelector('input[type="checkbox"]');
    var etatLogement = document.getElementById('etatLogement');
    var etat = true; //TODO: recuperer l'etat initial du logement 

    updateText(etat);

    checkbox.addEventListener('change', function () {
        if (checkbox.checked) {
            etat = true;
            updateText(etat);
            updateStatus(etat);
        } else {
            etat = false;
            updateText(etat);
            updateStatus(etat);
        }
    });

    function updateText(isChecked) {
        if (isChecked) {
            etatLogement.textContent = "Rendre indisponible :";
        } else {
            etatLogement.textContent = "Rendre disponible :";
        }
    }

    // Fonction pour envoyer l'état du checkbox au serveur
    function updateStatus(etat) {
        fetch('/views/backoffice/accomodations/pageDetailleeProprietaire.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'etat=' + etat
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
            })
            .catch(error => {
                console.error('Erreur de mise à jour du statut:', error);
            });
    }
});

