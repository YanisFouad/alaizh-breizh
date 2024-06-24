async function handleForm(event) {
    event.preventDefault();
    try {
        const formData = new FormData(document.getElementById("formProfil"));
        const response = await fetch("/controllers/account/profileCreationController.php", {
            method: "POST",
            body: formData
        });

        if(!response.ok) {
            window.notify("ERROR", "La réponse n'est pas OK", true); 
            return;
        }

        const data = await response.json();

        if(data.error) {
            window.notify("ERROR", data.error, true);
            return;
        }

        window.location.href = "/?notification-type=SUCCESS&notification-message=Vous êtes maintenant incrit !";
    } catch(e) {
        console.trace(e);
        window.notify(
            "ERROR",
            `Une erreur est survenue: ${e.stack}`,
            true
        );
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const photoInput = document.getElementById("photo_profil");
    const profileImage = document.querySelector("[for=\"photo_profil\"]>img");

    // Met à jour l'image de profil quand un fichier est sélectionné
    photoInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = e => {
            profileImage.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }, false);
});