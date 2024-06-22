const profileEditionButton = document.getElementById("profile-edition");

// displayname is a special field :)
const displayname = {
    inputs: {
        lastname: document.querySelector("#nom"),
        firstname: document.querySelector("#prenom")
    },
    associatedText: document.querySelector("#displayname + h3")
};
const fields = {
    "mail": null,
    "civilite": null,
    "date_naissance": null,
    "telephone": null,
    "numero": null,
    "complete_numer": null,
    "rue_adresse": null,
    "ville_adresse": null,
    "code_postal_adresse": null,
    "pays_adresse": null,
    "num_carte_identite": null,
    "rib_proprietaire": null
};
let editMode = false;

// fill fields variable with all associated fields
for(const inputId of Object.keys(fields)) {
    const input = document.getElementById(inputId);
    // plain text corresponds to the default text that appear    
    const associatedText = document.querySelector(`#${inputId} + h4`);

    if(input && associatedText)
        fields[inputId] = { input, associatedText }
}

function toggleFields() {
    const inputs = [
        ...Object.values(displayname.inputs),
        ...Object.values(fields).map(f => f?.input)
    ].filter(i => i);
    const associatedTextes = [
        displayname.associatedText,
        ...Object.values(fields).map(f => f?.associatedText)
    ].filter(t => t);

    inputs.forEach(input => input.style.display = editMode ? "block" : "none");
    associatedTextes.forEach(text => text.style.display = editMode ? "none" : "block");

    if(!editMode) {
        profileEditionButton.textContent = "Editer mon profil";
        profileEditionButton.classList.add("mdi-pencil");
        profileEditionButton.classList.remove("mdi-check");
    } else {
        profileEditionButton.textContent = "Valider les modifications";
        profileEditionButton.classList.remove("mdi-pencil");
        profileEditionButton.classList.add("mdi-check");
    }
}

async function editProfile() {
    const inputs = [...Object.values(displayname.inputs), ...Object.values(fields).map(f => f?.input)].filter(i => i);
    const formData = new FormData();
    formData.append("editProfile", true);

    for(const input of inputs) {
        if(input.value?.trim())
            formData.append(input.id, input.value);
    }
    try {
        const response = await fetch("/controllers/account/profileController.php", {
            method: "POST",
            body: formData
        });
        const json = await response.json();
        if(json.error) {
            window.notify("ERROR", json.error, true);
        }
        window.notify("SUCCESS", "Profil mit à jour !", true);
    } catch(e) {
        console.trace(e);
        window.notify("ERROR", `Une erreur est survenue: ${e.stack}`, true);
    }
    //window.notify("SUCCESS", "Profil mit à jour", true);
}

profileEditionButton.addEventListener("click", () => {
    editMode = !editMode;
    toggleFields();

    // once user has edited fields then edit the current profile
    if(!editMode)
        editProfile();
}, false);