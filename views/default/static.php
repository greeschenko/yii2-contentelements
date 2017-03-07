<?php

use yii\helpers\Html;
use greeschenko\file\widgets\FilesGallery;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model greeschenko\contentelements\models\Elements */
$this->title = ($model->meta_title != '') ? $model->meta_title : $model->title;
$this->registerMetaTag([
    'name' => 'description',
    'content' => $model->meta_descr
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $model->meta_keys
]);
$this->params['breadcrumbs'] = $model->genBreacrumbs();
?>
<div class="element">
    <header>
        <h1><?= Html::encode($model->title) ?></h1>
    </header>
    <article>
        <?= HtmlPurifier::process($model->content) ?>
    </article>
    <hr>
    <div class="element_atachments">
        <p class="lead"><?=Yii::t('cont_elem', 'Atachments')?></p>
        <?= FilesGallery::widget([
            'groupcode' => $model->atachments,
        ]);?>
    </div>
    <hr>
</div>
