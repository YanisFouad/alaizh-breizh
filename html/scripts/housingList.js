function toggleFilterMenu() {
    document.getElementById("filter-container").classList.toggle("hidden");
    document.getElementById("filter-container").classList.toggle("displayed");
 }

 function switchOpenClose(menuId, chevronDownId, chevronUpId) {
    let menu = document.getElementById(menuId);
    let chevronDown = document.getElementById(chevronDownId);
    let chevronUp = document.getElementById(chevronUpId);

    menu.classList.toggle("hidden");
    menu.classList.toggle("displayed");
    chevronDown.classList.toggle("hidden");
    chevronDown.classList.toggle("displayed");
    chevronUp.classList.toggle("hidden");
    chevronUp.classList.toggle("displayed");
 }