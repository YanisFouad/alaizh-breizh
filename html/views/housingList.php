<?php    
   require_once(__DIR__."/../models/AccommodationModel.php");
   require_once(__DIR__."/../services/RequestBuilder.php");
   require_once("layout/header.php"); 
   
   // Paramètres de pagination
   $articlesParPage = 10;
   $currentPage = isset($_GET['page']) && is_numeric($_GET["page"]) ? intval($_GET['page']) : 1;
   
   // Calculer l'indice de début pour la pagination
   $indiceDebut = ($currentPage - 1) * $articlesParPage;
   $accomodations = AccommodationModel::find(($currentPage - 1) * $articlesParPage, $articlesParPage);
 
   $totalAccomodations = AccommodationModel::count();
   $totalPages = ceil($totalAccomodations / $articlesParPage);

   function getDepartmentName($postCode) {
      $result = RequestBuilder::select("pls._departement")
          ->projection("nom_departement")
          ->where("num_departement = ?", $postCode)
          ->execute()
          ->fetchOne();
      return $result["nom_departement"];
   }

   ScriptLoader::load("housingList.js")
?>

<section id="filter-housing-container">
   <form id="filter-container" class="hidden">
      <div id="filter-title-container">
         <h1>Filtres</h1>
         <button disabled class="secondary">Tout effacer</button>
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
            foreach($accomodations as $accomodation) {
               if (!in_array($accomodation->get("ville_adresse"), $cityArray)) {
                  array_push($cityArray, $accomodation->get("ville_adresse"));
               }
            } 
            sort($cityArray);
            foreach($cityArray as $city) {?>
               <li>
                  <input disabled type="checkbox" id="<?php echo strtolower(str_replace(' ', '-', $accomodation->get("ville_adresse"))); ?>" name="<?php echo strtolower(str_replace(' ', '-', $accomodation->get("ville_adresse"))); ?>"/>
                  <label for="<?php echo strtolower(str_replace(' ', '-', $accomodation->get("ville_adresse"))); ?>"><?php echo $city ?></label>
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
            foreach($accomodations as $accomodation) {
               if (!in_array(substr($accomodation->get("code_postal_adresse"), 0, 2), $postCodeDepartmentArray)) {
                  array_push($postCodeDepartmentArray, substr($accomodation->get("code_postal_adresse"), 0, 2));
               }
            } 
            sort($postCodeDepartmentArray);
            foreach($postCodeDepartmentArray as $postCode) {?>
               <li>
                  <input disabled type="checkbox" id="<?php echo strtolower(str_replace(' ', '-', getDepartmentName($postCode))); ?>" name="<?php echo strtolower(str_replace(' ', '-', getDepartmentName($postCode))); ?>"/>
                  <label for="<?php echo strtolower(str_replace(' ', '-', getDepartmentName($postCode))); ?>"><?php echo getDepartmentName($postCode) ?></label>
               <!-- ⭕️ "Ille-et-Vilaine" / "Côtes d'Armor" CORRIGÉS ? --> 
               </li>
            <?php } ?>
         </ul>
      </section>

      <section id="price-filter-container">
         <div>
            <h3>Prix</h3>
            <span id="price-chevron-down" class="mdi mdi-chevron-down" onclick="switchOpenClose('price-min-max-container', 'price-chevron-down', 'price-chevron-up')"></span>
            <span id="price-chevron-up" class="mdi mdi-chevron-up hidden" onclick="switchOpenClose('price-min-max-container', 'price-chevron-down', 'price-chevron-up')"></span>
         </div>
         <ul id="price-min-max-container" class="displayed">
            <div>
               <input disabled type="text" id="min-price" name="min-price" placeholder="Prix minimum"/>
            </div>
            <div>
               <input disabled type="text" id="max-price" name="max-price" placeholder="Prix maximum"/>
            </div>
         </ul>
      </section>

      <!--<section id="notation-filter-container" class="hidden">
         <div>
            <h3>Note</h3>
            <span id="notation-chevron-down" class="mdi mdi-chevron-down" onclick="switchOpenClose('stars-notation-container', 'notation-chevron-down', 'notation-chevron-up')"></span>
            <span id="notation-chevron-up" class="mdi mdi-chevron-up hidden" onclick="switchOpenClose('stars-notation-container', 'notation-chevron-down', 'notation-chevron-up')"></span>
         </div>
         <ul id="stars-notation-container" class="hidden">
            <li class="stars-notation">
                  <a href="">
                     <span class="mdi mdi-star"></span>
                     <span class="mdi mdi-star"></span>
                     <span class="mdi mdi-star"></span>
                     <span class="mdi mdi-star"></span>
                     <span>& plus</span>
                  </a>
               </li>
               <li class="stars-notation">
                  <a href="">
                     <span class="mdi mdi-star"></span>
                     <span class="mdi mdi-star"></span>
                     <span class="mdi mdi-star"></span>
                     <span>& plus</span>
                  </a>
               </li>
               <li class="stars-notation">  
                  <span class="mdi mdi-star"></span>
                  <span class="mdi mdi-star"></span>
                  <span>& plus</span>
               </li>
               <li class="stars-notation">  
                  <span class="mdi mdi-star"></span>
                  <span>& plus</span>
               </li>
         </ul>
      </section>-->
      <div id="validation-filter-button-container">
         <button class="secondary">Annuler</button>
         <button disabled class="primary">Valider</button>
      </div>
   </form>



   <section id="housing-list-container">
      <form onsubmit="return false;">
        <button type="button" class="back-button" onclick="history.go(-1)">
            <span class="mdi mdi-arrow-left"></span>Retour
        </button>
    </form>
      <div id="housing-title-search-container">
         <h1>Logements (<?php echo $totalAccomodations; ?>)</h1>

         <form class="compact-search-bar">
            <div>
               <input disabled type="text" placeholder="Rechercher..." id="search-input-compact" class="search-input">
               <input disabled placeholder="Dates de séjour" id="date-input-compact" class="arrival-date-input" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"/>
               <input disabled type="text" placeholder="2 voyageurs" id="travelers-input-compact" class="travelers-number-input">
            </div>
            <button disabled><span class="mdi mdi-magnify"></span></button>
         </form> 

      </div>

      <div id="filter-sort-buttons-container">
         <!-- onclick="toggleFilterMenu()" -->
         <button id="filter-button" class="primary" onclick="toggleFilterMenu()"><span class="mdi mdi-filter-variant"></span>Filtres</button>
         <button disabled><span class="mdi mdi-sort-descending"></span>Trier par prix</button>
      </div>


      <section class="housing-list">
         <?php foreach($accomodations as $accomodation) {?>
            <a href="/logement?id_logement=<?=$accomodation->get("id_logement")?>">
               <article class="housing-item">
                  <div class="housing-image-item-container">
                     <img src="<?=$accomodation->get("photo_logement")?>" alt="Logement">
                  </div>
                  <div class="housing-text-details">
                     <div class="housing-description-container">
                        <h4 class="housing-description"><abbr title="<?php echo $accomodation->get("titre_logement"); ?>"><?php echo $accomodation->get("titre_logement"); ?></abbr></h4>
                        <!-- NOTATION // RETIRÉE
                           <div class="star-notation-container hidden">
                           <span class="mdi mdi-star"></span>
                           <h4><?php //echo $housingRating; ?></h4>
                        </div>
                        -->
                     </div>
                     <div class="housing-location-container">
                        <span class="mdi mdi-map-marker"></span>
                        <h4 class="housing-location"><abbr title="<?php echo $accomodation->get("ville_adresse"); ?>, <?php echo getDepartmentName(substr($accomodation->get("code_postal_adresse"), 0, 2)) ?>"><?php echo $accomodation->get("ville_adresse"); ?>, <?php echo getDepartmentName(substr($accomodation->get("code_postal_adresse"), 0, 2)) ?></h4>
                     </div>
                     <div class="housing-price-container">
                        <span class="housing-price"><?php echo $accomodation->get("prix_ht_logement"); ?>€</span><span class="per-night">par nuit</span>
                     </div>
                  </div>
               </article>
            </a>
         <?php } ?>

         <div class="show-all-button-container">
            <button class="show-all-button">Afficher tout<span class="mdi mdi-chevron-right"></span></button>
         </div>
      </section>

      <div>
         <?php 
            //echo $currentPage . " " . $totalPages;
         //$explodedPageNumberUrl = explode("=", $_SERVER['QUERY_STRING']);
         //$currentPageNumber = $explodedPageNumberUrl[1];
         ?>

         <form class="pagination">

            <!-- Premier bouton chevron -->
            <button <?php if ($currentPage == 1) {echo "disabled";}?> class="secondary" name="page" value="<?php echo $currentPage - 1 ?>">
               <span class="mdi mdi-chevron-left"></span>
            </button>

            <!-- Bouton contenant les numéros de pages -->
            <?php for($i = $currentPage-1; $i < $currentPage+2; $i++) { 
               if($i>0 && $i<=$totalPages){ 
                  ?>
                  <button class="<?=$i==$currentPage ? "primary" : "secondary"?>" name="page" value="<?php echo $i?>">
                     <span><?php echo $i?></span>
                  </button>
               <?php }
            } ?>

            <!-- Dernier bouton chevron -->
            <button <?php if ($currentPage == $totalPages) {echo "disabled";}?> class="secondary" name="page" value="<?php echo $currentPage + 1 ?>">
               <span class="mdi mdi-chevron-right"></span>
            </button>
         </form>
      </div>
   </section>
</section>

<?php require_once("layout/footer.php"); ?>