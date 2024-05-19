<?php require_once("layout/header.php"); ?>

<section>
   <section id="filter-container">
      <h1>Filtres</h1>
      <button>Tout effacer</button>
      


   </section>

   <section id="housing-list-container">

      <button id="back-button"><span class="mdi mdi-arrow-left"></span>Retour</button>
      <h1>Logements (21)</h1>
      <button>Filtres</button> <button>Trier par prix</button>

      <section class="housing-list">
         <?php for ($i = 1; $i <= 10; $i++) {?>
            <article class="housing-item">
               <div class="housing-image-item-container">
                  <img src="../../images/logement-test.jpeg" alt="Logement">
               </div>
               <div class="housing-text-details">
                  <div class="housing-description-container">
                     <h4 class="housing-description"><abbr title="Petite maison proche du port">Petite maison proche du port</abbr></h4>
                     <div class="star-notation-container">
                        <span class="mdi mdi-star"></span>
                        <h4>4,9</h4>
                     </div>
                  </div>
                  <div class="housing-location-container">
                     <span class="mdi mdi-map-marker"></span>
                     <h4 class="housing-location"><abbr title="Perros-Guirec, Côtes d'Armor">Perros-Guirec, Côtes d'Armor</h4>
                  </div>
                  <div class="housing-price-container">
                     <span class="housing-price">30€</span><span class="per-night">par nuit</span>
                  </div>
               </div>
            </article>
         <?php } ?>

         <div class="show-all-button-container">
            <button class="show-all-button">Afficher tout<span class="mdi mdi-chevron-right"></span></button>
         </div>
      </section>

      <ul class="pagination">
         <li><span class="mdi mdi-chevron-left"></span></li>
         <?php for ($i = 1; $i <= 3; $i++) {?>
            <li class="page_number"><?php echo $i?></li>
         <?php } ?>
         <li><span class="mdi mdi-chevron-right"></span></li>
      </ul>
   </section>
</section>

<?php require_once("layout/footer.php"); ?>