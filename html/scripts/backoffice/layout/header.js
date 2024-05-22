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