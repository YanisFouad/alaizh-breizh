console.log("connecting to");

const errorMessage = document.querySelector(".error-message");
const allInputsSelector = "form input:not(#prix_ht_logement), form select, form textarea, form #photo_logement";
const sections = document.querySelectorAll("form>section");
let activeSectionNumber = 0;
// active section by default is the first
let activeSection;

// I know it's a but weird but I need to fields with a sections
const FieldSectionMapper = {
    0: [
        "photo_logement", 
        "titre_logement", 
        "accroche_logement", 
        "description_logement", 
        "categorie_logement",
        "type_logement",
        "classe_energitique",
        "surface_logement",
        "max_personne_logement",
        "nb_lits_simples_logement",
        "nb_lits_doubles_logement"
    ],
    1: [
        "pays_adresse",
        "rue_adresse",
        "complement_adresse",
        "ville_adresse",
        "code_postal_adresse",
        "numero",
        "gps_latitude_logement",
        "gps_longitude_logement"
    ],
    3: [
        "prix_ht_logement",
        "duree_minimal_reservation",
        "delais_minimum_reservation",
        "delais_prevenance"
    ]
}

async function handleForm(event) {
    event.preventDefault();
    // if(activeSectionNumber !== sections.length-1)
    //     return;
    try {
        const formData = new FormData(event.target);
        formData.append("id_logement", new URLSearchParams(window.location.search).get("id_logement"))
        // remove activities & distances that hasn't been checked
        for(const activity of Array.from(document.querySelectorAll(".activities>input"))) {
            if(activity && !activity.checked) {
                formData.delete(activity.id);
                const activityName = activity.id.replace("activity_", "");
                formData.delete(`distance_for_${activityName}`);
            }
        }
        
        //modif ligne
        const response = await fetch(`/controllers/backoffice/accommodations/modificationAccommodationController.php`, {
            method: "POST",
            body: formData
        });
        console.log(response);
        if(!response.ok)
            return setError({message: "Impossible d'ajouter le logement"});

        const data = await response.json();
        if(data && data.error) {
            let sectionId;
            if(data.fields) {
                sectionId = Object.entries(FieldSectionMapper).find(([_, v]) => {
                    return v.includes(data.fields[0])
                })?.[0];
            }
            return setError({
                message: data.error,
                fieldIds: data?.fields,
                section: parseInt(sectionId) ?? null
             });
        }
        // once we have added the accommodation we can go to the home
        window.location.href = "/backoffice/logement";
    } catch(e) {
        console.error(e);
        setError({message: "Impossible de modifier le logement: " + e});
    }
}

function setError({message, section = null, fieldIds}) {
    errorMessage.style.display = !message ? "none" : "block";
    errorMessage.textContent = message;

    if(message) {
        if(fieldIds) {
            Array.from(document.querySelectorAll(allInputsSelector)).forEach(fId => document.getElementById(fId)?.classList?.remove("error"));
            fieldIds.forEach(fId => {
                const field = document.getElementById(fId);
                field?.parentElement?.classList?.add("error");
            });
        }
        // if(section !== null && section !== undefined) 
        //     setActiveSection(section)
    }
}

// /* SECTION ACTIONS */
// function setActiveSection(sectionNumber) {
//     activeSection && activeSection.classList.remove("active");
//     const section = sections[sectionNumber];
//     if(!section)
//         throw new Error("Section not found");
//     section.scrollIntoView({ behavior: "smooth" });
//     section.classList.add("active");
//     activeSection = section;
//     activeSectionNumber = sectionNumber;
// }
// function nextSection(index) {
//    if(activeSectionNumber <= sections.length  && activeSectionNumber === index) {
//         setActiveSection(activeSectionNumber+1);
//    }
// }
// function previousSection(index) {
//     if(activeSectionNumber > 0 && activeSectionNumber === index) {
//         setActiveSection(activeSectionNumber-1);
//     }
// }
/* SECTION ACTIONS */

/* SECTION SWITCHER */
// function handleSectionChange(event) {

// }
function loadSectionSwitchers() {
    Array.from(sections).forEach((section, i) => {
        const nextButton = section.querySelector("footer>button.next");
        const previousButton = section.querySelector("footer>button.previous");

        if(previousButton) {
            previousButton.onclick = () => previousSection(i);
        }
        if(nextButton) {
            nextButton.onclick = () => nextSection(i);
        } 
    })
}

/* SECTION EVENTS */
//window.addEventListener("scroll", handleSectionChange);
document.addEventListener("DOMContentLoaded", async () => {
    // setActiveSection(activeSectionNumber, false);
    loadSectionSwitchers();

    for(const input of Array.from(document.querySelectorAll(allInputsSelector))) {
        input.oninput = ({target}) => {
            target.parentElement.classList.remove("error");
        }
    }

    for(const acitvity of Array.from(document.querySelectorAll(".activities>input"))) {
        const distSelector = acitvity.parentElement.querySelector("select");
        if(acitvity.checked) {
            if(distSelector)
                distSelector.style.display = !acitvity.checked ? "none" : "block";
        }
        acitvity.onchange = ({target}) => {
            if(distSelector)
                distSelector.style.display = !target.checked ? "none" : "block";
        }
    }

    let photoLogement = document.querySelector(`[for="photo_logement"]`);
    photoLogement.style.backgroundImage = `url(${photoLogement.getAttribute("data-image")})`;

    //document.getElementById('photo_logement').src = window.URL.createObjectURL(this.files[0]);
    profilePictureInput = document.getElementById("photo_logement")
    profilePictureInput.addEventListener("change", ({target}) => {
        const photoModif = document.getElementById('photo_logement').dataset.image;
        const photo = target.files?.[0];
        console.log(photo);
        const reader = new FileReader();
        reader.onload = event => {
            const filePath = event.target.result;
            //picturePath = `url(${filePath})`;
            //document.querySelector("#profile-picture-input + img").setAttribute("src", filePath);
            document.getElementById('image_logement').style.background = `url(${filePath})`;
        }
        reader.readAsDataURL(photo);
        profilePictureFile = photo;
    },false);
    //     document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
        
    //     // const [file] = target.files;
    //     // if (file) {
    //     //     const label = document.getElementById('image_logement');
    //     //     label.style.backgroundImage = `url(${URL.createObjectURL(file)})`;
    //     // }
    // };
    // Load initial image if available


        //const uploadLabel = target.parentElement.querySelector("label");
        //const uploadIcon = uploadLabel.querySelector(".mdi");
        // uploadIcon?.classList?.remove("mdi-image-plus");
        //uploadIcon?.classList?.add("mdi-check");
        //uploadLabel?.classList.add("uploaded");

    // photoLogement.onchange = ({target}) => {
    //     const uploadLabel = target.parentElement.querySelector("label");
    // }
});

window.onload = () => {
    const label = document.getElementById('image_logement');
    const imageUrl = label.dataset.image;
    if (imageUrl) {
        label.style.backgroundImage = `url(${imageUrl})`;
    }
};


/* PRICE CALCUL SECTION */
document.getElementById("prix_ht_logement").oninput = ({target}) => {
    price = parseFloat(target.value?.trim() ? target.value : 0);
    price=price+(price*.10);
    const priceFormat = Intl.NumberFormat("fr-FR", {style: 'currency', currency: 'EUR'}).format(price);
    document.getElementById("price-ati").textContent = priceFormat;
}