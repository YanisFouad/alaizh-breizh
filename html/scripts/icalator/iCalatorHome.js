window.addEventListener("DOMContentLoaded", () => {
  const deleteButtons = document.querySelectorAll(".delete-btn");

  deleteButtons.forEach((button) => {
    button.addEventListener("click", () => {
      generateModal(button.dataset.key, button);
    });
  });
});

async function deleteCalendar(apiKey, button) {
  try {
    const response = await fetch(
      `/backoffice/calendrier/supprimer?key=${apiKey}`,
      {
        method: "POST",
      }
    );
    if (response.ok) {
      window.notify("SUCCESS", "Calendrier supprimé avec succès", true);
      button.parentElement.parentElement.remove();
    } else {
      window.notify("ERROR", "Impossible de supprimer le calendrier", true);
    }
  } catch (error) {
    window.notify("ERROR", "Impossible de supprimer le calendrier", true);
  }
}

function generateModal(apiKey, button) {
  const modal = document.getElementById("modal-calendar");
  const modalBackground = document.getElementById("modal-calendar-background");
  const modalClose = document.getElementById("modal-calendar-close");
  const modaleDelete = document.getElementById("modal-calendar-delete");

  modal.style.display = "block";
  modalBackground.style.display = "block";

  modaleDelete.addEventListener("click", () => {
    closeModal();
    deleteCalendar(apiKey, button);
  });

  modalBackground.addEventListener("click", () => {
    closeModal();
  });

  modalClose.addEventListener("click", () => {
    closeModal();
  });

  function closeModal() {
    modal.style.display = "none";
    modalBackground.style.display = "none";
  }
}