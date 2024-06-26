window.addEventListener('DOMContentLoaded', (event) => {
    const form = document.getElementById('form-icalator');

    form.addEventListener('submit', (event) => {

        const errorMessages = form.querySelectorAll('.error-message');
        errorMessages.forEach((errorMessage) => {
            errorMessage.remove();
        });

        event.preventDefault();
        const formData = new FormData(form);
        const startDate = formData.get('date-debut-souscription');
        const endDate = formData.get('date-fin-souscription');
        const logement = formData.getAll('logements[]');

        const startDateInput = document.getElementById('date-debut-souscription');
        const endDateInput = document.getElementById('date-fin-souscription');

        if(!startDate) {
            addError(startDateInput, 'La date de début est obligatoire');
            return;
        }

        if(!endDate) {
            addError(endDateInput, 'La date de fin est obligatoire');
            return;
        }

        if (startDate > endDate) {
            addError(startDateInput, 'La date de début doit être inférieure à la date de fin');
            return;
        }

        if(logement.length === 0) {
            const logementContainer = document.querySelector('label[for="logements"]');
            addError(logementContainer, 'Veuillez sélectionner au moins un logement');
            return;
        }

        form.submit();
    });
});

function addError(input, message) {
    input.insertAdjacentElement("afterend", createErrorMessage(message));
    input.classList.add('error');
    window.scrollTo({
        top: input.offsetTop - 50,
        behavior: 'smooth'
    });
}

function createErrorMessage(message) {
    const errorContainer = document.createElement('div');
    errorContainer.className = 'error-message';

    const icon = document.createElement('span');
    icon.className = 'mdi mdi-alert-circle-outline';
    errorContainer.appendChild(icon);

    const errorMessage = document.createElement('p');
    errorMessage.textContent = message;
    errorContainer.appendChild(errorMessage);

    return errorContainer;
}