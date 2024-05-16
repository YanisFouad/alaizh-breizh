<?php require_once("layout/header.php"); ?>

<section id="cover-search-bar">
   <div id="image-cover">
      <h1>Le rêve commence ici...</h1>
      <h2>Explorez la Bretagne en profitant de splendides vues.</h2>
   </div>
   
   <form id="search-bar">
      <input type="text" placeholder="Rechercher un séjour">
      <input type="date" name="arrival-date" id="arrival-date">
      <input type="date" name="departure-date" id="departure-date">
      <input type="text" placeholder="Nombre de personnes"><span class="mdi mdi-account"></span>
      
      <button><span class="mdi mdi-magnify"></span></button>
   </form>
</section>

<div id="list-title-block">
   <h1 id="housing-list-title">Notre sélection de logements</h1>
   <button class="show-more-button">Afficher plus</button>
</div>

<section class="housing-list">
<?php for ($i = 1; $i <= 10; $i++) {?>
   <article class="housing-item">
      <div>
         <img src="../../images/logement-test.jpeg" alt="Logement">
      </div>
      <h4 class="housing-description">Petite maison proche du port</h4>
      <h4 class="housing-location">Perros-Guirec, Côtes d'Armor</h4>
      <h4 id="housing-price">30€ par nuit</h4>
   </article>
   <?php } ?>
</section>

<?php require_once("layout/footer.php"); ?>