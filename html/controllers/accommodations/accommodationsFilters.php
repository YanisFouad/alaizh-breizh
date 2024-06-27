<?php
   require_once(__DIR__."/../../services/RequestBuilder.php");
   require_once(__DIR__."/../../models/AccommodationModel.php");
   require_once(__DIR__."/../../helpers/globalUtils.php");
   

   $content = trim(file_get_contents("php://input"));
   $response = json_decode($content, true);

   if(isset($response)) {
      extract($response);
      
      $request = RequestBuilder::select("logement")
         ->distinct()
         ->projection("logement.id_logement AS id_logement, titre_logement, photo_logement, nom_departement, ville_adresse, prix_ttc_logement")
         ->innerJoin("_departement", "SUBSTR(code_postal_adresse, 1, 2) = _departement.num_departement");
   
      // FILTRE COMMUNES
      if (count($cities) > 0) {
         $in = str_repeat('?,', count($cities) - 1) . '?';
         $request = $request->where("ville_adresse IN (".$in.")", ...$cities);
      }

      // FILTRE DÉPARTEMENTS
      if (count($departments) > 0) {
         $in = str_repeat('?,', count($departments) - 1) . '?';
         $request = $request->where("nom_departement IN (".$in.")", ...$departments);
      }

      // FILTRE PRIX
      if (isset($priceRange["min"])) {
         $request = $request->where("prix_ttc_logement >= ?", $priceRange["min"]);
      }
      if (isset($priceRange["max"])) {
         $request = $request->where("prix_ttc_logement <= ?", $priceRange["max"]);
      }

      // FILTRE RECHERCHE TEXTE
      if (isset($searchQuery) && strlen($searchQuery) > 0) {
         $request = $request->where("
            (LOWER(titre_logement) LIKE ? OR 
            LOWER(ville_adresse) LIKE ? OR
            LOWER(categorie_logement) LIKE ?)", 
            '%'. $searchQuery . '%', 
            '%'. $searchQuery . '%', 
            '%'. $searchQuery . '%'
         );
      }

      // FILTRE DATE D'ARRIVÉE


      // FILTRE NOMBRE DE VOYAGEURS
      if (isset($nbTravelers)) {
         $request = $request->where("max_personne_logement >= ?", $nbTravelers);
      }

      $departureOn = $dateRange["departureOn"]??NULL;
      $arrivesOn = $dateRange["arrivesOn"]??NULL;
      if(isset($departureOn) && isset($arrivesOn)) {
         $request = $request
            ->innerJoin("_reservation", "logement.id_logement = _reservation.id_logement")
            ->except("
               SELECT logement.id_logement AS id_logement, titre_logement, photo_logement, code_postal_adresse, ville_adresse, prix_ttc_logement 
               FROM logement
               INNER JOIN _reservation ON _reservation.id_logement = logement.id_logement
               WHERE ((date_arrivee > ?) AND (date_depart < ?))
            ", $arrivesOn, $departureOn);
      }

      $result = $request->execute()->fetchMany();

      // totalCount
      $totalCount = count($result);
      // items
      $items = array_slice(
         array_map(function ($r) {
            $r["photo_logement"] = FileLogement::get($r["photo_logement"]);
            return $r;
         }, $result)
      , $offset, $limit);
      
      $cities = transform_using($result, "ville_adresse");
      $departments = transform_using(
         RequestBuilder::select("logement")
            ->projection("nom_departement")
            ->innerJoin("_departement", "SUBSTR(code_postal_adresse, 1, 2) = _departement.num_departement")
            ->execute()
            ->fetchMany(), 
         "nom_departement"
      );

      $minPrice = RequestBuilder::select("logement")
         ->projection("MIN(prix_ttc_logement) AS min_price")
         ->execute()
         ->fetchOne()["min_price"] ?? 0;

      $maxPrice = RequestBuilder::select("logement")
         ->projection("MAX(prix_ttc_logement) AS max_price")
         ->execute()
         ->fetchOne()["max_price"] ?? 0;

      send_json_response([
         "items" => $items,
         "totalCount" => $totalCount,
         "cities" => $cities,
         "departments" => $departments,
         "priceRange" => [
            "min" => $minPrice,
            "max" => $maxPrice
         ]
      ]);
   }

   function transform_using($arr, $key) {
      $r = array_unique(
         array_map(function($r) use ($key) {
            return $r[$key];
         }, $arr)
      );
      rsort($r);
      return $r;
   }