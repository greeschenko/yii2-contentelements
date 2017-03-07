<?php

namespace greeschenko\contentelements;

use Yii;
use greeschenko\contentelements\assets\ModuleAsset;

class Module extends \yii\base\Module
{
    const VER = '0.0.1-dev';

    public $userclass;
    public $listitemclass = 'col-md-6 col-lg-6';

    public function init()
    {
        parent::init();

        ModuleAsset::register(Yii::$app->view);

        $this->components = [
            /*'image' => [
                'class' => 'yii\image\ImageDriver',
                'driver' => 'GD',  //GD or Imagick
            ],*/
        ];

        $this->registerTranslations();

        if (!$this->userclass) {
            throw new \yii\web\HttpException(501 ,Yii::t('cont_elem', 'user class not specified in the config file'));
        }
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['cont_elem*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@greeschenko/contentelements/messages',
            'fileMap' => [
                'cont_element' => 'cont_elem.php',
            ],
        ];
    }
}
