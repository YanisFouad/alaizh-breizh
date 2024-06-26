<?php 
   require_once(__DIR__."/../services/RequestBuilder.php");
   require_once(__DIR__."/../models/AccommodationModel.php");
   
   header('Content-Type: application/json');
   
   $content = trim(file_get_contents("php://input"));
   $response = json_decode($content, true);
   
   if (isset($response)) {
      $filteredAccommodations = array();

      $selectedTowns = $response["towns"];
      $selectedDepartments = $response["departments"];
      $priceRange = $response["priceRange"];
      $stringSearch = $response["stringSearch"];
      //$arrivalDate = $response["arrivalDate"];
      //$departureDate = $response["departureDate"];
      $travelers = $response["travelers"];

      $request = RequestBuilder::select("logement")->projection("*");
   
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
      if (isset($travelers)) { // ⭕️ TODO A CORRIGER
         $request = $request->where("max_personne_logement >= ?", $travelers);
      }

      $result = $request->execute()->fetchMany();

// je récupère les dates comme avant
// itérer sur $result qui contient les resultats des filtres
// pour récupérer l'id de chaque logement filtrés
// avec cet id, je peux recuperer toutes les reservations via le request builder
// avec ces données, on fait les conditions sur les dates (si ça correspond ou pas)
// si ça correspond pas, on retire le logement de result

      echo json_encode($result);
      exit;
   }

   // foreach($selectedTownsAndDepartments as $town) {
   //    $filteredAccommodations = getTownNames($town);
   // }
   

   // function getMaxPrice() {
   //    $result = RequestBuilder::select("logement")
   //       ->projection("MAX(prix_ttc_logement) AS max_price")
   //       ->execute()
   //       ->fetchMany();
   //       return $result["max_price"];
   // }

   //$allAccommodations = AccommodationModel::find(0, AccommodationModel::count());

   // ⭕️ TODO GÉRER LES ERREURS
   // ⭕️ TODO RETIRER LES ACCENTS
   // ⭕️ TODO "Aucun logement ne correspond à votre recherche"

   //$filteredAccommodations = array();
   //$filteredAccommodationsOnTownsAndDepts = array();

   //On a la liste des villes sélectionnées dans allTownsSelected ["array", "brest"]
   // 


   // SANS FILTRE : TOUS LES LOGEMENTS
   // if (isset($response[''])) {
   //    foreach ($allAccommodations as $accommodation) {
   //       array_push($filteredAccommodations, (array) $accommodation->getData());
   //    }
   // } 

   //print_r($selectedPriceRange);
   
   // (commune || département) && fourchette_de_prix


   // FILTRÉS PAR COMMUNES
   // if (isset($response['townsAndDepartments'])) {
   //    $selectedTownsAndDepartments = $response['townsAndDepartments'];
   //    foreach($allAccommodations as $accommodation) {
   //       if (in_array(strtolower(str_replace(' ', '-', $accommodation->get("ville_adresse"))), $selectedTownsAndDepartments) ||
   //             in_array(substr($accommodation->get("code_postal_adresse"), 0, 2), $selectedTownsAndDepartments)) {
   //             array_push($filteredAccommodationsOnTownsAndDepts, $accommodation);
   //       }
   //    }
   //    if (isset($response['priceRange'])) {
   //       $selectedPriceRange = $response['priceRange'];
   //    } else {
   //       $selectedPriceRange = array(0, 999999);
   //    }
   //    foreach($filteredAccommodationsOnTownsAndDepts as $accommodation) {
   //       if ($selectedPriceRange[0] <= $accommodation->get("prix_ttc_logement") &&
   //       $accommodation->get("prix_ttc_logement") <= $selectedPriceRange[1]) {
   //          array_push($filteredAccommodations, (array) $accommodation->getData());
   //       }
   //    }

   // }

// //var_dump($allAccommodations);

//    // FILTRÉS PAR COMMUNES
//    if (isset($response['townsAndDepartments'])) {
//       $selectedTownsAndDepartments = $response['townsAndDepartments'];
//       foreach($allAccommodations as $accommodation) {
//          if (in_array(strtolower(str_replace(' ', '-', $accommodation->get("ville_adresse"))), $selectedTownsAndDepartments) ||
//              in_array(substr($accommodation->get("code_postal_adresse"), 0, 2), $selectedTownsAndDepartments)) {
//             array_push($filteredAccommodations, $accommodation->getData());
//          }
//       }
//    }

//   // var_dump($filteredAccommodations);

//    // FILTRÉS PAR PRIX
   
//    if (isset($response['priceRange'])) {
//       $selectedPriceRange = $response['priceRange'];
//       foreach($allAccommodations as $accommodation) {
//          if ($selectedPriceRange[0] <= $accommodation->get("prix_ttc_logement") &&
//          $accommodation->get("prix_ttc_logement") <= $selectedPriceRange[1]) {
//             array_push($filteredAccommodations, (array) $accommodation->getData());
//          }
//       }
//    }
?>