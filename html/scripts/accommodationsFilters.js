/**********/
/** MAIN **/
/**********/

const clearAllFiltersButton = document.getElementById("clear-all-filters-button");
const cityList = document.getElementById("city-list");
const townCheckboxes = document.querySelectorAll("#city-list input[type='checkbox']");
const departmentCheckboxes = document.querySelectorAll("#department-list input[type='checkbox']");
const minPriceInput = document.getElementById("min-price");
const maxPriceInput = document.getElementById("max-price");
const accommodationTitleSearchContainer = document.getElementById("accommodation-title-search-container");
let accommodationList = document.getElementById("accommodation-list");
const paginationForm = document.getElementById("pagination");
const leftButton = document.getElementById("left-pagination-button");
const rightButton = document.getElementById("right-pagination-button");
const compactSearchBar = document.getElementById("compact-search-bar");
const searchInputCompact = document.getElementById("search-input-compact");  
const dateInputCompact = document.getElementById("date-input-compact");  
const travelersInputCompact = document.getElementById("travelers-input-compact");
let inputSearchBar = document.getElementById("search-input");
let inputNumberOfTravelers = document.getElementById("travelers-number-input");
let compactSearchBarButton = document.getElementById("compact-search-bar-button");

const accommodationPerPage = 10;
let allResults;
let totalPages;
let currentPage = 1;
let indiceDebut = (currentPage - 1) * accommodationPerPage;

let selectedTowns = [];
let selectedDepartments = [];
let priceRange = []; // [price_min, price_max] 
 let stringEntered = "";
// let arrivalSelected;
// let departureSelected;
let travelersSelected = 1;

document.addEventListener("DOMContentLoaded", () => {
   refreshAccommodationsResults();

   clearAllFiltersButton.addEventListener("click", () => {
      clearAllFilters();
   });

   //function toggleCheckbox(selectedData, checkboxes, name) {
   toggleCheckbox(selectedTowns, townCheckboxes, "name");
   toggleCheckbox(selectedDepartments, departmentCheckboxes, "dataset.postcode");
   getPrices(minPriceInput, "minPrice");
   getPrices(maxPriceInput, "maxPrice");

   // ⭕️ TODO COMPILER TOUT ENSEMBLE UNE FOIS
   searchInputCompact.addEventListener("focus", () => {
      compactSearchBar.innerHTML = "";
      renderFullSearchBar();
      attachSearchListener();
      searchOnClick();
      attachTravelersInputListener();
   })
   dateInputCompact.addEventListener("focus", () => {
      compactSearchBar.innerHTML = "";
      renderFullSearchBar();
      searchOnClick();
      attachTravelersInputListener();
   })
   travelersInputCompact.addEventListener("focus", () => {
      compactSearchBar.innerHTML = "";
      renderFullSearchBar();
      attachTravelersInputListener();
      searchOnClick();
   })

   attachSearchListener();
   //attachTravelersInputListener();
});



/***************/
/** FONCTIONS **/
/***************/

function searchOnClick() {
   compactSearchBarButton = document.getElementById("compact-search-bar-button");
   if (compactSearchBarButton) {
      compactSearchBarButton.addEventListener("click", () => {
         refreshAccommodationsResults();
      }, false);
   }
}

function attachSearchListener() {
   inputSearchBar = document.getElementById("search-input");
   if (inputSearchBar) {
      inputSearchBar.addEventListener("input", () => {
         stringEntered = (inputSearchBar.value).toLowerCase();
      });
   }
}

function attachTravelersInputListener() {
   const inputNumberOfTravelers = document.getElementById("travelers-number-input");
   
   if (inputNumberOfTravelers) {
      inputNumberOfTravelers.addEventListener("change", () => {
         travelersSelected = parseInt(inputNumberOfTravelers.value);
      });
   }
}

function renderFullSearchBar() {
   const searchInput = document.createElement('input');
   searchInput.type = 'text';
   searchInput.placeholder = 'Rechercher un séjour';
   searchInput.className = 'search-input';
   searchInput.id = 'search-input';

   const arrivalDateInput = document.createElement('input');
   arrivalDateInput.placeholder = 'Arrivée';
   arrivalDateInput.className = 'arrival-date-input';
   arrivalDateInput.type = 'text';
   arrivalDateInput.onfocus = function() { this.type = 'date'; };
   arrivalDateInput.onblur = function() { this.type = 'text'; };

   const departureDateInput = document.createElement('input');
   departureDateInput.placeholder = 'Départ';
   departureDateInput.className = 'departure-date-input';
   departureDateInput.type = 'text';
   departureDateInput.onfocus = function() { this.type = 'date'; };
   departureDateInput.onblur = function() { this.type = 'text'; };

   const travelersNumberInput = document.createElement('input');
   travelersNumberInput.id = 'travelers-number-input';
   travelersNumberInput.className = 'travelers-number-input';
   travelersNumberInput.type = 'number';
   travelersNumberInput.placeholder = 'Nombre de voyageurs';
   travelersNumberInput.min = 1;

   const button = document.createElement('button');
   button.className = 'is-disabled';
   const buttonIcon = document.createElement('span');
   buttonIcon.className = 'mdi mdi-magnify';
   button.id = 'compact-search-bar-button';
   button.type = "button";
   button.appendChild(buttonIcon);

   compactSearchBar.appendChild(searchInput);
   compactSearchBar.appendChild(arrivalDateInput);
   compactSearchBar.appendChild(departureDateInput);
   compactSearchBar.appendChild(travelersNumberInput);
   compactSearchBar.appendChild(button);
}

function getPrices(priceInput, dataName) {
   priceInput.addEventListener("input", (event) => {
      const priceEntered = parseInt(event.target.value);
      dataName === "minPrice" ? priceRange[0] = priceEntered : priceRange[1] = priceEntered;
      
      if (isNaN(priceRange[0])) 
         priceRange[0] = 0;
      if (isNaN(priceRange[1]))
         priceRange[1] = priceInput.dataset.maxprice;
      
      // ⭕️ VERIFIER QUE LE MAX > MIN
      if (priceRange[0] > priceRange[1]) {
         console.log("Le prix maximum ne peut être inférieur au prix minimum.");
         renderNoResults(); // ne fonctionne pas pour le moment
      }

      refreshAccommodationsResults();
   });
}

/** Génère l'affichage du nombre de logements résultant de la recherche ("Logements (35)") */
function updateAccommodationResultNumber(results) {
   const accommodationResultsNumber = document.getElementById("accommodation-results-number");
   let resultLength = results.length;
   
   if (accommodationResultsNumber === null) {
      let accommodationResultNumberH1 = document.createElement("h1");
      accommodationResultNumberH1.id = "accommodation-results-number";
      accommodationResultNumberH1.textContent = `Logements (${ resultLength })`;
      accommodationTitleSearchContainer.insertBefore(accommodationResultNumberH1, accommodationTitleSearchContainer.firstChild);
   } else {
      accommodationResultsNumber.textContent = `Logements (${resultLength})`;
   }
}

function addCheckboxFilter(selectedData, selectedCheckbox) {
   if (!selectedData.includes(selectedCheckbox)) {
      selectedData.push(selectedCheckbox);
   }
}

function removeCheckboxFilter(selectedData, selectedCheckbox) {
   const index = selectedData.indexOf(selectedCheckbox);
   if (index > -1) {
      selectedData.splice(index, 1);
   }
}

/** Ajoute ou retire les données (commune ou département) de la liste 'selectedTownsAndDepartments'
 * @param { Array } checkboxes tableau du contenu (communes ou départements) sélectionné
 * @param { String } name ("dataset.postcode" ou "name")
 * @param { String } dataName 
*/
function toggleCheckbox(selectedData, checkboxes, name) {
   checkboxes.forEach(checkbox => {
      checkbox.addEventListener("change", async ({ target }) => {
         const checkboxName = name === "dataset.postcode" ? target.dataset.postcode : target.name;
         target.checked ? addCheckboxFilter(selectedData, checkboxName) : removeCheckboxFilter(selectedData, checkboxName);


         refreshAccommodationsResults();
         // recharge la liste complète si toutes les cases sont décochées à la main
         //selectedTownsAndDepartments.length === 0 ? refreshAccommodationsResults() : refreshAccommodationsResults("townsAndDepartments", selectedTownsAndDepartments);
      })
   });
} 

/** Récupère les données sélectionnées. Si aucun paramètre, renvoie tous les logements. */
async function fetchData(url = "", data = {}) {
   const response = await fetch(url, {
      method: "POST",
      headers: {
         'Content-Type': 'application/json'
      },
      body: JSON.stringify(data),
   });
   return response.json();
}


/** Récupère les logements sélectionnés et met à jour la liste */ 
async function refreshAccommodationsResults() {
   accommodationList.innerHTML = ""; // Empêche les logements de se cumuler à chaque nouvelle recherche
   console.log(travelersSelected);
   const accommodationResults = await fetchData("/controllers/accommodationsFilters.php", {
      "towns": selectedTowns,
      "departments": selectedDepartments,
      "priceRange": priceRange,
      "stringSearch": stringEntered,
      // "arrivalDate": arrivalSelected,
      // "departureDate": departureSelected,
      "travelers": travelersSelected, 
   });
   accommodationResults ?
      accommodationResults.forEach((accommodation) => {
         renderAccommodationList(accommodation);
      })
   : renderNoResults();

// console.log("current : " , currentPage);

//    let startIndex = (currentPage - 1) * accommodationPerPage + 1;
//    let endIndex = currentPage * accommodationPerPage + 1;

//    if (accommodationResults) {
//       for (let i = startIndex; i < endIndex; i++) {
//          renderAccommodationList(accommodationResults[i])
//       } 
//    } else {
//       renderNoResults();
//    }
   updateAccommodationResultNumber(accommodationResults);

   allResults = accommodationResults.length;
   totalPages = Math.ceil(allResults / accommodationPerPage);

   const buttons = document.querySelectorAll("#pagination > button:not(.action)");
   for(const button of buttons) {
      button.remove();
   }   
   renderPagination();
}

// PAGINATION ///////////////////////////////////////////////////////

function goPrevPage() {
   if (currentPage > 1) {
      currentPage--;
      refreshAccommodationsResults();
   }
}

function goNextPage() {
   if (currentPage < totalPages) {
      currentPage++;
      refreshAccommodationsResults();
   }
}

function renderPagination() {
   // Affichage de la première page (/3) des boutons de paginations
   let min = currentPage === totalPages ? currentPage - 2 : currentPage - 1;
   if (min < 1) {
      min = 1;
   }
   // Affichage de la dernière page (/3) des boutons de paginations
   let max = currentPage === 1 ? 3 : currentPage + 1;
   if (max > totalPages) {
      max = totalPages;
   }
   
   // BOUTON PRÉCÉDENT
   if (currentPage == 1) {
      leftButton.disabled = true;
   }
   leftButton.value = `${ currentPage - 1 }`;
   leftButton.addEventListener("click", () => {
      goPrevPage();
   });
   
   // BOUTONS NUMÉROS DE PAGES
   for (let i = min; i <= max; i++) {
      let numberPageButtons = document.createElement("button");
      numberPageButtons.className = i === currentPage ? "primary" : "secondary";
      //numberPageButtons.className = "number-pagination-buttons";
      numberPageButtons.name = "page";
      numberPageButtons.value = i;
      numberPageButtons = paginationForm.insertBefore(numberPageButtons, rightButton);

      let numberPage = document.createElement("span");
      numberPage.textContent = i;
      numberPageButtons.appendChild(numberPage);
      numberPageButtons.addEventListener("click", () => {
         //
      });
   }

   // BOUTON SUIVANT
   if (currentPage == totalPages) {
      rightButton.disabled = true;
   }
   rightButton.value = `${ currentPage + 1 }`;
   rightButton.addEventListener("click", () => {
      goNextPage();
   });

}
///////////////////////////////////////////////////////////////////////// limit + offset

/** Génère la liste des logements */
function renderAccommodationList(accommodation) {
   let linkToOpenAccommodationDetails = document.createElement("a");
   linkToOpenAccommodationDetails.href = `/logement?id_logement=${ accommodation.id_logement }`; //
   accommodationList.appendChild(linkToOpenAccommodationDetails);

   let accommodationItem = document.createElement("article");
   accommodationItem.className = "accommodation-item";
   linkToOpenAccommodationDetails.appendChild(accommodationItem);
   
   let accommodationImageItemContainer = document.createElement("div");
   accommodationImageItemContainer.className = "accommodation-image-item-container";
   accommodationItem.appendChild(accommodationImageItemContainer);

   let accommodationImage = document.createElement("img");
   accommodationImage.src = `/files/logements/${ accommodation.photo_logement }.jpg`; //
   accommodationImage.alt = "Photo du logement";
   accommodationImageItemContainer.appendChild(accommodationImage);

   let accommodationTextDetails = document.createElement("div");
   accommodationTextDetails.className = "accommodation-text-details";
   accommodationItem.appendChild(accommodationTextDetails);

   let accommodationDescriptionContainer = document.createElement("div");
   accommodationDescriptionContainer.className = "accommodation-description-container";
   accommodationTextDetails.appendChild(accommodationDescriptionContainer);

   let accommodationDescription = document.createElement("h4");
   accommodationDescription.className = "accommodation-description";
   accommodationDescriptionContainer.appendChild(accommodationDescription);

   let accommodationDescriptionAbbr = document.createElement("abbr");
   accommodationDescriptionAbbr.title = `${ accommodation.titre_logement }`; //
   accommodationDescriptionAbbr.textContent = `${ accommodation.titre_logement }`; //
   accommodationDescriptionContainer.appendChild(accommodationDescriptionAbbr);

   let accommodationLocationContainer = document.createElement("div");
   accommodationLocationContainer.className = "accommodation-location-container";
   accommodationTextDetails.appendChild(accommodationLocationContainer);

   let locationIcon = document.createElement("span");
   locationIcon.className = "mdi mdi-map-marker";
   accommodationLocationContainer.appendChild(locationIcon);

   let accommodationLocation = document.createElement("h4");
   accommodationLocation.className = "accommodation-location";
   accommodationLocationContainer.appendChild(accommodationLocation);

   let accommodationLocationAbbr = document.createElement("abbr");
   accommodationLocationAbbr.title = `${ accommodation.ville_adresse }, ${ accommodation.code_postal_adresse }`; //
   accommodationLocationAbbr.textContent = `${ accommodation.ville_adresse }, ${ accommodation.code_postal_adresse }`; //
   accommodationLocation.appendChild(accommodationLocationAbbr);

   let accommodationPriceContainer = document.createElement("div");
   accommodationPriceContainer.className = "accommodation-price-container";
   accommodationTextDetails.appendChild(accommodationPriceContainer);

   let accommodationPrice = document.createElement("span");
   accommodationPrice.textContent = `${ accommodation.prix_ttc_logement } €`; //
   accommodationPrice.className = "accommodation-price";
   accommodationPriceContainer.appendChild(accommodationPrice);

   let perNight = document.createElement("span");
   perNight.className = "per-night";
   perNight.textContent = "par nuit";
   accommodationPriceContainer.appendChild(perNight);
}

// ⭕️ TODO CORRIGER LE CSS
function renderNoResults() {
   let noResult = document.createElement("p");
   noResult.textContent = "Désolé, aucun logement correspondant à votre recherche n'a été trouvé..."; //
   accommodationList.appendChild(noResult);
}

/** Décoche toutes les cases cochées 
 * @param { String } checkboxType le 'type' du contenu des checkboxes à décocher 
 * @example "town", "department"
*/
function uncheckAllCheckboxes(checkboxType) {
   let checkboxes = document.getElementsByClassName(`${ checkboxType }-checkboxes`);  
   for (let i = 0; i < checkboxes.length; i++) {  
         checkboxes[i].checked = false;
   }
}

/** Vide les champs de texte */
function emptyAllTextInputs() {
   let priceInputs = document.getElementsByClassName("price-input"); 
   for (let i = 0; i < priceInputs.length; i++) {  
      priceInputs[i].value = "";
   }
}

// function closeOpenedMenu(menuToClose) {
//    if (menuToClose.className === "displayed") {
//       menuToClose.className = "hidden";
//    }
// }

/** Retire tous les filtres et réinitialise les résultats des logements */
function clearAllFilters() {
   selectedTowns = [];
   selectedDepartments = [];
   priceRange = [];
   stringEntered = "";
   // let arrivalSelected;
   // let departureSelected;
   travelersSelected = 1;
   
   // décocher toutes les checkboxes et vider les inputs
   // ⭕️ TODO GRISER SI AUCUN FILTRE ACTIF
   uncheckAllCheckboxes("town");
   uncheckAllCheckboxes("department");
   emptyAllTextInputs();
   //closeOpenedMenu(cityList); // 🤔 CONSERVER ?

   // ⭕️ REINITIALISER LES PRIX

   // réafficher tous les logements (réinitialiser)
   refreshAccommodationsResults();
}

