
function VerifyField(event){
    event.preventDefault();
    let mail = document.getElementById("mail");
    let date_naissance = document.getElementById("date_naissance");
    let mot_de_passe = document.getElementById("mot_de_passe");
    let mot_de_passe_confirm = document.getElementById("mot_de_passe_confirm");
    let error_mdp =document.getElementById("error_mdp");
    let error_mail =document.getElementById("error_mail");
    let error_date =document.getElementById("error_date");
    error_date.innerText = "";
    error_mdp.innerText = "";
    error_date.innerText = "";


    if (mot_de_passe != mot_de_passe_confirm) {
        error_mdp.innerText = "Le mot de passe ne correspond pas"
    }

}


document.getElementById('photo_profil').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        const img = document.getElementById('profile-image');
        img.src = e.target.result;
        img.style.display = 'block'; // Show the image
    }

    if (file) {
        reader.readAsDataURL(file);
    }
});