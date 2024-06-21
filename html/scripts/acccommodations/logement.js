
/***Popup Devis***/
const modal = document.getElementById("modal");
const btn = document.getElementById("boutonDevis");
const closeModal = document.querySelector(".close");
/***Plus/moins nombre de voyageurs***/
const moins = document.getElementById("moins");
const valeurAffichee = document.getElementById("valeurVoyageurs");
const plus = document.getElementById("plus");
const nbPersonneMax = document.getElementById("nbPersonneMax").innerHTML;


var valeur = 1;

btn.onclick = function () {
    modal.style.display = "block";
}

closeModal.onclick = function () {

    modal.style.display = "none";
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function miseAJourValeurAffichee() {
    valeurAffichee.textContent = valeur;
    moins.disabled = valeur <= 0;
    plus.disabled = valeur == nbPersonneMax;
}

moins.addEventListener('click', () => {
    valeur--;
    miseAJourValeurAffichee();
    document.getElementById("valeurVoyageurs").innerHTML = valeur
    document.getElementById("taxeSejour").innerHTML = "1 x " + valeur + " voyageurs x 3 nuits";

});

plus.addEventListener('click', () => {
    valeur++;
    miseAJourValeurAffichee();
    document.getElementById("valeurVoyageurs").innerHTML = valeur
    document.getElementById("taxeSejour").innerHTML = "1 x " + valeur + " voyageurs x 3 nuits";
}
);



miseAJourValeurAffichee();

