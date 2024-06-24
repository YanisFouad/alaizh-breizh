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
 * Format phone number
 * @param $phone_number a provided phone number
 * @return string a formatted phone number
 */
function phone_number_format($phone_number) {
    $phone = str_replace(' ', '', $phone_number);
    if ($phone[0] != '0') {
        $phone = '0'.$phone;
    }
    return wordwrap($phone, 2, " ", true);
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

/**
 * Send json response with associated headers
 * @param $entries a list of entries 
 */
function send_json_response(...$entries) {
    header("Content-type: application/json");
    echo json_encode(...$entries);
    exit;
}

/**
 * Verify if email is valid or not (based on a regex)
 * @return boolean if email is valid or not
 */
function is_valid_email($email) {
    $EMAIL_REGEX_PATTERN = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
    return preg_match($EMAIL_REGEX_PATTERN, $email);
}