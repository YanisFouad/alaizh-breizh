<?php 
   require_once("layout/header.php");
   require_once(__DIR__."/../services/RequestBuilder.php");
   require_once(__DIR__."/../models/AccommodationModel.php");

   $accommodations = AccommodationModel::find(0, 10);

   function getDepartmentName($postCode) {
      $result = RequestBuilder::select("pls._departement")
          ->projection("nom_departement")
          ->where("num_departement = ?", $postCode)
          ->execute()
          ->fetchOne();
      return $result["nom_departement"];
   }
?>

<section class="home-and-accommodations-sections" id="home-page">
   <section id="cover-search-bar">
      <div id="image-cover">
         <h1>Le rêve commence ici...</h1>
         <h2>Explorez la Bretagne en profitant de splendides vues.</h2>
      </div>
      
      <form id="search-bar">
         <input type="text" placeholder="Rechercher un séjour" class="search-input">
         <input placeholder="Arrivée" class="arrival-date-input" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"/>
         <input placeholder="Départ" class="departure-date-input" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"/>
         <input type="text" placeholder="Nombre de voyageurs" class="travelers-number-input"/><!--<span class="mdi mdi-account"></span>-->
         <button class="is-disabled"><span class="mdi mdi-magnify"></span></button>
      </form>
   </section>

   <section id="accommodation-list-container">

      <div id="list-title-container">
         <h1 id="accommodation-list-title">Notre sélection de logements</h1>
      </div>
      <div class="show-more-button-container">
         <a href="/logements"><button class="secondary show-more-button">Afficher plus<span class="mdi mdi-chevron-right"></span></button></a>
      </div>

      <section id="accommodation-list">
         <?php foreach($accommodations as $accommodation) {?>
            <a href="/logement?id_logement=<?=$accommodation->get("id_logement")?>">
               <article class="accommodation-item">
                  <div class="accommodation-image-item-container">
                     <img src="<?php echo $accommodation->get("photo_logement")?>" alt="Photo du logement">

                  </div>
                  <div class="accommodation-text-details">
                     <div class="accommodation-description-container">
                        <h4 class="accommodation-description"><abbr title="<?php echo $accommodation->get("titre_logement"); ?>"><?php echo $accommodation->get("titre_logement"); ?></abbr></h4>
                     </div>
                     <div class="accommodation-location-container">
                        <span class="mdi mdi-map-marker"></span>
                        <h4 class="accommodation-location"><abbr title="<?php echo $accommodation->get("ville_adresse"); ?>, <?php echo getDepartmentName(substr($accommodation->get("code_postal_adresse"), 0, 2)) ?>"><?php echo $accommodation->get("ville_adresse"); ?>, <?php echo getDepartmentName(substr($accommodation->get("code_postal_adresse"), 0, 2)) ?></h4>
                     </div>
                     <div class="accommodation-price-container">
                        <span class="accommodation-price"><?php echo $accommodation->get("prix_ht_logement"); ?>€</span><span class="per-night">par nuit</span>
                     </div>
                  </div>
               </article>
            </a>
         <?php } ?>

         <div class="show-all-button-container">
            <a href="/logements"><button class="show-all-button">Afficher tout<span class="mdi mdi-chevron-right"></span></button>
      </section>
   </section>
</section>

<?php require_once("layout/footer.php"); ?>