<?php

/**
 * Format price with ","
 * @param $price a provided price
 * @return string a price formatted using ","
 */
function price_format($price) {
    return number_format($price, 2, ",", " ");
}


/**
 * Format a date
 * @param $date a provided date
 * @return string a date formatted in french format
 */
function to_french_date($date) {
    if (is_string($date)) {
        $date = new DateTime($date);
    }
    
    $months = [
        '01' => 'Janvier',
        '02' => 'Février',
        '03' => 'Mars',
        '04' => 'Avril',
        '05' => 'Mai',
        '06' => 'Juin',
        '07' => 'Juillet',
        '08' => 'Août',
        '09' => 'Septembre',
        '10' => 'Octobre',
        '11' => 'Novembre',
        '12' => 'Décembre',
    ];
    
    $day = $date->format('d');
    $month = $months[$date->format('m')];
    $year = $date->format('Y');
    
    return "$day $month $year";
}