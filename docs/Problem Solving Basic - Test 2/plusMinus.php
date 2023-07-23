<?php

function plusMinus(array $arr) {
    $positive = 0;
    $negative = 0;
    $zero = 0;
    $totalArr = count($arr);
    
    foreach ($arr as $i) {
        if ($i > 0) {
            $positive++;
        } elseif ($i < 0) {
            $negative++;
        } else {
            $zero++;
        }
    }
    
    $positiveResult = $positive / $totalArr;
    $negativeResult = $negative / $totalArr;
    $zeroResult = $zero / $totalArr;
    
    echo number_format($positiveResult, 6) . "\n";
    echo number_format($negativeResult, 6) . "\n";
    echo number_format($zeroResult, 6) . "\n";
}

plusMinus([1,1,0,-1,-1]);