<?php

function obsfuceRIB($rib) {
    $obsfuceLength = 4;
    $length = strlen($rib);
    $rib2keep = substr($rib, 0, $obsfuceLength);
    return wordwrap(
        $rib2keep . join("", array_fill(0, $length-$obsfuceLength-1,"*")), 
        4, 
        " ", 
        true
    );
}

if(isset($_POST) && isset($_POST["editProfile"])) {
    extract($_POST);
    
}