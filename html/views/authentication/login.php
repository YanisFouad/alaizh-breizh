<div role="dialog" class="modal">
    <div class="center">
        <h3>Connexeion/Inscription</h3>
        <button class="close">&times;</button>

        <img src="../../assets/images/logo/logo-alhaiz-breizh-fullsize.svg" alt="logo fullsize" />

        <!--<div role="dialog" class="mdi mdi-information error-message">
            impossible de se connecter
        </div>-->

        <form action="#" onsubmit="handleLogin">
            <div class="form-field">
                <label class="required">Adresse mail</label>
                <input type="email" />
            </div>
            <div class="form-field">
                <label class="required">Mot de passe</label>
                <input type="password" />
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

    function handleLogin() {

    }

    

</script>