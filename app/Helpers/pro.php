<?php
if(!function_exists('pro_params')){
    function pro_params($captive_use, $export_tariff, $domestic_tariff, $commercial_tariff, $cost_of_small_system, $system_size_kwp, $geopoints)
    {
        //echo("Using helper!");
        echo("using default values: $captive_use, $export_tariff, $domestic_tariff, $commercial_tariff, $cost_of_small_system, $system_size_kwp, \n ");
        //globals
        $distance_per_pixel = 0.3677;
        $area_per_pix =  $distance_per_pixel**2;
        //area, in pixels, of a standard 1.6x0.99m size
        $panel_area_in_pix = (1.6*0.99)/$area_per_pix;
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
        $embedded_co2_per_kwp = 2142; //kg. renewableenergyhub.co.uk/main/solar-panels/solar-panels-carbon-analysis/ suggests 1500kg for manufacture nrel.gov/docs/fy13osti/56487.pdf suggests 60-70% of CO2 due to manufacture, so lets assume 1500/0.7=2142
        $panel_lifetime = 25;
        //calculate some lifetime factors using our assumption about degradation
        //and price increases
        $panel_lifetime_output_factor = 0;
        $panel_domestic_lifetime_value_factor = 0;
        $panel_commercial_lifetime_value_factor = 0;
        //echo nl2br("calculating panel-related variables\r\n");
        for($i = 1; $i <= $panel_lifetime; $i++){
            $panel_lifetime_output_factor = $panel_lifetime_output_factor + $panel_degradation**($i - 1);
            $panel_domestic_lifetime_value_factor = $panel_domestic_lifetime_value_factor + $annual_domestic_electric_price_increase ** ($i-1);
            $panel_commercial_lifetime_value_factor = $panel_commercial_lifetime_value_factor + $annual_commercial_electric_price_increase ** ($i - 1);
            //echo nl2br("_____________________in iteration $i\r\n __________________panel_lifetime_output_factor: $panel_lifetime_output_factor\r\n __________________panel_domestic_lifetime_value_factor: $panel_domestic_lifetime_value_factor\r\n __________________panel_commercial_lifetime_value_factor: $panel_commercial_lifetime_value_factor\r\n");
        }
        //echo nl2br("\r\n_____________________________________final values:\r\n __________________panel_lifetime_output_factor: $panel_lifetime_output_factor\r\n __________________panel_domestic_lifetime_value_factor: $panel_domestic_lifetime_value_factor\r\n __________________panel_commercial_lifetime_value_factor: $panel_commercial_lifetime_value_factor\r\n");
        //THE REST OF THESE CALCULATION have to be applied to each site in the project,
        //so LOOP through these sites
        //we will be using these stored values for each stie:
            //$numpanels. $system_capacity_kWp, $roofclass (from the database);
        foreach($geopoints as $geopoint){
            //echo("$geopoint->roofclass");
            $roofclass = $geopoint->roofclass;
            if($roofclass == 's'){
                $gen_per_year_per_kwp = 937; //figure for south facing in gloucester according to NREL
                $yr_breakdown_per_kwp = [24,40,74,104,128,132,133,115,83,54,29,21]; //from NREL, gloucester
                $spacing_factor = 1;
                $foreshorten = 1/cos((30*M_PI)/180); //--> 1.1547; assume 30 degree average slope - area will be foreshortened by cos(30)
            }
            else if($roofclass == 'f'){
                $gen_per_year_per_kwp = 937; //figure for south facing in gloucester according to NREL - flat should enable optimal angle (e.g. ~15 deg) but need to space racks (or lay flat, but that increases system cost)
                $yr_breakdown_per_kwp = [24,40,74,104,128,132,133,115,83,54,29,21];
                $spacing_factor = 1.5; //0.5m spacing between rows so area usual increases by at least factor of 1.5
                $foreshorten = 1; //no foreshortening of the roof, and 15 deg racks basically no foreshortening
            }
            else if($roofclass == 'i'){
                $gen_per_year_per_kwp = 806; //figure for south facing in gloucester according to NREL - flat should enable optimal angle (e.g. ~15 deg) but need to space racks (or lay flat, but that increases system cost)
                //these two arrays below are used to get the average of each month in the matlab file
                $arr1 = [16,30,61,91,119,124,125,102,69,41,20,13];
                $arr2 = [16,30,60,90,114,121,122,102,69,42,22,13];
                $yr_breakdown_per_kwp = array_map(function(...$arrays){
                    return array_sum($arrays) /2;
                }, $arr1, $arr2); // --> gives [16, 30, 60.5, 90.5, 116.5, 122.5, 123.5, 102, 69, 41.5, 21, 13]
                $spacing_factor = 1;
                $foreshorten = 1/cos(30*M_PI/180); //--> 1.1547
            }
            //SYSTEM COST ESTIMATES, scaled by the value entered by the user
            $sys_cost_5kw = $cost_of_small_system / $system_size_kwp; //default 1200
            $sys_cap = $geopoint->system_capacity_kWp;
            if($sys_cap < 3){
                $c = 1500 * ($sys_cost_5kw/1200);
            }
            else if($sys_cap < 10){
                $c = $sys_cost_5kw;
            }
            else if($sys_cap < 50){
                $c = 1000 * ($sys_cost_5kw / 1200);
            }
            else if($sys_cap < 250){
                $c= 850 * ($sys_cost_5kw / 1200);
            }
            else if($sys_cap < 1000){
                $c= 600 * ($sys_cost_5kw / 1200);
            }
            else {
                $c= 500 * ($sys_cost_5kw / 1200);
            }
            $sys_cost = $c * $sys_cap;
            if($sys_cap < 10){ //changed from <50 to <10
                $electric_price = $domestic_tariff; //default value is set in controller method
            } else {
                $electric_price = $commercial_tariff;  //default value is set in controller method
            }
            //as per Phil, these should not need recalculating, just use stored values (unless panel rating is added to pro interface)
            //means no need to update geopoint data in the following variables: (marked by corresponding database column name)
                //annual_gen_kwh:
            //  $annual_gen_kwh = $sys_cap * $gen_per_year_per_kwp;
            //     //annual_saved_co2_kg:
            // $annual_co2_saved = $annual_gen_kwh * $co2_saved_per_kwh;
            //     //monthly_gen_captive_kwh?
            // $monthly_gen =[]; //an array containing data from Jan ~ Dec
            // foreach($yr_breakdown_per_kwp as $month_breakdown){
            //     $monthly_gen[] = $month_breakdown * $sys_cap;
            // }
            //     //yearly_cos2_saved_kg:
            // $cum_co2_overtime = array_fill(0, $panel_lifetime + 1, 0);
            //     //yearly_gen_captive_kWh:
            // $yearly_gen_overtime = array_fill(0, $panel_lifetime + 1, 0) ;
            //     //lifetime_co2_saved_kg:
            // $lifetime_co2_saved = $annual_co2_saved * $panel_lifetime_output_factor;
            // $embedded_co2 = $embedded_co2_per_kwp * $sys_cap;
            // //note: the cumulative co2 array starts with embedded co2 as its first element
            // $cum_co2_overtime[0] = $embedded_co2;
            // $yearly_gen_overtime[0] = $annual_gen_kwh;
            // for($j = 1; $j <= $panel_lifetime; $j++){
            //     $cum_co2_overtime[$j+1] = $cum_co2_overtime[$j] + $annual_co2_saved * $panel_degradation**($j-1);
            //     $yearly_gen_overtime[$j+1] = $annual_gen_kwh * $panel_degradation**$j;
            // }
            //=============================================
            //stuff that will change with PRO parameter changes;
            //each variable is marked by their corresponding column name in the database on top
            //1. annual_gen_GBP:
            $annual_gen_kwh = $geopoint->annual_gen_kWh;
            $annual_gen_val = ($annual_gen_kwh * $electric_price * $captive_use) + ($annual_gen_kwh * $export_tariff *(1 - $captive_use));
            //$annual_gen_val = $annual_gen_kwh * 0.146 * 0.8 + $annual_gen_kwh * 0.055 * 0.2;
            //2. lifetime_gen_GBP:
            if($sys_cap > $residential_threshold){
                $lifetime_value = $annual_gen_val * $panel_commercial_lifetime_value_factor;
            } else{
                $lifetime_value = $annual_gen_val * $panel_domestic_lifetime_value_factor;
            }
            //3. lifetime_return_on_investment_percent:
            $return_on_investment = $lifetime_value / $sys_cost;
            //4. annualized_return_on_investment_percent:
            $annual_roi = (1 + $return_on_investment) ** (1 / $panel_lifetime) - 1;
            //5. breakeven_years calculation
            $breakeven = -1;
            $v = 0;
            $ag = $annual_gen_kwh;
            $ep = $electric_price; //came from either domestic tariff or commercial tariff
            $ex = $export_tariff;
            for($k = 1; $k <= $panel_lifetime; $k++){
                $tmpv = $ag * $ep * $captive_use + $ag * $ex * (1 - $captive_use); //value of elctricity use + export
                $dpt = 0;
                if($sys_cap > $residential_threshold && $k <= (1/$annual_depreciation)){
                    $dpt = $sys_cost * $annual_depreciation * $corporate_tax_rate; //depreciation tax benefits
                }
                $tmpv += $dpt;
                $v += $tmpv;
                if($v > $sys_cost){
                    $breakeven = $k;
                    break;
                }
                $ag *= $panel_degradation;
                if($sys_cap > $residential_threshold){
                    $ep *= $annual_commercial_electric_price_increase;
                } else{
                    $ep *= $annual_domestic_electric_price_increase;
                }
            }
            if($breakeven == -1){
                $breakeven = $panel_lifetime;
            }
            //update all the changed params on a geopoint
            $geopoint -> system_cost_GBP = $sys_cost;
            $geopoint -> annual_gen_GBP = $annual_gen_val;
            $geopoint -> lifetime_gen_GBP = $lifetime_value;
            $geopoint -> lifetime_return_on_investment_percent = $return_on_investment * 100;
            $geopoint -> annualized_return_on_investment_percent = $annual_roi * 100;
            $geopoint -> breakeven_years = $breakeven;
        }
        $pro_geopoints = $geopoints;
        //dd($pro_geopoints);
        return $pro_geopoints;

    }
}
