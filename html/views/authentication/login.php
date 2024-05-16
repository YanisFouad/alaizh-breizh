<div role="dialog" class="modal" id="authentication-modal">
    <div class="center">
        <h3>Connexion/Inscription</h3>
        <button onclick="closeLoginModal()" class="close">&times;</button>

        <img src="../../assets/images/logo/logo-alhaiz-breizh-fullsize.svg" alt="logo fullsize" />

        <div role="dialog" id="error-message" class="mdi mdi-information error-message"></div>

        <form action="#" onsubmit="handleLogin(event)">
            <div class="form-field">
                <label class="required">Adresse mail</label>
                <input name="email" type="email" required />
            </div>
            <div class="form-field">
                <label class="required">Mot de passe</label>
                <input name="password" type="password" required />
            </div>

            <a href="#" class="forgot-password">
                Mot de passe oublié ?
            </a>

            <button type="submit" class="primary">Se connecter</button>

            <h5 class="another-choice">
                <span class="text">OU</span>
                <span class="line"></span>
            </h5>

            <button type="button" class="primary">
                S'inscrire
            </button>
        </form>
    </div>
</div>

<script>

    const authenticationModalElement = document.getElementById("authentication-modal");
    const errorMessageElement = document.getElementById("error-message");

    function setErrorMessage(message) {
        if(!message) {
            errorMessageElement.style.display = "none";
        } else {
            errorMessageElement.style.display = "block";
            errorMessageElement.textContent = message;
        }
    }

    function closeLoginModal() {
        authenticationModalElement.style.display = "none";
    }

    function openLoginModal() {
        authenticationModalElement.style.display = "block";
    }

    async function handleLogin(event) {
        event.preventDefault();

        try {
            const formData = new FormData(event.target);
            const response = await fetch("/controllers/authentication/loginController.php", {
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
</script>