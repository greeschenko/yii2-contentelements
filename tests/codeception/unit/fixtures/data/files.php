<?php

$faker = Faker\Factory::create();

$res = [];

for ( $i = 1; $i < 11; $i++ ) {
    $res[] = [
        'name'=>$faker->word,
        'path'=>time().'/',
        'ext'=>'jpg',
        'user_id'=>2,
        'created_at'=>time(),
        'updated_at'=>time(),
        'status'=>0,
        'type'=>1,
        'group'=>(1111*$i),
    ];
}

return $res;
