
/***Popup Devis***/
const modal = document.getElementById("modal");
const btn = document.getElementById("boutonDevis");
const closeModal = document.querySelector(".close");

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

/***Plus/moins nombre de voyageurs ***/
const moins = document.getElementById("moins");
const valeurAffichee = document.getElementById("valeurVoyageurs");
const plus = document.getElementById("plus");

let valeur = 1;
function miseAJourValeurAffichee() {
    valeurAffichee.textContent = valeur;
    moins.disabled = valeur <= 0;
}

if (valeur == 1) {

}
moins.addEventListener('click', () => {
    valeur--;
    miseAJourValeurAffichee();
});

plus.addEventListener('click', () => {
    valeur++;
    miseAJourValeurAffichee();
});

miseAJourValeurAffichee();