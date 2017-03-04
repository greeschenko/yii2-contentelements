<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

/**
 * Represents login page
 * @property \AcceptanceTester|\FunctionalTester $actor
 */
class DealsCreatePage extends BasePage
{
    public $route = 'deals/create';

    /**
     * fillAndCreate
     *
     * @param array $datadeal
     * @param array $datalot
     * @access public
     * @return void
     */
    public function fillAndCreate(array $datadeal,array $datalot)
    {
        /*'name'=>'',
        'number'=>'',
        'organizer'=>'',
        'user_name'=>'',
        'user_position'=>'',
        'user_email'=>'',
        'user_phone'=>'',
        'user_fax'=>'',
        'is_closed'=>'',
        'stat_end_time'=>'',
        'start_prop_time'=>'',
        'stop_prop_time'=>'',
        'ext_time'=>'',*/
        foreach ($datadeal as $key=>$value) {
            $this->actor->fillField('#deals-'.$key, $value);
        }

        /*'number'=>'',
        'description'=>'',
        'start_price'=>'',
        'price_step'=>'',
        'deposit'=>'',
        'organizer_rent'=>'',
        'other_files'=>'',*/
        foreach ($datalot as $key=>$value) {
            $this->actor->fillField('#lots-0-'.$key, $value);
        }
        /*$this->actor->click('#saveandaccept');*/
    }
}
