<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model greeschenko\contentelements\models\Elements */

$this->title = Yii::t('cont_elem', 'Create Element');
$this->params['breadcrumbs'][] = ['label' => Yii::t('cont_elem', 'Element'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elements-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
