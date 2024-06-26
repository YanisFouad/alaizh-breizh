const sortBtn = document.getElementById("sort-btn");
let bookingList = document.getElementById("liste-reservation-locataire");

const SORT_LABEL = "Tri par date d'arrivée ";

const SortDir = {
    DESC: "DESC",
    ASC: "ASC"
}
let sortDir = SortDir.DESC;

sortBtn.addEventListener("click", () => {
    sortDir  = sortDir === SortDir.DESC ? SortDir.ASC : SortDir.DESC;  
    updateBookings();
    updateSortName();
}, false);

document.addEventListener("DOMContentLoaded", () => {
    const url = new URL(location);
    if(url.searchParams.get("sortDir"))
        sortDir = url.searchParams.get("sortDir");
    updateSortName();
});

async function updateBookings() {
    try {
        const formData = new FormData();
        formData.append("sortDir", sortDir);
        for(const k of ["offset", "limit", "tenant_id", "period"])
            formData.append(k, document.getElementById(k).value);

        const response = await fetch("/controllers/bookings/listeReservationsProprietaireController.php", {
            method: "POST",
            body: formData
        });

        if(!response.ok) {
            window.notify(
                "ERROR",
                "Réponse pas oK",
                true
            );
            return;
        }

        const data = await response.json();
        const bookings = data.bookings;

        const url = new URL(location);
        // save tab get
        const tab = url.searchParams.get("tab");

        bookingList.innerHTML = "";
        bookings.forEach(booking => updateBooking(booking));
        window.notify(
            "SUCCESS",
            "Liste mise à jour !",
            true
        );

        if(tab)
            url.searchParams.set("tab", tab);
        url.searchParams.set("sortDir", sortDir);
        window.history.pushState({}, null, url);
    } catch(e) {
        console.trace(e);
        window.notify(
            "ERROR",
            `Une erreur est survenue: ${e}`,
            true
        );
    }
}

function updateSortName() {
    const icon = sortBtn.querySelector(".mdi");
    const label = document.querySelector(".label");
    if(sortDir === SortDir.ASC) {
        icon.classList.remove("mdi-sort-descending");
        icon.classList.add("mdi-sort-ascending");
    } else {
        icon.classList.add("mdi-sort-descending");
        icon.classList.remove("mdi-sort-ascending");
    }
    label.textContent = SORT_LABEL + (sortDir === SortDir.ASC ? "décroissant" : "croissant");
}

function updateBooking(booking) {
    const _formatDate = date => new Date(date).toLocaleDateString("fr-FR", {
            year: "numeric",
            month: "numeric",
            day: "numeric"
        });
    const _formatPrice = price => Intl.NumberFormat("fr-FR", {style: 'currency', currency: 'EUR'}).format(price);

    bookingList.insertAdjacentHTML("beforeend", `
        <a class="non-souligne" href="/reservation?id=${booking.id_reservation}">
            <article class="liste-reservation-locataire-logement">
                <!-- Photo maison + nom maison -->
                <div>
                    <div id='img-container'>
                        <img src="${booking.photo_logement}" alt="Logement">
                    </div>
                    <h4>${booking.titre_logement}</h4>
                </div>
                        
                <!-- Description maison -->
                <div class="liste-reservation-locataire-logement-detail">
                    <div>
                        <h5 class="titreDetail">Date de réservation</h5>
                        <h5>${_formatDate(booking.date_reservation)}</h5>
                    </div>
                    <div>
                        <h5 class="titreDetail">Date d'arrivée</h5>
                        <h5>${_formatDate(booking.date_arrivee)}</h5>
                    </div>
                    <div>
                        <h5 class="titreDetail">Nombre de nuits</h5>
                        <h5>${booking.nb_nuit}</h5>
                    </div>
                    <div>
                        <h5 class="titreDetail">Prix total</h5>
                        <h5>${_formatPrice(booking.prix_total)}</h5>
                    </div>
                    <button class="primary frontoffice liste-reservation-locataire-flex-row" disabled>
                        <span class="mdi mdi-eye-outline"></span>
                        Facture
                    </button>
                </div>
            </article>
        </a>
    `);
    console.log(bookingList.innerHTML)
}