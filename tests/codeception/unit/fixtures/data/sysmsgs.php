<?php

$faker = Faker\Factory::create();

$res = [];

for ( $i = 1; $i < 5; $i++ ) {
    for ( $j = 0; $j < 10; $j++ ) {
        $res[] = [
            'content'=>$faker->text(rand(100,400)),
            'user_id'=>$i,
            'created_at'=>time(),
            'updated_at'=>time(),
            'status'=>rand(1,3),
            'type'=>rand(1,3),
        ];
    }
}

return $res;
