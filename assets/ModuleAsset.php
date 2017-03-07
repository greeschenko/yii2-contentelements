<?php

namespace greeschenko\contentelements\assets;

use yii\web\AssetBundle;

class ModuleAsset extends AssetBundle
{
    public $sourcePath = '@greeschenko/contentelements/web';
    public $css = [
        'css/module.css',
    ];
    public $js = [
        'js/module.js'
    ];
}
