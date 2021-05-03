<?php

if(!function_exists('pro_params')){
    function pro_params($captive_use, $export_tariff, $domestic_tariff, $commercial_tariff, $cost_of_small_system, $system_size_kwp, $geopoints)
    {
        echo("Using helper!");
        echo("$captive_use, $export_tariff, $domestic_tariff, $commercial_tariff, $cost_of_small_system, $system_size_kwp");

        //globals
        $distance_per_pixel = 0.3677;
        $area_per_pix =  $distance_per_pixel**2;
        //area, in pixels, of a standard 1.6x0.99m size
        $panel_area_in_pix = 1.6*0.99/$area_per_pix;

        $min_worth_while_system_in_panels = 4;
        $min_worth_while_system_in_pix = round($panel_area_in_pix * $min_worth_while_system_in_panels); //used in area processing
        $min_worth_while_system_cap = 2; //applied as threshold on final system
        $av_panel_rating = 0.32; //320W;
        $annual_domestic_electric_price_increase = 1.03; //3% increase;
        $annual_commercial_electric_price_increase = 1.05; //5% increase - commercial have tended to rise faster for last 10-20 years
        $panel_degradation = 0.99; //%1 degradation
        $annual_depreciation = 0.1;
        $corporate_tax_rate = 0.21;
        $residential_threshold = 10; //treat systems below this size as residential.  above, assume tax benefits claimed etc
        $co2_saved_per_kwh = 0.5; //rensmart.com/Calculators/KWH-to-CO2 also breaks down different electricity sources.  Since UK hardly uses coal and oil any more, we should view the gas-based generation as what our solar generation replaces, so 0.5kg/kWh
        $embedded_co2_per_kep = 2142; //kg. renewableenergyhub.co.uk/main/solar-panels/solar-panels-carbon-analysis/ suggests 1500kg for manufacture nrel.gov/docs/fy13osti/56487.pdf suggests 60-70% of CO2 due to manufacture, so lets assume 1500/0.7=2142
        $panel_lifetime = 25;

        //calculate some lifetime factors using our assumption about degradation
        //and price increases
        $panel_lifetime_output_factor = 0;
        $panel_domestic_lifetime_value_factor = 0;
        $panel_commercial_lifetime_value_factor = 0;

        for($i = 1; $i <= $panel_lifetime; $i++){
            $panel_lifetime_output_factor += $panel_degradation ** ($i - 1);
            $panel_domestic_lifetime_value_factor += $annual_domestic_electric_price_increase ** ($i-1);
            $panel_commercial_lifetime_value_factor += $annual_commercial_electric_price_increase ** ($i - 1);
        }

        //THE REST OF THESE CALCULATION have to be applied to each site in the project,
        //so LOOP through these sites
        //we will be using these stored values for each stie:
            //$numpanels. $system_capacity_kWp, $roofclass (from the database);

        foreach($geopoints as $geopoint){
            //echo("$geopoint->roofclass");
            if($geopoint->roofclass == 's'){
                $gen_per_year_per_kwp = 937; //figure for south facing in gloucester according to NREL
                $yr_breakdown_per_kwp = [24,40,74,104,128,132,133,115,83,54,29,21]; //from NREL, gloucester
                $spacingfactor = 1;
                $foreshorten = 1/cos(30*M_PI/180); //assume 30 degree average slope - area will be foreshortened by cos(30)
            }
            else if($geopoint->roofclass == 'f'){
                $gen_per_year_per_kwp = 937; //figure for south facing in gloucester according to NREL - flat should enable optimal angle (e.g. ~15 deg) but need to space racks (or lay flat, but that increases system cost)
                $yr_breakdown_per_kwp = [24,40,74,104,128,132,133,115,83,54,29,21];
                $spacingfactor = 1.5; //0.5m spacing between rows so area usual increases by at least factor of 1.5
                $foreshorten = 1; //no foreshortening of the roof, and 15 deg racks basically no foreshortening
            }
            else if($geopoint->roofclass == 'i'){
                $gen_per_year_per_kwp = 806; //figure for south facing in gloucester according to NREL - flat should enable optimal angle (e.g. ~15 deg) but need to space racks (or lay flat, but that increases system cost)
                //these two arrays below are used to get the average of each month in the matlab file
                $arr1 = [16,30,61,91,119,124,125,102,69,41,20,13];
                $arr2 = [16,30,60,90,114,121,122,102,69,42,22,13];
                $yr_breakdown_per_kwp = array_map(function(...$arrays){
                    return array_sum($arrays) /2;
                }, $arr1, $arr2); // --> gives [16, 30, 60.5, 90.5, 116.5, 122.5, 123.5, 102, 69, 41.5, 21, 13]
                $spacingfactor = 1;
                $foreshorten = 1/cos(30*M_PI/180);
            }

            //SYSTEM COST ESTIMATES, sclaed by the value entered by the user
            $sys_cost_5kw = $cost_of_small_system / $system_size_kwp; //default 1200
            if($geopoint->system_capacity_kWp < 3){
                $c = 1500 * ($sys_cost_5kw/1200);
            }

            //dd($gen_per_year_per_kwp);
        }















        $pro_data = [
            'captive_use' => $captive_use,
            'export_tariff'=>$export_tariff,
            'domestic_tariff'=>$domestic_tariff,
            'commercial_tariff'=>$commercial_tariff,
            'cost_of_small_system'=>$cost_of_small_system,
            'system_size_kwp' => $system_size_kwp

        ];

        return $pro_data;

    }
}
