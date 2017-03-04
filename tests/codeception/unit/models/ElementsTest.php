<?php

namespace tests\codeception\unit\models;

use yii\codeception\TestCase;
use Codeception\Specify;
use tests\codeception\unit\fixtures\ElementsFixture;
use app\models\Elements;

/**
 * Login form test
 */
class ElementsTest extends TestCase
{
    use Specify;

    public function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testElementsList()
    {
        $models = Elements::find()->all();

        $this->specify('check elements list', function () use ($models) {
            expect('models count > 0', (count($models) > 0))->true();
        });
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'elements' => [ 'class' => ElementsFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/elements.php'
            ],
        ];
    }
}
