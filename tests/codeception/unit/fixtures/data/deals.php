<?php
use \app\models\User;

$faker = Faker\Factory::create();

$res = [];

for ( $i = 0; $i < 10; $i++ ) {
    $statuslist = [0,10];
    /*$orgz = User::find()->where(['role'=>[User::ROLE_ADMIN,User::ROLE_ORG]])->all();*/
    $name = $faker->text(50);

    if ( $i <= 5 ) {
        $timeindex = $i - 5;
    } else {
        $timeindex = $i;
    }

    if ( $i == 1 || $i == 2) {
        $cond = 5;
    } elseif ($i == 3) {
        $cond = 6;
        $name = 'qqqqttt';
    } else {
        $cond = 0;
    }

    if ( $i == 5 ) {
        $stat_end_time = time()-3600;
        $start_prop_time = time()-600;
        $stop_prop_time = time()+150;
    } elseif ( $i == 6 ) {
        $stat_end_time = time()-3000;
        $start_prop_time = time()+3000;
        $stop_prop_time = time()+12000;
    } else {
        $stat_end_time = time()+($timeindex * 60 * 60 * 24);
        $start_prop_time = $stat_end_time + (60 * 60 * 24 * 1);
        $stop_prop_time = $start_prop_time + (60 * 60 * 24);
    }
    $res[] = [
        'name'=>$name,
        /*'organizer'=>$orgz[rand(0,count($orgz)-1)]->id,*/
        'organizer'=>3,
        'number'=>($i+10).'-АП',
        'user_name'=>$faker->firstName,
        'user_position'=>$faker->company,
        'user_email'=>$faker->email,
        'user_phone'=>$faker->phoneNumber,
        'user_fax'=>$faker->phoneNumber,
        'form'=>rand(1,3),
        'is_closed'=>rand(0,1),
        'stat_end_time'=>$stat_end_time,
        'start_prop_time'=>$start_prop_time,
        'stop_prop_time'=>$stop_prop_time,
        'ext_time'=>1,
        'min_users'=>1,
        'organizer_rent'=>rand(1000,10000),
        'order_bay_sell_info'=>$faker->text(400),
        'pay_time_info'=>$faker->text(400),
        'deposit_contract'=>$faker->randomNumber(9),
        'proj_bay_sell_contract'=>$faker->randomNumber(9),
        'other_files'=>$faker->randomNumber(9),
        'created_at'=>time(),
        'updated_at'=>time(),
        'cond'=>$cond,
        /*'status'=>$statuslist[rand(0,1)],*/
        'status'=>10,
        'type'=>0,
        'seller_firm_name' => $faker->company,
        'seller_firstname' => $faker->firstName,
        'seller_lastname' => $faker->lastName,
        'seller_middlename' => $faker->firstName,
        'seller_address' => $faker->address,
        'seller_position' => $faker->jobTitle,
    ];
}

return $res;
