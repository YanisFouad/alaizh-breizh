const AuthenticationType = {
    TENANT: {
        name: "tenant",
        title: "Locataire",
        className: "tenant-authentication",
        buttonClassNames: ["primary"]
    },
    OWNER: {
        name: "owner",
        title: "Propriétaire",
        className: "owner-authentication",
        buttonClassNames: ["primary", "backoffice"]
    }
}

const authenticationElement = document.getElementById("authentication-modal");
const authenticationErrorElement = authenticationElement.querySelector(".error-message");
let authenticationTitleElement = authenticationElement.querySelector(".title");

let authenticationType = null;

function changeAuthenticationType(newType = AuthenticationType.TENANT) {  
    authenticationType = newType;
    authenticationTitleElement.textContent = newType.title;
    authenticationElement.classList.remove(
        ...Object.values(AuthenticationType).map(({className}) => className)
    );
    authenticationElement.classList.add(newType.className);

    authenticationElement.querySelectorAll("nav>button")
        .forEach(e => e.classList.remove("active"));
    authenticationElement.querySelector(`[data-account-type="${newType.name}"]`)
        .classList.add("active");
    const buttons = authenticationElement.querySelectorAll("form>button");
    buttons.forEach(b => {
        b.classList.remove(
            ...Object.values(AuthenticationType).map(({buttonClassNames}) => buttonClassNames).flat()
        )
        b.classList.add(...newType.buttonClassNames);
    })
}

function setErrorMessage(message) {
    if(!message) {
        authenticationErrorElement.style.display = "none";
    } else {
        authenticationErrorElement.style.display = "block";
        authenticationErrorElement.textContent = message;
    }
}

function closeLoginModal() {
    authenticationElement.style.display = "none";
}

function openLoginModal() {
    if(!authenticationType)
        changeAuthenticationType(AuthenticationType.TENANT);
    authenticationElement.style.display = "block";
}

async function handleLogin(event) {
    event.preventDefault();

    try {
        const formData = new FormData(event.target);
        formData.set("authType", authenticationType.name);
        const response = await fetch(`/controllers/authentication/loginController.php`, {
            method: "POST",
            body: formData
        });
        if(!response.ok) {
            setErrorMessage("Connexion impossible");
            console.error(response.status)
            return;
        }
        const data = await response.json();
        if(data.error) {
            setErrorMessage(data.error);
            return;
        }

        // redirect the user to the back office if he has been connected has owner
        if(authenticationType === AuthenticationType.OWNER) {
            window.location.href = "/backoffice/";
            return;
        }
        // then reload the window to take in count the user session
        window.location.reload();
    } catch(e) {
        setErrorMessage(`Erreur interne a eu lieu: ${e}`);
        console.error(e);
    }
}

for(const type of Object.values(AuthenticationType)) {
    document.querySelector(`[data-account-type="${type.name}"]`).onclick = () => {
        changeAuthenticationType(type);
    }
}

window.addEventListener("DOMContentLoaded", () => {
    const hash = window.location.hash;
    if(hash) {
        const type = hash.split("=").pop().toUpperCase();
        changeAuthenticationType(AuthenticationType[type]);
        openLoginModal();
    }
});