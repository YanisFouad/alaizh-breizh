<?php 
   $pageTitle = "Page d'accueil"; 

   require_once("layout/header.php");
   require_once(__DIR__."/../services/RequestBuilder.php");
   require_once(__DIR__."/../models/AccommodationModel.php");

   $accomodations = AccommodationModel::find(0, 10);

   function getDepartmentName($postCode) {
      $result = RequestBuilder::select("pls._departement")
          ->projection("nom_departement")
          ->where("num_departement = ?", $postCode)
          ->execute()
          ->fetchOne();
      return $result["nom_departement"];
   }
?>

<section id="home-page">
   <section id="cover-search-bar">
      <div id="image-cover">
         <h1>Le rêve commence ici...</h1>
         <h2>Explorez la Bretagne en profitant de splendides vues.</h2>
      </div>
      
      <form id="search-bar">
         <input disabled type="text" placeholder="Rechercher un séjour" class="search-input">
         <input disabled placeholder="Arrivée" class="arrival-date-input" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"/>
         <input disabled placeholder="Départ" class="departure-date-input" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"/>
         <input disabled type="text" placeholder="Nombre de voyageurs" class="travelers-number-input"/><!--<span class="mdi mdi-account"></span>-->
         <button disabled class="is-disabled"><span class="mdi mdi-magnify"></span></button>
      </form>
   </section>

   <section id="housing-list-container">

      <div id="list-title-container">
         <h1 id="housing-list-title">Notre sélection de logements</h1>
      </div>
      <div class="show-more-button-container">
         <a href="./housing-list.php"><button class="secondary show-more-button">Afficher plus<span class="mdi mdi-chevron-right"></span></button></a>
      </div>

      <section class="housing-list">
         <?php foreach($accomodations as $accomodation) {?>
            <article class="housing-item">
               <div class="housing-image-item-container">
                  <!-- ⭕️ À REMPLACER PAR LE BON SERVICE -->
                  <img src="<?php echo $accomodation->get("photo_logement")?>" alt="Logement">

               </div>
               <div class="housing-text-details">
                  <div class="housing-description-container">
                     <h4 class="housing-description"><abbr title="<?php echo $accomodation->get("titre_logement"); ?>"><?php echo $accomodation->get("titre_logement"); ?></abbr></h4>
                     <!-- NOTATION // RETIRÉE
                     <div class="star-notation-container">
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
         <?php } ?>

         <div class="show-all-button-container">
            <a href="./housing-list.php"><button class="show-all-button">Afficher tout<span class="mdi mdi-chevron-right"></span></button>
      </section>
   </section>
</section>

<?php require_once("layout/footer.php"); ?>