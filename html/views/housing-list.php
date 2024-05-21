<?php 
   require_once("../models/AccommodationsModel.php");
   require_once("layout/header.php"); 
?>
<?php 
   $accomodations = AccommodationsModel::find(0, 10);

   $pageTitle = "Liste des logements"; 

   $cityList = array ("Crozon", "Lannion", "Morlaix", "Nantes", "Perros-Guirec", "Rennes", "Saint-Philbert-de-Grand-Lieu", "Vannes");
   $departmentList = array ("Côtes d'Armor", "Finistère", "Ille-et-Vilaine", "Loire-Atlantique", "Morbihan");
   $numberOfResults = "26";
   $housingTitleDescription = "Petite maison, proche du port";
   $housingCity = "Perros-Guirec";
   $housingDepartment = "Côtes d'Armor";
   $housingRating = "5,0";
   $pricePerNight = "60";
?>

<section id="filter-housing-container">
   <section id="filter-container" class="hidden">
      <div id="filter-title-container">
         <h1>Filtres</h1>
         <button>Tout effacer</button>
      </div>
      
      <section id="city-filter-container">
         <div>
            <h3>Communes</h3>
            <span id="city-chevron-down" class="mdi mdi-chevron-down" onclick="switchOpenClose('city-list', 'city-chevron-down', 'city-chevron-up')"></span>
            <span id="city-chevron-up" class="mdi mdi-chevron-up hidden" onclick="switchOpenClose('city-list', 'city-chevron-down', 'city-chevron-up')"></span>
         </div>
         <ul id="city-list" class="hidden">
         <?php foreach($accomodations as $accomodation) {?>
               <li>
                  <input type="checkbox" id="<?php echo strtolower(str_replace(' ', '-', $city)); ?>" name="<?php echo strtolower(str_replace(' ', '-', $city)); ?>"/>
                  <label for="<?php echo strtolower(str_replace(' ', '-', $city)); ?>"><?php echo $accomodation->get("id_logement") ?></label>
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
            <?php foreach ($departmentList as $department) {?>
               <li>
                  <input type="checkbox" id="<?php echo strtolower(str_replace(' ', '-', $department)); ?>" name="<?php echo strtolower(str_replace(' ', '-', $department)); ?>"/>
                  <label for="<?php echo strtolower(str_replace(' ', '-', $department)); ?>"><?php echo $department; ?></label>
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
         <ul id="price-min-max-container" class="hidden">
            <div>
               <input type="text" id="min-price" name="min-price" placeholder="Prix minimum"/>
            </div>
            <div>
               <input type="text" id="max-price" name="max-price" placeholder="Prix maximum"/>
            </div>
         </ul>
      </section>

      <section id="notation-filter-container">
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
      </section>
   </section>

   <section id="housing-list-container">
      <div>
         <button class="back-button"><span class="mdi mdi-arrow-left"></span>Retour</button>
      </div>
      <div id="housing-title-search-container">
         <h1>Logements (<?php echo $numberOfResults; ?>)</h1>
         <form class="compact-search-bar">
            <input type="text" placeholder="Rechercher..." class="search-input">
            <input placeholder="Date de séjour" class="arrival-date-input" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"/>
            <input type="text" placeholder="2 voyageurs" class="travelers-number-input">
            <button><span class="mdi mdi-magnify"></span></button>
         </form>
      </div>
      <div id="filter-sort-buttons-container">
         <button id="filter-button" class="primary" onclick="toggleFilterMenu()"><span class="mdi mdi-filter-variant"></span>Filtres</button>
         <button><span class="mdi mdi-sort-descending"></span>Trier par prix</button>
      </div>

      <section class="housing-list">
         <?php for ($i = 1; $i <= 10; $i++) {?>
            <article class="housing-item">
               <div class="housing-image-item-container">
                  <!-- PHP -->
                  <img src="../../images/logement-test.jpeg" alt="Logement">
               </div>
               <div class="housing-text-details">
                  <div class="housing-description-container">
                     <h4 class="housing-description"><abbr title="<?php echo $housingTitleDescription; ?>"><?php echo $housingTitleDescription; ?></abbr></h4>
                     <div class="star-notation-container">
                        <span class="mdi mdi-star"></span>
                        <h4><?php echo $housingRating; ?></h4>
                     </div>
                  </div>
                  <div class="housing-location-container">
                     <span class="mdi mdi-map-marker"></span>
                     <h4 class="housing-location"><abbr title="<?php echo $housingCity; ?>, <?php echo $housingDepartment; ?>"><?php echo $housingCity; ?>, <?php echo $housingDepartment; ?></h4>
                  </div>
                  <div class="housing-price-container">
                     <span class="housing-price"><?php echo $pricePerNight; ?>€</span><span class="per-night">par nuit</span>
                  </div>
               </div>
            </article>
         <?php } ?>

         <div class="show-all-button-container">
            <button class="show-all-button">Afficher tout<span class="mdi mdi-chevron-right"></span></button>
         </div>
      </section>
      <div>
         <ul class="pagination">
            <li><a href=""><span class="mdi mdi-chevron-left"></span></a></li>
            <?php for ($i = 1; $i <= 3; $i++) {?>
               <li class="page-number"><a href=""><?php echo $i?></a></li>
            <?php } ?>
            <li><a href=""><span class="mdi mdi-chevron-right"></a></span></li>
         </ul>
      </div>
   </section>
</section>

<script>
   function toggleFilterMenu() {
      document.getElementById("filter-container").classList.toggle("hidden");
      document.getElementById("filter-container").classList.toggle("displayed");
   }

   function switchOpenClose(menuId, chevronDownId, chevronUpId) {
      let menu = document.getElementById(menuId);
      let chevronDown = document.getElementById(chevronDownId);
      let chevronUp = document.getElementById(chevronUpId);

      menu.classList.toggle("hidden");
      menu.classList.toggle("displayed");
      chevronDown.classList.toggle("hidden");
      chevronDown.classList.toggle("displayed");
      chevronUp.classList.toggle("hidden");
      chevronUp.classList.toggle("displayed");
   }
</script>

<?php require_once("layout/footer.php"); ?>