<?php

$faker = Faker\Factory::create();

$res = [];

foreach (\app\models\Lots::find()->where(['number'=>1])->all() as $one ) {
    $timecode=$one->deal->start_prop_time;
    for ( $i = 0; $i < 5; $i++ ) {
        foreach (\app\models\User::find()->select('id')->where(['role'=>'memb'])->all() as $user) {
            $timecode=$timecode+10;

            if ( $one->deal_id != 2 ) {
                $res[] = [
                    'user_id'=>$user->id,
                    'deal_id'=>$one->deal_id,
                    'lot_num'=>1,
                    'steps'=>rand(1,10),
                    'created_at'=>$timecode,
                    'updated_at'=>$timecode,
                    'microtime'=>rand(1111,9999),
                ];
            }
        }
    }
}

return $res;
