<?php
namespace tests\codeception\unit\fixtures;

use yii\test\ActiveFixture;

class DealsItemsFixture extends ActiveFixture
{
    public $modelClass = 'app\models\DealsItems';
    public $dataFile = '@tests/codeception/unit/fixtures/data/deals-items.php';
}
