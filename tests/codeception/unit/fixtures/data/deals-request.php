<?php

$faker = Faker\Factory::create();

$res = [];

foreach (\app\models\Lots::find()->all() as $one ) {
    if ( $one->number==1 ) {
        foreach (\app\models\User::find()->select('id')->where(['role'=>'memb'])->all() as $user) {
            $res[] = [
                'user_id'=>$user->id,
                'is_rules_accept'=>1,
                'docs'=>'2222',
                'created_at'=>time(),
                'updated_at'=>time(),
                'status'=>1,
                'type'=>1,
                'deal_id'=>$one->deal_id,
                'lot_num'=>1,
            ];
        }
    }
}

return $res;
