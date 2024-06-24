document.addEventListener("DOMContentLoaded", () => {
  const calendarLinks = document.querySelectorAll(".calendar-link");

  calendarLinks.forEach((calendarLink) => {
    calendarLink.addEventListener("click", (e) => {
      e.preventDefault();
      const text = calendarLink.textContent;

      navigator.clipboard
        .writeText(text)
        .then(() => {
          window.notify("SUCCESS", "Lien copié dans le presse-papier", true);
        })
        .catch((err) => {
          window.notify(
            "ERROR",
            "Impossible de copié le lien dans le presse-papier",
            true
          );
        });
    });
  });
});
