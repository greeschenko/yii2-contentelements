<?php

$faker = Faker\Factory::create();

$res = [];

for ( $i = 1; $i < 11; $i++ ) {
    $lotscount = rand(2,7);
    $randprise = 1000;

    if ( $i % 4 == 0 ) {
        $randprise = 4000;
    } elseif ( $i % 5 == 0 ) {
        $randprise = 5000;
    } elseif ( $i % 9 == 0 ) {
        $randprise = 9000;
    } else {
        $randprise = rand(1000,3000);
    }

    for ( $j = 1; $j < $lotscount; $j++ ) {
        $res[] = [
           'number'=>$j,
           'description'=>$faker->text(),
           'info'=>$faker->text(400),
           'review_order'=>$faker->text(400),
           'start_price'=>$randprise,
           'with_pdw'=>rand(0,1),
           'price_step'=>rand(10,100),
           'step_type'=>rand(1,2),
           'deposit'=>'',
           'other_files'=>$faker->randomNumber(8),
           'created_at'=>time(),
           'updated_at'=>time(),
           'status'=>0,
           'type'=>0,
           'deal_id'=>$i,
        ];
    }
}

return $res;
