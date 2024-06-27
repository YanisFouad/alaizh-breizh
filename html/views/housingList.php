<?php    
   require_once(__DIR__."/../models/AccommodationModel.php");
   require_once(__DIR__."/../services/RequestBuilder.php");
   require_once("layout/header.php"); 

   ScriptLoader::load("housingList.js");
   ScriptLoader::load("accommodationsFilters.js");
?>

<section id="filter-accommodation-container">
   <section id="filter-container">
      <div id="filter-title-container">
         <h1>Filtres</h1>
         <button class="secondary" id="clear-filters">Réinitialiser</button>
      </div>

      <div class="section">
         <h3 class="cities" onclick="toggleDropdown(event)">
            Communes
            <span class="mdi mdi-chevron-down"></span>
         </h3>

         <ul class="data" id="cities"></ul>
      </div>

      <div class="section">
         <h3 class="department" onclick="toggleDropdown(event)">
            Départements
            <span class="mdi mdi-chevron-down"></span>
         </h3>

         <ul class="data" id="departments"></ul>
      </div>

      <div class="section">
         <h3 class="price-range" onclick="toggleDropdown(event)">
            Prix
         </h3>

         <div class="data price-range">
            <input type="number" id="min-price" min="0">
            <input type="number" id="max-price" min="0">
         </div>
      </div>

      <div id="validation-filter-button-container">
         <button onclick="closeFilters()" class="secondary">Annuler</button>
         <button onclick="closeFilters()" class="primary">Valider</button>
      </div>
   </section>

   <section id="accommodation-list-container">
      <header>
         <div>
            <a href="/">
               <button type="button" class="back-button">
                     <span class="mdi mdi-arrow-left"></span>
                     Retour
               </button>
            </a>
            <h1>
               Logements (<span id="accommodation-results-number">0</span>)
            </h1>
         </div>

         <div id="accommodation-search-container">
            <div class="compact-search-bar" id="compact-search-bar">
               <div>
                  <input onfocus="renderFullSearchBar()" type="text" placeholder="Rechercher..." id="search-input-compact" class="search-input">
                  <input onfocus="renderFullSearchBar()" placeholder="Dates de séjour" id="date-input-compact" class="arrival-date-input" type="text" />
                  <input onfocus="renderFullSearchBar()" type="text" placeholder="2 voyageurs" id="travelers-input-compact" class="travelers-number-input">
               </div>
               <button onclick="renderFullSearchBar()"><span class="mdi mdi-magnify"></span></button>
            </div>
            <div class="compact-search-bar" id="full-search-bar">
               <input type="text" placeholder="Rechercher un séjour" class="search-input" id="search-query-input">
               <input placeholder="Arrivée" id="arrival-date-input" class="arrival-date-input" type="text" onfocus="focusDate(this)" onblur="unfocusDate(this)">
               <input placeholder="Départ" id="departure-date-input" class="departure-date-input" type="text" onfocus="focusDate(this)" onblur="unfocusDate(this)">
               <input id="travelers-number-input" class="travelers-number-input" type="number" placeholder="Nombre de voyageurs" min="1">
               <button id="search-button" type="button">
                  <span class="mdi mdi-magnify"></span>
               </button>
            </div>
         </div>
      </header>

      <div id="filter-sort-buttons-container">
         <button id="filter-button" class="primary">
            <span class="mdi mdi-filter-variant"></span>
            Filtres
         </button>
         <button id="sort-btn">
            <span class="mdi mdi-sort-descending"></span>
            <span class="label"></span>
         </button>
      </div>

      <section id="accommodation-list"></section>
      <div id="no-accommodation-result-area">
         <h1>Aucun logement trouvé !</h1>
      </div>

      <div class="pagination" id="pagination"></div>
   </section>
</section>

<?php require_once("layout/footer.php"); ?>