<?php

if(isset($_POST) && isset($_POST["array"])) {
    $array = unserialize($_POST["array"]);
    if(!isset($array) || count($array) < 1) {
        header("Content-Type: application/json");
        echo json_encode(array("error" => "Liste vide."));
        exit;
    }

    $f = fopen('php://memory', 'w');
    // includes headers, we have to image that the first element of the list have all keys
    fputcsv($f, array_keys($array[0]), ";");
    foreach ($array as $line) { 
        fputcsv($f, $line, ";"); 
    }
    fseek($f, 0);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$_POST["name"]??"export.csv".'";');
    fpassthru($f);
}