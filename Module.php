<?php

/*TODO цілі
 *  1. безкінечна вложеність элементів
 *  2. вивід статичних сторінок та динамічних списків сторінок
 *  3. seo елементи
 *
 * */

namespace greeschenko\prozorrosale;

use Yii;

class Module extends \yii\base\Module
{
    const VER = '0.1-dev';

    public $name;
    public $istest = false;
    public $proddomen = [];
    public $prourl;
    public $proname;
    public $prokey;
    public $test_prourl;
    public $test_proname;
    public $test_prokey;
    public $itemclass = '';
    public $tb_name;
    public $tb_edrpou;
    public $tb_bank_name;
    public $tb_bank_account;
    public $tb_bank_mfo;

    public function init()
    {
        parent::init();

        if (!in_array($_SERVER['SERVER_NAME'],$this->proddomen)) {
            $this->prourl = $this->test_prourl;
            $this->proname = $this->test_proname;
            $this->prokey = $this->test_prokey;
            $this->istest = true;
        }

        $this->components = [
            /*'image' => [
                'class' => 'yii\image\ImageDriver',
                'driver' => 'GD',  //GD or Imagick
            ],*/
        ];
    }
}
