
document.addEventListener('DOMContentLoaded', function () {
    var etatLogement = document.getElementById('etatLogement');
    var etatActuel = document.getElementById('logementActuel');
    var etat = etatActuel.getAttribute('data-php-variable');
    updateText(etat);

    checkbox.addEventListener('change', function () {
        if (etat) {
            updateText(etat);
        } else {
            updateText(etat);
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
        fetch('/controllers/backoffice/accommodations/DispoLogement.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'status=' + etat
        })
            .then(response => response.text())
            .then(response => {
                console.log(response);
            })
            .catch(error => {
                console.error('Erreur de mise à jour du statut:', error);
            });
    }
});

