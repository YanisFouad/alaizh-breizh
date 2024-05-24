let menuBurger = document.getElementById("open-burger-menu");
let openButton = document.getElementById("open-burger-button");
let closeButton = document.getElementById("close-burger-button");

function openBurgerMenu(open) {
    if (open) {
        menuBurger.classList.remove("hidden");
        menuBurger.classList.add("displayed");
        openButton.classList.remove("displayed");
        openButton.classList.add("hidden");
        closeButton.classList.remove("hidden");
        closeButton.classList.add("displayed");
    } else {
        menuBurger.classList.add("hidden");
        menuBurger.classList.remove("displayed");
        openButton.classList.add("displayed");
        openButton.classList.remove("hidden");
        closeButton.classList.add("hidden");
        closeButton.classList.remove("displayed");
    }
}