<?php

function usd($price)
{
    $price = str_replace(',', '', $price);

    return money_format('%.2n', $price);
}

function color($value)
{
    return $value > 0 ? 'success' : 'danger';
}