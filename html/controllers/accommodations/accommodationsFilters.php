<?php 
   require_once(__DIR__."/../../services/RequestBuilder.php");
   require_once(__DIR__."/../../models/AccommodationModel.php");
   require_once(__DIR__."/../../helpers/globalUtils.php");
   
   header('Content-Type: application/json');
   
   $content = trim(file_get_contents("php://input"));
   $response = json_decode($content, true);
   

   if (isset($response)) {
      $filteredAccommodations = array();

      $selectedTowns = $response["towns"];
      $selectedDepartments = $response["departments"];
      $priceRange = $response["priceRange"];
      $stringSearch = $response["stringSearch"] ?? NULL;
      $arrivalDate = $response["arrivalDate"] ?? NULL;
      $departureDate = $response["departureDate"] ?? NULL;
      $travelers = $response["travelers"];
      $offset = $response["offset"];
      $limit = $response["limit"];
      $sortDir = $response["sortDir"]??"DESC";

      $request = RequestBuilder::select("logement")
         ->distinct()
         ->projection("logement.id_logement AS id_logement, titre_logement, photo_logement, code_postal_adresse, ville_adresse, prix_ttc_logement");
   
      // FILTRE COMMUNES
      if (count($selectedTowns) > 0) {
         $in = str_repeat('?,', count($selectedTowns) - 1) . '?';
         $request = $request->where("ville_adresse IN (".$in.")", ...$selectedTowns);
      }

      // FILTRE DÉPARTEMENTS
      if (count($selectedDepartments) > 0) {
         $in = str_repeat('?,', count($selectedDepartments) - 1) . '?';
         $request = $request->where("SUBSTR(code_postal_adresse, 1, 2) IN (".$in.")", ...$selectedDepartments);
      }

      // FILTRE PRIX
      if (count($priceRange) > 0) {
         if (isset($priceRange[0])) {
            $request = $request->where("prix_ttc_logement >= ?", $priceRange[0]);
         }
         if (isset($priceRange[1])) {
            $request = $request->where("prix_ttc_logement <= ?", $priceRange[1]);
         }
      }

      // FILTRE RECHERCHE TEXTE
      if (strlen($stringSearch) > 0) {
         $request = $request->where("
            (LOWER(titre_logement) LIKE ? OR 
            LOWER(ville_adresse) LIKE ? OR
            LOWER(categorie_logement) LIKE ?)", 
            '%'. $stringSearch . '%', 
            '%'. $stringSearch . '%', 
            '%'. $stringSearch . '%'
         );
      }

      // FILTRE DATE D'ARRIVÉE


      // FILTRE NOMBRE DE VOYAGEURS
      if (isset($travelers)) {
         $request = $request->where("max_personne_logement >= ?", $travelers);
      }

      if(isset($departureDate) && isset($arrivalDate)) {
         $request = $request
            ->innerJoin("_reservation", "logement.id_logement = _reservation.id_logement")
            ->except("
               SELECT logement.id_logement AS id_logement, titre_logement, photo_logement, code_postal_adresse, ville_adresse, prix_ttc_logement 
               FROM logement
               INNER JOIN _reservation ON _reservation.id_logement = logement.id_logement
               WHERE ((date_arrivee > ?) AND (date_depart < ?))
            ", $arrivalDate, $departureDate);
      }

      $request = $request->sortBy("prix_ttc_logement", $sortDir);
      $result = $request->execute()->fetchMany();

      // totalCount
      $totalCount = count($result);
      // items
      $items = array_slice($result, $offset, $limit);

      $communes = array_map(function($r) {
         return $r["ville_adresse"];
      }, $result);

// je récupère les dates comme avant
// itérer sur $result qui contient les resultats des filtres
// pour récupérer l'id de chaque logement filtrés
// avec cet id, je peux recuperer toutes les reservations via le request builder
// avec ces données, on fait les conditions sur les dates (si ça correspond ou pas)
// si ça correspond pas, on retire le logement de result

// pour chaque réservation ayant cet ID
// si la date d'arrivée choisie n'est pas comprise dans la période de réservation
// ET 
// (si la date d'arrivée est antérieure à la période de réservation ET que la date de départ est aussi antérieure
// OU que la date d'arrivée est postérieure à la période de réservation ET que la date de départ est aussi postérieure)

      send_json_response([
         "items" => $items,
         "communes" => $communes,
         "totalCount" => $totalCount
      ]);
      exit;
   }
?>