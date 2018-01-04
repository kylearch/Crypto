<?php

namespace App\Helpers;

class NumberHelper
{

    public static function format(float $price, $isUSD = FALSE)
    {
        if ($isUSD === TRUE) {
            $decimals = 2;
        } else {
            $decimals  = $price < 1 ? strspn($price, '0', strpos($price, '.') + 1) : 0;
            $decimals  += ($decimals > 0) ? 2 : 0;
            $decimals  = max(2, $decimals);
        }

        return number_format($price, $decimals);
    }

}