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

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'urld') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'parent') ?>

    <?php // echo $form->field($model, 'preview') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'meta_title') ?>

    <?php // echo $form->field($model, 'meta_descr') ?>

    <?php // echo $form->field($model, 'meta_keys') ?>

    <?php // echo $form->field($model, 'atachments') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cont_elem', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cont_elem', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
