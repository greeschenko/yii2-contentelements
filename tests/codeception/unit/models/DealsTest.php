<?php

namespace tests\codeception\unit\models;

use yii\codeception\TestCase;
use app\models\Deals;
use app\models\DealsSearch;
use tests\codeception\unit\fixtures\DealsFixture;
use tests\codeception\unit\fixtures\DealsRequestFixture;
use tests\codeception\unit\fixtures\DealsOffersFixture;
use Codeception\Specify;

class DealsTest extends TestCase
{
    use Specify;

    protected function setUp()
    {
        parent::setUp();
        // uncomment the following to load fixtures for user table
        //$this->loadFixtures(['items']);
    }

    public function testDealsList()
    {
        $deals = Deals::find()->all();

        $this->specify('check deals count', function () use ($deals) {
            expect('count deals == 10', (count($deals) == 10))->true();
        });
    }

    public function testDealsSearchByName()
    {
        $model = new DealsSearch([
            'name'=>'ttt',
        ]);

        $this->specify('check search by name', function () use ($model) {
            expect('have one result', (count($model->searchPublic(false)->getModels()) == 1))->true();
        });
    }

    public function testDealsSearchByOrg()
    {
        $model = new DealsSearch([
            'organizer_str'=>3,
        ]);

        $this->specify('check search by organizer', function () use ($model) {
            expect('have eny result', (count($model->searchPublic(false)->getModels()) > 0))->true();
        });
    }

    public function testDealsSearchByNumber()
    {
        $model = new DealsSearch([
            'number'=>'10-АП',
        ]);

        $this->specify('check search by number', function () use ($model) {
            expect('have one result by number', (count($model->searchPublic(false)->getModels()) == 1))->true();
        });
    }

    public function testDealsSearchByStatDate()
    {
        $model = new DealsSearch([
            'stat_end_time_from'=>date('d-m-Y',time()-(60*60*24*30)),
            'stat_end_time_to'=>date('d-m-Y',time()+(60*60*24*15)),
        ]);

        $this->specify('check search by date', function () use ($model) {
            expect('find more one record', (count($model->searchPublic(false)->getModels()) > 0))->true();
        });
    }

    public function testDealsSearchByPrice()
    {
        $model = new DealsSearch([
            'price_from'=>4000,
            'price_to'=>5000,
        ]);

        $this->specify('check search by price', function () use ($model) {
            expect('find more one record', (count($model->searchPublic(false)->getModels()) > 0))->true();
        });
    }

    public function testDealsSearchByCondition()
    {
        $model = new DealsSearch([
            'conditions'=>[1],
        ]);

        $this->specify('check search by condition', function () use ($model) {
            expect('find more one record', (count($model->searchPublic(false)->getModels()) > 0))->true();
        });
    }

    public function fixtures()
    {
        return [
            'items' => [
                'class' => DealsFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/deals.php'
            ],
            'request' => [
                'class' => DealsRequestFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/deals-request.php'
            ],
            'offers' => [
                'class' => DealsOffersFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/deals-offers.php'
            ],
        ];
    }
}
