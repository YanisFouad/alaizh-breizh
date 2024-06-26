const btn = document.querySelector(".finalize");
const checkbox = document.getElementById("cgv");

btn.disabled = true;

checkbox.addEventListener("change", () => {
    if (checkbox.checked) {
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
});

