const filterContainerElement = document.getElementById("filter-container");
const filterButtonElement = document.getElementById("filter-button");

const minPriceInput = document.getElementById("min-price");
const maxPriceInput = document.getElementById("max-price");

const accommodationsElement = document.getElementById("accommodation-list");
const noAccommodationFoundElement = document.getElementById("no-accommodation-result-area");
const accommodationSearchContainer = document.getElementById("accommodation-search-container");

const paginationElement = document.getElementById("pagination");

const fullSearchBar = document.getElementById("full-search-bar");
const tinySearchBar = document.getElementById("compact-search-bar");

const sortBtn = document.getElementById("sort-btn");

const SORT_LABEL = "Tri par prix ";
const LIMIT = 10;

const SortDir = {
   DESC: "DESC",
   ASC: "ASC"
}

let sortDir = SortDir.DESC;
let page = 1;
let totalPages = 0;
let filters = {}

/** GLOBAL UPDATE */

async function update({updateURL = true} = {}) {
   try {
      const response = await fetch("/controllers/accommodations/accommodationsFilters.php", {
         method: "POST",
         body: JSON.stringify({
            ...filters,
            sortDir,
            offset: (page-1)*LIMIT,
            limit: LIMIT
         })
      });

      if(!response.ok) {
         window.notify(
            "ERROR",
            "Réponse pas ok",
            true
         )
         return;
      }

      if(updateURL) {
         store();
      }
      const {items, totalCount, ...contextFilter} = await response.json();

      totalPages = Math.ceil(totalCount/LIMIT);
      noAccommodationFoundElement.style.display = !items.length ? "block" : "none";

      renderTotalCount(totalCount);
      renderContextFilter(contextFilter);
      renderAccommodations(items);
      renderPagination(items.length);
      renderSortButtonText();
   } catch(e) {
      console.trace(e);
      window.notify(
         "ERROR",
         `Une erreur est survenue: ${e.stack}`,
         true
      )
   }
}

/** RENDERS */

function renderTinySearchBar() {
   fullSearchBar.style.display = "none";
   tinySearchBar.style.display = "flex";
}

function renderFullSearchBar() {
   fullSearchBar.style.display = "flex";
   tinySearchBar.style.display = "none";
}

function renderContextFilter({priceRange: {min: minPrice, max: maxPrice}, cities, departments}) {
   const citiesElement = document.getElementById("cities");
   const departmentsElement = document.getElementById("departments");

   // clear filters
   citiesElement.innerHTML = "";
   departmentsElement.innerHTML = "";

   cities.forEach(city => renderFilterElementCheckbox(citiesElement, "cities", city));
   departments.forEach(city => renderFilterElementCheckbox(departmentsElement, "departments", city));

   const _formatPrice = price =>  Intl.NumberFormat("fr-FR", {style: 'currency', currency: 'EUR'}).format(parseFloat(price));

   minPriceInput.placeholder = `Prix minimum: ${_formatPrice(minPrice)}`;
   maxPriceInput.placeholder = `Prix maximum: ${_formatPrice(maxPrice)}`;
}

function renderFilterElementCheckbox(filterElement, filterName, data) {
   filterElement?.insertAdjacentHTML("afterbegin", `
      <li>
         <input 
            type="checkbox"
            class="filter-checkbox"
            id="${data}" 
            name="${data}"
            ${filters[filterName].includes(data) ? 'checked' : ''}
         >
         <label for="${data}">${data}</label>
      </li>
   `);
   document.getElementById(data).onchange = () => {
      handleFilterCheckbox(filterName, data);
   }
}

function renderTotalCount(totalCount) {
   document.getElementById("accommodation-results-number").textContent = totalCount;
}

function renderSortButtonText() {
   const icon = sortBtn.querySelector(".mdi");
   const label = sortBtn.querySelector(".label");
   if(sortDir === SortDir.ASC) {
       icon.classList.remove("mdi-sort-descending");
       icon.classList.add("mdi-sort-ascending");
   } else {
       icon.classList.add("mdi-sort-descending");
       icon.classList.remove("mdi-sort-ascending");
   }
   label.textContent = SORT_LABEL + (sortDir === SortDir.ASC ? "décroissant" : "croissant");

}

function renderAccommodations(accommodations) {
   accommodationsElement.innerHTML = "";
   accommodations.forEach(accommodation => 
      renderAccommodation(accommodation));
}

function renderAccommodation({ id_logement, titre_logement, photo_logement, nom_departement, ville_adresse, prix_ttc_logement } = {}) {
   const priceFormat = Intl.NumberFormat("fr-FR", {style: 'currency', currency: 'EUR'}).format(parseFloat(prix_ttc_logement));
   accommodationsElement.insertAdjacentHTML("afterbegin", `
      <a href="/logement?id_logement=${id_logement}">
         <article class="item">
            <div class="img-container">
               <img src="${photo_logement}" alt="Logement">
            </div>
            <footer>
               <h4 class="title" title="${titre_logement}">
                  ${titre_logement}
               </h4>
               <h4 class="localization" title="${ville_adresse}">
                  <span class="mdi mdi-map-marker"></span>
                  ${ville_adresse}, ${nom_departement}
               </h4>
               <h4 class="price" title="${priceFormat} par nuit">
                  <span>${priceFormat}</span>
                  <span>par nuit</span>
               </h4>
            </footer>
         </article>
      </a>   
   `);
}

function renderPagination(doNotRender=false) {
   paginationElement.innerHTML = "";
   if(doNotRender) {
      paginationElement.insertAdjacentHTML("afterbegin", `
         <button onclick="handlePreviousPage()" class="secondary action" name="page" ${page <= 1 ? "disabled" : ""}>
            <span class="mdi mdi-chevron-left"></span>
         </button>
   
         ${Array(totalPages).fill('').map((_, i) => 
            `<button onclick="handlePage(${i+1})" class="${i === page-1 ? "primary": "secondary"}">
               <span>${i+1}</span>
            </button>`
         ).join("\n")}
         
         <button onclick="handleNextPage()" class="secondary action" name="page" ${(page > totalPages-1) ? "disabled" : ""}>
            <span class="mdi mdi-chevron-right"></span>
         </button>
      `);
   }
}

/** GLOBAL EVENTS */

document.addEventListener("DOMContentLoaded", () => {
   initFilters();
   renderTinySearchBar();
   load();
   update({updateURL: false});
});

/** FILTER EVENTS */

// toggle filter
filterButtonElement.addEventListener("click", () => {
   if(!filterContainerElement.classList.contains("extended")) {
      filterContainerElement.classList.add("extended");
   } else {
      filterContainerElement.classList.remove("extended");
   }
});

// clear filter button
document.getElementById("clear-filters").addEventListener("click", () => {
   initFilters();
   update();
});

// Searchbar search button
document.getElementById("search-button").addEventListener("click", () => {
   const querySearchInput = document.getElementById("search-query-input");
   const arrivalDateInput = document.getElementById("arrival-date-input");
   const departureDateInput = document.getElementById("departure-date-input");
   const travelersNumberInput = document.getElementById("travelers-number-input");

   let arrivesOn = arrivalDateInput.value?.trim() ? arrivalDateInput.value : null;
   let departureOn = departureDateInput.value?.trim() ? departureDateInput.value : null;
   let travelersNumber = travelersNumberInput.value?.trim() ? travelersNumberInput.value : null;
   const searchQuery = querySearchInput.value?.trim() ? querySearchInput.value : null;
   if(arrivesOn) {
      arrivesOn = arrivesOn.split("/").reverse().join("-");
   }
   if(departureOn) {
      departureOn = departureOn.split("/").reverse().join("-");
   }
   if(travelersNumber) {
      travelersNumber = parseInt(travelersNumber); 
   }
   
   filters.searchQuery = searchQuery,
   filters.dateRange.arrivesOn = arrivesOn;
   filters.dateRange.departureOn = departureOn;
   filters.nbTravelers = travelersNumber;

   if(arrivesOn && departureOn) {
      const a = (new Date(arrivesOn)).getTime();
      const d = (new Date(departureOn)).getTime();
      if(a > d) {
         window.notify(
            "ERROR",
            "La date d'arrivée ne peut pas être supérieur à celle de départ.",
            true
         )
         return;
      }
   }

   if(!searchQuery && !arrivesOn && !departureOn && !travelersNumber)
      return;
   update();
});

sortBtn.addEventListener("click", () => {
   sortDir  = sortDir === SortDir.DESC ? SortDir.ASC : SortDir.DESC;  
   update();
});

// price range inputs
minPriceInput.addEventListener("input", ({target}) => {
   filters.priceRange.min = parseFloat(target.value);
   update();
});

maxPriceInput.addEventListener("input", ({target}) => {
   filters.priceRange.max = parseFloat(target.value);
   update();
});

/** SEARCH BAR UTILS */
function focusDate(element) {
   element.type = "date";
}
function unfocusDate(element) {
   element.type = "text";
   element.value = element.value.split("-").reverse().join("/");
}

/** FILTERS UTILS */
function initFilters() {
   filters = {
      cities: [],
      departments: [],
      priceRange: {min: null, max: null},
      searchQuery: null,
      dateRange: {arrivesOn: null, departureOn: null},
      nbTravelers: 1
   }
}
function handleFilterCheckbox(filterName, data) {
   const filter = filters[filterName];
   if(filter.includes(data)) {
      filter.splice(filter.indexOf(data), 1);
   } else {
      filter.push(data);
   }
   update();
}
function closeFilters() {
   filterContainerElement.classList.remove("extended");
}
/** PAGINATION UTILS */
function handleNextPage() {
   if(page > totalPages-1)
      return;
   handlePage(page+1);
}
function handlePreviousPage() {
   if(page <= 1)
      return;
   handlePage(page-1);
}
function handlePage(newPage) {
   page = newPage;
   update();
   window.scrollTo(0, 0);
}

/** TOGGLE DROPDOWN */
function toggleDropdown({ target }) {
   const className = target.classList.item(0);
   const element = document.querySelector("."+className+" + ul");
   if(element) {
      if(element.classList.contains("opened")) 
         element.classList.remove("opened");
      else
         element.classList.add("opened");
   }
}

/** STORE AND LOAD FILTERS, SORT AND PAGE */
function store() {
   const url = new URL(location);
   const searchParams = url.searchParams;
   const {cities, departments, priceRange} = filters;
   searchParams.set("filters", encodeURI(JSON.stringify({ cities, departments, priceRange })));
   searchParams.set("sortDir", sortDir);
   searchParams.set("page", page);

   window.history.pushState({}, null, url);
}
function load() {
   const loadedData = {};
   const url = new URL(location);
   const searchParams = url.searchParams;

   if(searchParams.has("filters")) {
      filters = searchParams.get("filters");
      filters = JSON.parse(decodeURI(filters));
   }
   if(searchParams.has("sortDir")) {
      sortDir = searchParams.get("sortDir");
   }
   if(searchParams.has("page")) {
      page = searchParams.get("page");
   }

   if(searchParams.has("searchQuery")) {
      filters.searchQuery = searchParams.get("searchQuery");
   }
   const arrivesOn = searchParams.get("arrivesOn");
   if(arrivesOn?.trim()) {
      filters.dateRange.arrivesOn = arrivesOn;
   }
   const departureOn = searchParams.get("departureOn");
   if(departureOn?.trim()) {
      filters.dateRange.departureOn = departureOn;
   }
   const travelersCount = searchParams.get("travelersCount");
   if(travelersCount?.trim()) {
      filters.nbTravelers = parseInt(travelersCount);
   }

   return loadedData;
}