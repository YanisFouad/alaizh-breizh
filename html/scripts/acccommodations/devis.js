async function handleDevis() {
    try {
        const formData = new FormData();
        formData.append("nb_voyageurs", 3);
        formData.append("total_ati", 300);
        formData.append("accommodationId", document.getElementById("id_logement")?.value);
        await fetch(`/controllers/accommodations/devis.php`, {
            method: "POST",
            body: formData
        });
        window.location.href = "/finaliser-ma-reservation"
    } catch(e) {
        console.error(e);
    }
}