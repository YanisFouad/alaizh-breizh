<?php    
   require_once(__DIR__."/../models/AccommodationModel.php");
   require_once(__DIR__."/../services/RequestBuilder.php");
   require_once("layout/header.php"); 
   
   $totalAccommodations = AccommodationModel::count();
   // tous les logements résultant de la recherche
   $allAccommodations = AccommodationModel::find(0, $totalAccommodations);

   function getDepartmentName($postCode) {
      $result = RequestBuilder::select("pls._departement")
         ->projection("nom_departement")
         ->where("num_departement = ?", $postCode)
         ->execute()
         ->fetchOne();
      return $result["nom_departement"];
   }

   function getMinPrice() {
      $result = RequestBuilder::select("logement")
         ->projection("MIN(prix_ttc_logement) AS min_price")
         ->execute()
         ->fetchOne();
         return $result["min_price"];
   }

   function getMaxPrice() {
      $result = RequestBuilder::select("logement")
         ->projection("MAX(prix_ttc_logement) AS max_price")
         ->execute()
         ->fetchOne();
         return $result["max_price"];
   }

   ScriptLoader::load("housingList.js");
   ScriptLoader::load("accommodationsFilters.js");
?>

<section id="filter-housing-container">
   <section id="filter-container" class="hidden">
      <div id="filter-title-container">
         <h1>Filtres</h1>
         <!-- ⭕️ TODO PERDRE LE FOCUS -->
         <button class="secondary" id="clear-all-filters-button">Réinitialiser</button>
      </div>
      
      <section id="city-filter-container">
         <div>
            <!-- Fermé de base parce que la liste peut être longue... -->
            <h3>Communes</h3>
            <span id="city-chevron-down" class="mdi mdi-chevron-down hidden" onclick="switchOpenClose('city-list', 'city-chevron-down', 'city-chevron-up')"></span>
            <span id="city-chevron-up" class="mdi mdi-chevron-up" onclick="switchOpenClose('city-list', 'city-chevron-down', 'city-chevron-up')"></span>
         </div>
         <ul id="city-list" class="hidden">
            <?php 
               $cityArray = array();
               foreach($allAccommodations as $accommodation) {
                  if (!in_array($accommodation->get("ville_adresse"), $cityArray)) {
                     array_push($cityArray, $accommodation->get("ville_adresse"));
                  }
               } 
               sort($cityArray);
               foreach($cityArray as $city) {?>
                  <li>
                     <input 
                        type="checkbox"
                        class="town-checkboxes"  
                        id="<?php echo $city; ?>" 
                        name="<?php echo $city; ?>"/>
                     <label for="<?php echo $city; ?>"><?php echo $city ?></label>
                  </li>
               <?php } ?>
         </ul>
      </section>

      <section id="department-filter-container">
         <div>
            <h3>Départements</h3>
            <span id="department-chevron-down" class="mdi mdi-chevron-down" onclick="switchOpenClose('department-list', 'department-chevron-down', 'department-chevron-up')"></span>
            <span id="department-chevron-up" class="mdi mdi-chevron-up hidden" onclick="switchOpenClose('department-list', 'department-chevron-down', 'department-chevron-up')"></span>
         </div>
         <ul id="department-list" class="displayed">
            <?php 
            $postCodeDepartmentArray = array();
            foreach($allAccommodations as $accommodation) {
               if (!in_array(substr($accommodation->get("code_postal_adresse"), 0, 2), $postCodeDepartmentArray)) {
                  array_push($postCodeDepartmentArray, substr($accommodation->get("code_postal_adresse"), 0, 2));
               }
            } 
            sort($postCodeDepartmentArray);
            foreach($postCodeDepartmentArray as $postCode) { ?>
               <li>
                  <input
                     type="checkbox" 
                     class="department-checkboxes" 
                     id="<?php echo strtolower(getDepartmentName($postCode)); ?>" 
                     name="<?php echo strtolower(getDepartmentName($postCode)); ?>"
                     data-postcode="<?php echo $postCode; ?>"/>
                  <label for="<?php echo strtolower(getDepartmentName($postCode)); ?>"><?php echo getDepartmentName($postCode) ?></label>
               </li>
            <?php } ?> 
         </ul>
      </section>

      <!-- ⭕️ TODO INTERDIRE LA SAISIE D'AUTRE CHOSE QUE DES CHIFFRES -->
      <section id="price-filter-container">
         <div>
            <h3>Prix</h3>
            <span id="price-chevron-down" class="mdi mdi-chevron-down" onclick="switchOpenClose('price-min-max-container', 'price-chevron-down', 'price-chevron-up')"></span>
            <span id="price-chevron-up" class="mdi mdi-chevron-up hidden" onclick="switchOpenClose('price-min-max-container', 'price-chevron-down', 'price-chevron-up')"></span>
         </div>
         <ul id="price-min-max-container" class="displayed">
            <!-- ⭕️ TODO GÉRER LE PRIX MAX -->   
            <div>
               <input 
                  type="number" 
                  class="price-input"
                  id="min-price" 
                  name="min-price" 
                  min="0"
                  data-minprice="<?php echo getMinPrice() ?>"
                  placeholder="Prix minimum : <?php echo getMinPrice() ?>€"/>
            </div>
            <div>
               <input 
                  type="number" 
                  class="price-input"
                  id="max-price" 
                  name="max-price" 
                  min="0"
                  data-maxprice="<?php echo getMaxPrice() ?>"
                  placeholder="Prix maximum : <?php echo getMaxPrice() ?>€"/>
            </div>
         </ul>
      </section>

      <div id="validation-filter-button-container">
         <button class="secondary">Annuler</button>
         <button class="primary">Valider</button>
      </div>
   </section>



   <section id="accommodation-list-container">
      <form onsubmit="return false;">
        <button type="button" class="back-button" onclick="history.go(-1)">
            <span class="mdi mdi-arrow-left"></span>Retour
        </button>
      </form>

      <div id="accommodation-title-search-container">
         <!-- Nombre de résultats affiché ici : Logements (35) -->
         <form class="compact-search-bar" id="compact-search-bar">
            <div>
               <input type="text" placeholder="Rechercher..." id="search-input-compact" class="search-input">
               <!-- ⭕️ TODO DÉROULER LA BARRE DE RECHERCHE -->
               <input placeholder="Dates de séjour" id="date-input-compact" class="arrival-date-input" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"/>
               <input type="text" placeholder="2 voyageurs" id="travelers-input-compact" class="travelers-number-input">
            </div>
            <button><span class="mdi mdi-magnify"></span></button>
         </form> 
      </div>

      <div id="filter-sort-buttons-container">
         <button id="filter-button" class="primary" onclick="toggleFilterMenu()"><span class="mdi mdi-filter-variant"></span>Filtres</button>
         <button><span class="mdi mdi-sort-descending"></span>Trier par prix</button>
      </div>

      <section id="accommodation-list">
      </section>
      <div id="no-accommodation-result-area"></div>

         <!-- ⭕️ TODO NE PAS AFFICHER SI AUCUN RÉSULTAT
               GÉRER ERREUR (MAUVAISE PAGE EN DUR DANS L'URL) -->
         <div class="pagination" id="pagination">
            <!-- Chevron de gauche -->
            <button class="secondary action" id="left-pagination-button" name="page">
               <span class="mdi mdi-chevron-left"></span>
            </button>

            <!-- Boutons contenant les numéros de pages -->
            
            <!-- Chevron de droite -->
            <button class="secondary action" id="right-pagination-button" name="page">
               <span class="mdi mdi-chevron-right"></span>
            </button>
         </div>
      </div>
   </section>
</section>

<!-- ⭕️ TODO PROBLÈME DE FOOTER QUI PASSE PAR-DESSUS -->

<?php require_once("layout/footer.php"); ?>