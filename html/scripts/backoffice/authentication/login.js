const authenticationErrorElement = document.querySelector(".error-message");

function setErrorMessage(message) {
    if(!message) {
        authenticationErrorElement.style.display = "none";
    } else {
        authenticationErrorElement.style.display = "block";
        authenticationErrorElement.textContent = message;
    }
}

async function handleLogin(event) {
    event.preventDefault();

    try {
        const formData = new FormData(event.target);
        formData.set("authType", "owner");
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
        window.location.reload();
    } catch(e) {
        setErrorMessage(`Erreur interne a eu lieu: ${e}`);
        console.error(e);
    }
}