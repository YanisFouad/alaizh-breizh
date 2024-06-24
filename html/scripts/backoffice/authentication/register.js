// such of quick and dirty code
// function VerifyField(event){
//     event.preventDefault();
//     let form = document.getElementById("formProfil");
//     let mail = document.getElementById("mail").value;
//     let date_naissance = document.getElementById("date_naissance").value;
//     let mot_de_passe = document.getElementById("mot_de_passe").value;
//     let mot_de_passe_confirm = document.getElementById("mot_de_passe_confirm").value;
//     let error_mdp =document.getElementById("error_mdp")
//     let error_mail =document.getElementById("error_mail");
//     let error_date =document.getElementById("error_date");
//     error_date.innerText = "";
//     error_mdp.innerText = "";
//     error_mail.innerText = "";
//     correct = true;

//     if (mot_de_passe != mot_de_passe_confirm) {
//         error_mdp.innerText = "Le mot de passe ne correspond pas";
//         correct = false;
//     }
//     if (!validateEmail(mail)) {
//         error_mail.innerText = "L'email est incorrect"
//         correct = false;

//     }
//     if(!isAdult(date_naissance)){
//         error_date.innerText="Vous devez être majeur pour créer un compte"
//         correct = false;

//     }
//     if(correct){
//         form.submit();
//     }

// }

// function validateEmail(email) {
//     // Expression régulière pour valider le format de l'email
//     const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
//     // Teste l'email contre l'expression régulière
//     return emailRegex.test(email);
// }

// function isAdult(birthDateString) {
//     // Convertit la chaîne de date de naissance en objet Date
//     const birthDate = new Date(birthDateString);
    
//     // Obtient la date actuelle
//     const today = new Date();
    
//     // Calcule l'âge
//     let age = today.getFullYear() - birthDate.getFullYear();
//     const monthDiff = today.getMonth() - birthDate.getMonth();
    
//     // Ajuste l'âge si l'anniversaire n'est pas encore passé cette année
//     if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
//         age--;
//     }
    
//     // Vérifie si la personne est majeure (18 ans ou plus)
//     return age >= 18;
// }

async function handleForm(event) {
    event.preventDefault();
    try {
        const formData = new FormData(document.getElementById("register-form"));

        const response = await fetch("/controllers/backoffice/account/registerController.php", {
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
            // @todo manage fields
            return;
        }

        window.location.href = "/backoffice?notification-type=SUCCESS&notification-message=Vous êtes bien inscrit !";
    } catch(e) {
        console.trace(e);
        window.notify(
            "ERROR",
            `Une erreur est survenue: ${e.stack}`,
            true
        );
    }
}

// udpate photo section
document.addEventListener('DOMContentLoaded', function () {
    const photoInput = document.getElementById("photo");
    const profileImage = document.querySelector("[for=\"photo\"]>img");

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