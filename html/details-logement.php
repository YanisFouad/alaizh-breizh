<?php require_once("views/layout/header.php"); ?>

<div>
   <div id="image-cover">
   <h1>Le rêve commence ici...</h1>
   <p>Visitez et explorez la Bretagne en profitant de splendides vues.</p>
</div>
   
   <form id="search-bar">
      <input type="text" placeholder="Rechercher un séjour">
      <input type="date" name="arrival-date" id="arrival-date">
      <input type="date" name="departure-date" id="departure-date">
      <select name="" id="">
         <?php for ($i = 1; $i <= 8; $i++) {?>
            <option value="<?php echo $i ?>"><?php echo $i ?></option>
         <?php } ?>
      </select>
      <button></button>
   </form>
</div>

<h1>Notre sélection de logements</h1>

<button>Afficher plus</button>

<section>
   <article>
      <img src="images/logement-test.png" alt="Logement" height="150px" width="150px">
      <p>Petite maison proche du port</p>
      <p>Perros-Guirec, Côtes d'Armor</p>
      <p>30€ par nuit</p>
   </article>
</section>

<?php require_once("views/layout/footer.php"); ?>