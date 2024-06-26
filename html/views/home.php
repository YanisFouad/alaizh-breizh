<?php 
   require_once("layout/header.php");
   require_once(__DIR__."/../services/RequestBuilder.php");
   require_once(__DIR__."/../models/AccommodationModel.php");

   $accommodations = AccommodationModel::find(0, 10);

   function getDepartmentName($postCode) {
      $result = RequestBuilder::select("_departement")
          ->projection("nom_departement")
          ->where("num_departement = ?", $postCode)
          ->execute()
          ->fetchOne();
      return $result["nom_departement"];
   }
?>

<main id="home">
   <div class="hero-header">
      <div class="image-cover">
         <div class="title">
            <h1>Le rêve commence ici...</h1>
            <h2>Explorez la Bretagne en profitant de splendides vues.</h2>
         </div>
         <div class="image"></div>
      </div>
      
      <form id="search-bar" method="GET" action="/logements">
         <input name="searchQuery" type="text" placeholder="Rechercher un séjour" class="search-input">
         <input name="arrivesOn" placeholder="Arrivée" class="arrival-date-input" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"/>
         <input name="departureOn" placeholder="Départ" class="departure-date-input" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"/>
         <input name="travelersCount" type="text" placeholder="Nombre de voyageurs" class="travelers-number-input"/><!--<span class="mdi mdi-account"></span>-->
         <button class="primary frontoffice"><span class="mdi mdi-magnify"></span></button>
      </form>
      <form id="search-bar" class="responsive">
         <input disabled type="text" placeholder="Rechercher..." class="search-input">
         <input disabled placeholder="Date arrivée/départ" class="departure-arrival-date-input" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"/>
         <button><span class="mdi mdi-magnify"></span></button>
      </form>
   </div>

   <section class="accommodations">
      <header>
         <h1>Notre sélection de logements</h1>
         <a href="/logements">
            <button class="secondary">
               Afficher plus<span class="mdi mdi-chevron-right"></span>
            </button>
         </a>
      </header>

      <section>
         <?php foreach($accommodations as $accomodation) {?>
            <a href="/logement?id_logement=<?=$accomodation->get("id_logement")?>">
               <article class="item">
                  <div class="img-container">
                     <img src="<?=$accomodation->get("photo_logement")?>" alt="Logement">
                  </div>
                  <footer>
                     <h4 class="title" title="<?=$accomodation->get("titre_logement")?>">
                        <?=$accomodation->get("titre_logement"); ?>
                     </h4>
                     <h4 class="localization" title="<?=$accomodation->get("ville_adresse")?>">
                        <span class="mdi mdi-map-marker"></span>
                        <?=$accomodation->get("ville_adresse"); ?>
                     </h4>
                     <h4 class="price" title="<?=$accomodation->get("prix_ht_logement")?>€ par nuit">
                        <span><?=$accomodation->get("prix_ht_logement")?> €</span>
                        <span>par nuit</span>
                     </h4>
                  </footer>
               </article>
            </a>
         <?php } ?>
      </section>

      <footer>
         <a href="/logements">
            <button class="primary">
               Afficher tout<span class="mdi mdi-chevron-right"></span>
            </button>
         </a>
      </footer>
   </section>
</main>

<?php require_once("layout/footer.php"); ?>