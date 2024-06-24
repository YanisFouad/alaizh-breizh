document.addEventListener("DOMContentLoaded", function () {
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

  const idLogement = new URLSearchParams(window.location.search).get("id_logement");
  const btnDate = document.getElementById("boutonDate");
  const minDate = new Date();
  minDate.setDate(minDate.getDate() + 1);
  let nbMinReservation = 4;
  let delaisReservation = 4;
  const dateDepart = document.getElementById("date-depart");
  const dateArrivee = document.getElementById("date-arrivee");

  flatpickr.l10ns.customFr = Object.assign({}, flatpickr.l10ns.fr, {
    rangeSeparator: " - ",
  });

  const fp = flatpickr(btnDate, {
    enableTime: false,
    locale: flatpickr.l10ns.customFr, // Utiliser la locale personnalisée
    dateFormat: "d/m/Y",
    mode: "range",
    minDate: minDate,
    onClose: function (selectedDates, dateStr, instance) {},
    onChange: function (selectedDates, dateStr, instance) {
    //   errorMessage.textContent = "";
      if (selectedDates.length === 2) {
        let startDate = new Date(selectedDates[0]).fp_incr(1);
        let endDate = new Date(selectedDates[1]).fp_incr(1);
        let minEndDate = new Date(
          startDate.getFullYear(),
          startDate.getMonth(),
          startDate.getDate() + (nbMinReservation - 1)
        );

        if (endDate < minEndDate) {
          instance.clear();
        //   errorMessage.textContent = `La sélection doit être d'au moins ${nbMinReservation} jours.`;
        } else {
          // Réinitialiser les contraintes min/max de la plage de dates
          instance.set("minDate", minDate);
          instance.set("maxDate", null);
        }
      }
    },
  });
  getBookingsDate(idLogement, fp);


  btnDate.addEventListener("click", () => {
    fp.open();
  });

  btnDate.addEventListener("change", (e) => {
    if (e.target.value) {
      e.target.innerText = e.target.value;
      const selectedDates = fp.selectedDates;
      if(selectedDates.length === 2) {
        const selectedStartDate = new Date(selectedDates[0].toISOString().split('T')[0]);
        selectedStartDate.setDate(selectedStartDate.getDate() + 1);
        dateArrivee.innerText = selectedStartDate.toLocaleDateString('fr-fr', {year:"numeric", month:"long", day:"numeric"});

        const selectedEndDate = new Date(selectedDates[1].toISOString().split('T')[0]);
        selectedEndDate.setDate(selectedEndDate.getDate() + 1);
        dateDepart.innerText = selectedEndDate.toLocaleDateString('fr-fr', {year:"numeric", month:"long", day:"numeric"});
        console.log(selectedStartDate, selectedEndDate);
        updateNbNuits(selectedStartDate, selectedEndDate);
      }
    } else {
      e.target.innerText = "Choisir une date";
    }
  });

  btn.onclick = function () {
    modal.style.display = "block";
  };

  closeModal.onclick = function () {
    modal.style.display = "none";
  };

  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };

  function miseAJourValeurAffichee() {
    valeurAffichee.textContent = valeur;
    moins.disabled = valeur <= 0;
    plus.disabled = valeur == nbPersonneMax;
  }

  moins.addEventListener("click", () => {
    valeur--;
    miseAJourValeurAffichee();
    document.getElementById("valeurVoyageurs").innerHTML = valeur;
    document.getElementById("taxeSejour").innerHTML =
      "1 x " + valeur + " voyageurs x 3 nuits";
  });

  plus.addEventListener("click", () => {
    valeur++;
    miseAJourValeurAffichee();
    document.getElementById("valeurVoyageurs").innerHTML = valeur;
    document.getElementById("taxeSejour").innerHTML =
      "1 x " + valeur + " voyageurs x 3 nuits";
  });

  miseAJourValeurAffichee();
});

function updateNbNuits(startDate, endDate) {
    const nbNuits = document.getElementById("nombreNuits");
    const timeDifference = endDate.getTime() - startDate.getTime();
    const dayDiff = timeDifference / (1000 * 3600 * 24);

    nbNuits.innerText = dayDiff;
    updateTotal(dayDiff);
}

function updateTotal(nbNuits) {
  const prixNuit = document.getElementById("prix-nuit").innerText;
  const totalNuits = document.getElementById("nb-nuits-total");

  totalNuits.innerText = nbNuits;
  
  const total = document.getElementById("total");
  let totalRes = (parseInt(nbNuits) * parseInt(prixNuit)).toFixed(2);
  totalRes = totalRes.replace(".", ",");
  total.innerText = totalRes;
}

async function getBookingsDate(idLogement, fp) {
  await fetch(`/controllers/accommodations/devisBookingDates.php?id=${idLogement}`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  }).then((response) => {
    if (response.ok) {
      return response.json();
    }
  }).then((data) => {
    setBookingsDate(data, fp);
  });
}

function setBookingsDate(dates, fp) {
  for(const [index, date] of Object.entries(dates)) {
    const dateArrivee = new Date(date.date_arrivee);
    const dateDepart = new Date(date.date_depart);
    fp.config["disable"].push({from: dateArrivee, to: dateDepart});
  }
}