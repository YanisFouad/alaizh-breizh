<?php
    /*
    if(!UserSession::isConnected()) {
        header("Location: /backoffice/connexion");
        exit;
    }*/
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/materialdesignicons.min.css">

    <title>Alhaiz Breizh - Backoffice</title>
</head>
<body>
    <header id="backoffice">
        <a href="/backoffice/">
            <img alt="AlhaizBreizh's logo" src="/assets/images/logo/logo-alhaiz-breizh-fullsize.svg" />
        </a>
        <nav>
            <ul>
                <li>
                    <a href="/backoffice/">
                        Mes logements
                    </a>
                </li>
            </ul>
        </nav>
        <aside>
            <a href="/backoffice/reservations">
                <button>
                    Mes réservations
                    <span class="mdi mdi-arrow-top-right"></span>
                </button>
            </a>
            <div id="profile">
                <div class="infos">
                    <img src="/assets/images/default-user.jpg" alt="Lucas Aupry's picture" />
                    <span>Lucas Aupry</span>
                    <span class="mdi mdi-chevron-up"></span>
                </div>

                <div class="dropdown">
                    <ul>
                        <li>
                            <form method="POST" action="/controllers/authentication/disconnectController.php?redirecTo=/backoffice/">
                                <button type="submit">Déconnexion</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <span class="owner">Propriétaire</span>
        </aside>

        <script type="text/javascript">
            const profileElement = document.getElementById("profile");
            let profileInfosIcon = profileElement?.querySelector(".infos>span.mdi");
            let profileDropdown = profileElement?.querySelector(".dropdown");

            function toggleDropdown(display) {
                if(!display) {
                    profileDropdown.style.display = "none";
                    profileInfosIcon.classList.add("mdi-chevron-up");
                    profileInfosIcon.classList.remove("mdi-chevron-down");
                } else {
                    profileDropdown.style.display = "block";
                    profileInfosIcon.classList.remove("mdi-chevron-up");
                    profileInfosIcon.classList.add("mdi-chevron-down");
                }
            }
            
            profileElement?.querySelector(".infos")?.addEventListener("click", () =>
                toggleDropdown(profileDropdown.style.display !== "block")
            , false);

            window.addEventListener("click", ({target}) => {
                let parent = target.parentElement;
                do {
                    parent = parent?.parentElement;
                } while(parent && parent.id !== "profile");
                if(!parent)
                    toggleDropdown(false);
            }, false);
        </script>
    </header>
    <main>