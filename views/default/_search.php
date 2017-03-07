<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model greeschenko\contentelements\models\ElementsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="elements-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-10">
            <?= $form->field($model, 'all')->label(false) ?>
        </div>
        <div class="col-md-2">
            <?= Html::submitButton(Yii::t('cont_elem', 'Search'), ['class' => 'btn btn-block btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div class="clearfix"></div>
