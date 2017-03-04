<?php

namespace tests\codeception\unit\models;

use yii\codeception\TestCase;
use app\models\User;
use tests\codeception\unit\fixtures\UsersFixture;
use tests\codeception\unit\fixtures\SysmsgsFixture;
use tests\codeception\unit\fixtures\FilesFixture;
use Codeception\Specify;

class UserTest extends TestCase
{
    use Specify;

    protected function setUp()
    {
        parent::setUp();
        // uncomment the following to load fixtures for user table
        //$this->loadFixtures(['items']);
    }

    // TODO add test methods here
    //
    public function testUserValidate()
    {
        $model = User::findOne(1);

        $this->specify('check user passvalidate', function () use ($model) {
            expect('model should not login user', $model->validatePassword('test'))->false();
        });
    }

    public function fixtures()
    {
        return [
            'items' => [
                'class' => UsersFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/user.php'
            ],
            'files' => [
                'class' => FilesFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/files.php'
            ],
            'sysmsgs' => [
                'class' => SysmsgsFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/sysmsgs.php'
            ],
        ];
    }
}
