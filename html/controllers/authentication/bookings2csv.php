<?php

if(isset($_POST)) {
    extract($_POST);
    if(!isset($bookings) || count($bookings) < 1) {
        header("Content-Type: application/json");
        echo json_encode(array("error" => "Aucune réservation trouvé."));
        exit;
    }

    $f = fopen('php://memory', 'w'); 
    foreach ($bookings as $line) { 
        fputcsv($f, $line, ";"); 
    }
    fseek($f, 0);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="export.csv";');
    fpassthru($f);
}