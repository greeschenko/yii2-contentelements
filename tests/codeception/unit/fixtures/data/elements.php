<?php

$faker = Faker\Factory::create();

$res = [];

for ( $i = 1; $i < 11; $i++ ) {
    if ( in_array($i, [1,2,3]) ) {
        $type = 2;
    } else {
        $type = 1;
    }

    if ( $type == 1 ) {
        $width = 300;
        $height = 200;
    } else {
        $width = 900;
        $height = 250;
    }

    $imgsrc = $faker->imageUrl($width, $height, 'business');

    /*$imgsrc = '/static/tmp/'.$dir.'/'.$i.'.jpg';*/

    $res[] = [
        'title'=>$faker->text(100),
        'content'=>'<p>'.$faker->text(100).'</p>'."<br/><img src='$imgsrc' alt=''><br/>".'<p>'.$faker->text(400).'</p>',
        'user_id'=>2,
        'urld'=>$faker->word,
        'tags'=>'',
        'type_id'=>1,
        'cat_id'=>1,
        'meta_title'=>$faker->text(100),
        'meta_descr'=>$faker->text(200),
        'meta_keys'=>'',
        'created_at'=>time(),
        'updated_at'=>time(),
        'type'=>$type,
        'status'=>2,
    ];
}

for ( $j = 11; $j < 24; $j++ ) {
    $imgsrc = '/uploads/tmptest/logo_'.$faker->numberBetween(1,30).'.jpg';
    $res[] = [
        'title'=>$faker->text(30),
        'content'=>"<img src='$imgsrc' alt=''>",
        'user_id'=>2,
        'urld'=>$faker->url,
        'tags'=>'',
        'type_id'=>1,
        'cat_id'=>1,
        'meta_title'=>'',
        'meta_descr'=>'',
        'meta_keys'=>'',
        'created_at'=>time(),
        'updated_at'=>time(),
        'type'=>4,
        'status'=>2,
    ];
}

return $res;
