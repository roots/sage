<?php

namespace App\Utils;

/**
 * Format a phone number according to finnish system. Not perfect.
 */
function format_phone($number)
{
    // Remove all separators (except for parenthesis)
    $number = str_replace(['-', ' '], '', $number);
    // Format the ending numbers with spacing:
    // - 0407072916 -> 040 7072916
    // - 04007072916 -> 0400 7072916
    $number = preg_replace('|(\d{2,4})(\d{3})*(\d{4})$|', '$1 $2 $3', $number);
    // Format the spacing and the optional parenthesis:
    // +35840 -> +358 40
    // +358(0)40 -> +358 40
    $number = preg_replace('|^\+?358([\d])?(\(0\))?|', '+358 $1', $number);
    return $number;
}
