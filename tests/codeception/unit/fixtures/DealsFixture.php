<?php
namespace tests\codeception\unit\fixtures;

use yii\test\ActiveFixture;

class DealsFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Deals';
    public $depends = [
        'tests\codeception\unit\fixtures\UsersFixture',
        'tests\codeception\unit\fixtures\LotsFixture',
        'tests\codeception\unit\fixtures\DealsItemsFixture',
    ];
}
