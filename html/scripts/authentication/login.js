const authenticationElement = document.getElementById("authentication-modal");
const authenticationErrorElement = authenticationElement.querySelector(".error-message");
let redirectToUri;

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
    authenticationElement.style.display = "block";
}

// just check parameters
window.addEventListener("DOMContentLoaded", () => {
    const hash = location.hash;
    if(hash && hash === "#connection") {
        const url = new URL(location);
        const params = url.searchParams;
        if(params.has("redirectTo"))
            redirectToUri = "/"+params.get("redirectTo");
        openLoginModal();
        url.hash = "";
        history.pushState({}, "", url);
    }
})

async function handleLogin(event) {
    event.preventDefault();

    try {
        const formData = new FormData(event.target);
        formData.set("authType", "tenant");
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

        // then reload the window to take in count the user session
        window.notify("SUCCESS", "Connecté !");
        // if we have a parameter "redirectTo" then redirect to the page otherwise just reload the current page
        if(redirectToUri)
            window.location.href = redirectToUri;
        else 
            window.location.reload();
    } catch(e) {
        setErrorMessage(`Erreur interne a eu lieu: ${e}`);
        console.error(e);
    }
}