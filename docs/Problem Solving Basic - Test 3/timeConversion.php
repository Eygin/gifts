<?php

function timeConversion(string $time) {
    $resultConversion = date("H:i:s", strtotime($time));
    
    echo $resultConversion;
}

timeConversion('12:01:01AM');