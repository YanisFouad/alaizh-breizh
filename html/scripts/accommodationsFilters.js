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
let paginationForm = document.getElementById("pagination");
const leftButton = document.getElementById("left-pagination-button");
const rightButton = document.getElementById("right-pagination-button");
const compactSearchBar = document.getElementById("compact-search-bar");
const searchInputCompact = document.getElementById("search-input-compact");  
const dateInputCompact = document.getElementById("date-input-compact");  
const travelersInputCompact = document.getElementById("travelers-input-compact");
let inputSearchBar = document.getElementById("search-input");
let inputNumberOfTravelers = document.getElementById("travelers-number-input");
let compactSearchBarButton = document.getElementById("compact-search-bar-button");
let arrivalDateInput = document.getElementById("arrival-date-input");
let departureDateInput = document.getElementById("departure-date-input");

const accommodationPerPage = 10;
let allResults;
let totalPages;
let currentPage = 1;
let indiceDebut = (currentPage - 1) * accommodationPerPage;

let selectedTowns = [];
let selectedDepartments = [];
let priceRange = []; // [price_min, price_max] 
let stringEntered = "";
let arrivalSelected;
let departureSelected;
let travelersSelected = 1;

const DEFAULT_LIMIT = 10;
let page = 1;
let limit = DEFAULT_LIMIT;

const SortDir = {
   ASC: "ASC",
   DESC: "DESC"
}
let sortDir = SortDir.DESC;

document.addEventListener("DOMContentLoaded", () => {
   const url = new URL(location);
   const params = url.searchParams;
   if(params.has("page")) {
      try {page = parseInt(params.get("page"))} catch(e) {}
   }

   if(params.get("sortDir"))
      sortDir = params.get("sortDir");
   if(params.get("searchQuery")?.trim()) {
      stringEntered = params.get("searchQuery");
   }
   if(params.get("arrivesOn")?.trim()) {
      arrivalSelected = params.get("arrivesOn");
   }
   if(params.get("departureOn")?.trim()) {
      departureSelected = params.get("departureOn");
   }
   if(params.has("travelersCount")) {
      travelersSelected = parseInt(params.get("travelersCount"));
   }

   updateSortName();
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
      getArrivalDate();
      getDepartureDate();
      attachSearchListener(); 

   })
   dateInputCompact.addEventListener("focus", () => {
      compactSearchBar.innerHTML = "";
      renderFullSearchBar();
      searchOnClick();
      attachTravelersInputListener();
      getArrivalDate();
      getDepartureDate();
      attachSearchListener(); 

   })
   travelersInputCompact.addEventListener("focus", () => {
      compactSearchBar.innerHTML = "";
      renderFullSearchBar();
      attachTravelersInputListener();
      searchOnClick();
      getArrivalDate();
      getDepartureDate();
      attachSearchListener(); 
   })
});

/***************/
/** FONCTIONS **/
/***************/

function getArrivalDate() {
   arrivalDateInput = document.getElementById("arrival-date-input");
   if (arrivalDateInput) {
      arrivalDateInput.addEventListener("change", () => {
         arrivalSelected = arrivalDateInput.value;
         console.log(arrivalSelected);
      })
   }
}

function getDepartureDate() {
   departureDateInput = document.getElementById("departure-date-input");
   if (departureDateInput) {
      departureDateInput.addEventListener("change", () => {
         departureSelected = departureDateInput.value;
      })
   }
}

function searchOnClick() {
   compactSearchBarButton = document.getElementById("compact-search-bar-button");
   if (compactSearchBarButton) {
      compactSearchBarButton.addEventListener("click", () => {
         if(arrivalSelected && departureSelected) {
            const arrivesOn = (new Date(arrivalSelected)).getTime();
            const departureOn = (new Date(departureSelected)).getTime();
            if(departureOn < arrivesOn) {
               window.notify(
                  "ERROR",
                  "La date d'arrivée ne peut pas être supérieure à celle de départ.",
                  true
               );
               return;
            }
         }
         refreshAccommodationsResults();
      }, false);
   }
}

function attachSearchListener() {
   inputSearchBar = document.getElementById("search-input");
   if (inputSearchBar) {
      inputSearchBar.addEventListener("input", () => {
         stringEntered = (inputSearchBar.value).toLowerCase();
      }, false);
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
   arrivalDateInput.id = 'arrival-date-input';
   arrivalDateInput.className = 'arrival-date-input';
   arrivalDateInput.type = 'text';
   arrivalDateInput.onfocus = function() { 
      this.type = 'date'; 
   };
   arrivalDateInput.onblur = function() { 
      this.type = 'text'; 
      this.value = this.value.split("-").reverse().join("/");
   };

   const departureDateInput = document.createElement('input');
   departureDateInput.placeholder = 'Départ';
   departureDateInput.id = 'departure-date-input';
   departureDateInput.className = 'departure-date-input';
   departureDateInput.type = 'text';
   departureDateInput.onfocus = function() { 
      this.type = 'date';
   };
   departureDateInput.onblur = function() { 
      this.type = 'text';
      this.value = this.value.split("-").reverse().join("/");
   };

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

      page = 1;
      refreshAccommodationsResults();
   });
}

/** Génère l'affichage du nombre de logements résultant de la recherche ("Logements (35)") */
function updateAccommodationResultNumber() {
   const accommodationResultsNumber = document.getElementById("accommodation-results-number");
   let resultLength = totalCount;
   
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
         page = 1;

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
   //console.log(travelersSelected);
   const url = new URL(location);
   url.searchParams.set("page", page);
   history.pushState({}, null, url);

   const {communes, items: accommodationResults, totalCount: total} = await fetchData("/controllers/accommodations/accommodationsFilters.php", {
      "towns": selectedTowns,
      "departments": selectedDepartments,
      "priceRange": priceRange,
      "stringSearch": stringEntered,
      "arrivalDate": arrivalSelected,
      "departureDate": departureSelected,
      "travelers": travelersSelected, 
      "offset": (page-1)*limit,
      "limit": limit,
      "sortDir": sortDir
   });
   document.getElementById("pagination").style.display = "flex";
   document.getElementById("no-accommodation-result-area").innerHTML = "";
   total > 0 ?
      accommodationResults.forEach((accommodation) => {
         renderAccommodationList(accommodation);
      })
   : renderNoResults();
   totalCount = total;
   updateAccommodationResultNumber(accommodationResults);
   renderCommunes(communes);

   allResults = totalCount;
   totalPages = Math.ceil(allResults / accommodationPerPage);

   const buttons = document.querySelectorAll("#pagination > button:not(.action)");
   for(const button of buttons) {
      button.remove();
   }   
   renderPagination();
   window.scrollTo(0, 0);
}

// PAGINATION ///////////////////////////////////////////////////////

function goPrevPage() {
   if (page > 1) {
      page--;
      refreshAccommodationsResults();
   }
   console.log(page)
}

function goNextPage() {
   if (page < totalPages-1) {
      page++;
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
   leftButton.disabled = page <= 1;

   leftButton.value = `${ currentPage - 1 }`;
   leftButton.onclick = goPrevPage;
   
   // BOUTONS NUMÉROS DE PAGES
   for (let i = min; i <= max; i++) {
      let numberPageButtons = document.createElement("button");
      numberPageButtons.className = page === i ? "primary" : "secondary";
      //numberPageButtons.className = "number-pagination-buttons";
      numberPageButtons.name = "page";
      numberPageButtons.value = i;
      numberPageButtons = paginationForm.insertBefore(numberPageButtons, rightButton);

      let numberPage = document.createElement("span");
      numberPage.textContent = i;
      numberPageButtons.appendChild(numberPage);

      numberPageButtons.onclick = () => {
         page = i;
         refreshAccommodationsResults();
      }
   }

   // BOUTON SUIVANT
   rightButton.disabled = page >= totalPages-1;

   rightButton.value = `${ currentPage + 1 }`;
   rightButton.onclick = goNextPage;
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

// ⭕️ TODO AJOUTER CSS
function renderNoResults() {
   document.getElementById("no-accommodation-result-area")
      .insertAdjacentHTML("afterbegin", "<p>Désolé, aucun logement correspondant à votre recherche n'a été trouvé...</p>");
   document.getElementById("pagination").style.display = "none";
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

/** Retire tous les filtres et réinitialise les résultats des logements */
function clearAllFilters() {
   selectedTowns = [];
   selectedDepartments = [];
   priceRange = [];
   stringEntered = "";
   arrivalSelected = null;
   departureSelected = null;
   travelersSelected = 1;
   page = 1;
   
   // décocher toutes les checkboxes et vider les inputs
   // ⭕️ TODO GRISER SI AUCUN FILTRE ACTIF
   uncheckAllCheckboxes("town");
   uncheckAllCheckboxes("department");
   emptyAllTextInputs();

   // réafficher tous les logements (réinitialiser)
   refreshAccommodationsResults();
}


/** CONTEXT FILTER AREA */
function renderCommunes(communes) {
   let cityList = document.getElementById("city-list");
   cityList.innerHTML = "";
   communes = [...new Set(communes)].sort((a, b) => b.localeCompare(a));
   for(const commune of communes) {
      cityList.insertAdjacentHTML("afterbegin", `
         <li>
            <input
               type="checkbox"
               class="town-checkboxes"  
               id="${commune}" 
               name="${commune}"
            >
            <label for="${commune}">
               ${commune}
            </label>
         </li>
      `);
   }
}

/** SORT AREA */
const sortBtn = document.getElementById("sort-btn");
const SORT_LABEL = "Tri par prix ";

sortBtn.addEventListener("click", () => {
   sortDir  = sortDir === SortDir.DESC ? SortDir.ASC : SortDir.DESC;  
   refreshAccommodationsResults();
   updateSortName();
   
   const url = new URL(location);
   url.searchParams.set("sortDir", sortDir);
   window.history.pushState({}, null, url);
}, false);
function updateSortName() {
   const icon = sortBtn.querySelector(".mdi");
   const label = document.querySelector(".label");
   if(sortDir === SortDir.ASC) {
       icon.classList.remove("mdi-sort-descending");
       icon.classList.add("mdi-sort-ascending");
   } else {
       icon.classList.add("mdi-sort-descending");
       icon.classList.remove("mdi-sort-ascending");
   }
   label.textContent = SORT_LABEL + (sortDir === SortDir.ASC ? "décroissant" : "croissant");
}