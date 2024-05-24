const authenticationElement = document.getElementById("authentication-modal");
const authenticationErrorElement = authenticationElement.querySelector(".error-message");

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
        window.location.reload();
        closeLoginModal();
    } catch(e) {
        setErrorMessage(`Erreur interne a eu lieu: ${e}`);
        console.error(e);
    }
}