<?php


function format_amount($amount)
{
    if (strpos($amount, '.')) {
        return substr($amount, 0, strpos($amount, '.') + 3);
    } else {
        return $amount;
    }
}
