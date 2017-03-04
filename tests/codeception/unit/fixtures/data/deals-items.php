<?php
use app\models\Items;

$faker = Faker\Factory::create();

$res = [];

for ( $i = 1; $i < 11; $i++ ) {
    $n = 0;
    foreach (Items::find()->all() as $one) {
        if ( $n % 5 == 0 or $n % 9 == 0 ) {
            $res[] = [
                'item_id'=>$one->id,
                'deal_id'=>$i,
                'created_at'=>time(),
                'updated_at'=>time(),
                'status'=>0,
                'type'=>0,
            ];
        }
        $n++;
    }
}

return $res;
