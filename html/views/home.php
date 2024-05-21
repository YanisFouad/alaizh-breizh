<?php 
   $pageTitle = "Page d'accueil"; 

   $housingTitleDescription = "Petite maison, proche du port";
   $housingCity = "Perros-Guirec";
   $housingDepartment = "Côtes d'Armor";
   $housingRating = "5,0";
   $pricePerNight = "60";
?>
<?php require_once("layout/header.php"); ?>


<section id="home-page">
   <section id="cover-search-bar">
      <div id="image-cover">
         <h1>Le rêve commence ici...</h1>
         <h2>Explorez la Bretagne en profitant de splendides vues.</h2>
      </div>
      
      <form id="search-bar">
         <input type="text" placeholder="Rechercher un séjour" class="search-input">
         <input placeholder="Arrivée" class="arrival-date-input" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"/>
         <input placeholder="Départ" class="departure-date-input" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" />
         <input type="text" placeholder="Nombre de personnes" class="travelers-number-input"><span class="mdi mdi-account"></span>
         <button><span class="mdi mdi-magnify"></span></button>
      </form>
   </section>

   <section id="housing-list-container">

      <div id="list-title-container">
         <h1 id="housing-list-title">Notre sélection de logements</h1>
      </div>
      <div class="show-more-button-container">
         <button class="show-more-button">Afficher plus<span class="mdi mdi-chevron-right"></span></button>
      </div>

      <section class="housing-list">
         <!-- PHP -->
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
   </section>
</section>

<?php require_once("layout/footer.php"); ?>