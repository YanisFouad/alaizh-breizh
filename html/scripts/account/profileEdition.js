const profileEditionButton = document.getElementById("profile-edition");

// profile picture section
const profilePicture = document.getElementById("profile-picture");
const profilePictureInput = document.getElementById("profile-picture-input");
let profilePictureFile;

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
    "complement_numero": null,
    "rue_adresse": null,
    "ville_adresse": null,
    "code_postal_adresse": null,
    "pays_adresse": null,
    "num_carte_identite": null,
    "rib_proprietaire": null
};
let editMode = false;

// fill fields variable with all associated fields
for (const inputId of Object.keys(fields)) {
    const input = document.getElementById(inputId);
    // plain text corresponds to the default text that appear    
    const associatedText = document.querySelector(`#${inputId} + h4`);

    if (input && associatedText)
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

    // profile picture profilePicture

    inputs.forEach(input => input.style.display = editMode ? "block" : "none");
    associatedTextes.forEach(text => text.style.display = editMode ? "none" : "block");

    // only for profile picture we have to do a tricky thing
    profilePicture.style.display = editMode ? "block" : "none";
    document.querySelector("article.profile>img").style.display = editMode ? "none" : "block";

    if (!editMode) {
        profileEditionButton.textContent = "Editer mon profil";
        profileEditionButton.classList.add("mdi-pencil");
        profileEditionButton.classList.remove("mdi-check");

        // then update all textes
        const firstName = displayname.inputs.firstname.value;
        const lastName = displayname.inputs.lastname.value;
        displayname.associatedText.textContent = `${lastName.toUpperCase()} ${firstName}`;

        for (const { input, associatedText } of Object.values(fields).filter(f => f)) {
            associatedText.textContent = input.value;
        }
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

    for (const input of inputs) {
        if (input.value?.trim())
            formData.append(input.id, input.value);
    }

    if (profilePictureFile)
        formData.append("profilePicture", profilePictureFile);
    try {
        const response = await fetch("/controllers/account/profileEditionController.php", {
            method: "POST",
            body: formData
        });
        const json = await response.json();
        if (json.error) {
            window.notify("ERROR", json.error, true);
        }
        window.notify("SUCCESS", "Profil mis à jour !", true);
    } catch (e) {
        console.trace(e);
        window.notify("ERROR", `Une erreur est survenue: ${e.stack}`, true);
    }
}

profilePictureInput.addEventListener("change", ({ target }) => {
    const file = target.files?.[0];
    const reader = new FileReader();
    reader.onload = event => {
        const filePath = event.target.result;
        profilePicture.style.backgroundImage = `url(${filePath})`;
        profilePicture.querySelector("span").remove();
        document.querySelector("#profile-picture-input + img").setAttribute("src", filePath);
    }
    reader.readAsDataURL(file);
    profilePictureFile = file;
}, false);

profileEditionButton.addEventListener("click", () => {
    editMode = !editMode;
    toggleFields();

    // once user has edited fields then edit the current profile
    if (!editMode)
        editProfile();
}, false);


const apiKeyInput = document.getElementById("api-key");

document.getElementById("generate-api-key").addEventListener("click", async () => {
    try {
        const formData = new FormData();
        formData.append("generateApiKey", true);
        const response = await fetch("/controllers/account/profileEditionController.php", {
            method: "POST",
            body: formData
        });

        if (!response.ok) {
            window.notify(
                "ERROR",
                "La réponse n'est pas OK",
                true
            );
            return;
        }
        const data = await response.json();

        if (data.error) {
            window.notify(
                "ERROR",
                data.error,
                true
            );
            return;
        }

        apiKeyInput.value = data.apiKey;
        apiKeyInput.setAttribute("disabled", false);
        window.notify(
            "SUCCESS",
            "Clé api généré !",
            true
        );
    } catch (e) {
        window.notify(
            "ERROR",
            `Une erreur est survenue: ${e.stack}`,
            true
        )
    }
});

document.getElementById("copy-api-key").addEventListener("click", async () => {
    try {
        await navigator.clipboard.writeText(apiKeyInput.value);
        window.notify(
            "SUCCESS",
            "Clé api copié !",
            true
        );
    } catch (e) {
        console.trace(e);
        window.notify(
            "ERROR",
            `Impossible de copier: ${e.stack}`,
            true
        );
    }
}, false);