<?php

if(!function_exists('pro_params')){
    function pro_params($captive_use, $export_tariff, $domestic_tariff, $commercial_tariff, $kW_price)
    {
        echo("Using helper!");
        //echo("$captive_use, $export_tariff, $domestic_tariff, $commercial_tariff, $kW_price");

        $pro_data = [
            $captive_use, $export_tariff, $domestic_tariff, $commercial_tariff, $kW_price

        ];

        return $pro_data;

    }
}
