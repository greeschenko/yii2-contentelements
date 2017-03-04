<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model greeschenko\contentelements\models\Elements */

$this->title = Yii::t('cont_elem', 'Update {modelClass}: ', [
    'modelClass' => 'Elements',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('cont_elem', 'Elements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cont_elem', 'Update');
?>
<div class="elements-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
