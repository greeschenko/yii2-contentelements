<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model greeschenko\contentelements\models\Elements */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="elements-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group text-right">
        <?= Html::submitButton(Yii::t('cont_elem', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <div class="row">
        <div class="col-md-9">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'preview')->textarea(['rows' => 3]) ?>
            <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'urld')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'parent')->textInput() ?>
            <?= $form->field($model, 'type')->dropDownList($model->typelist) ?>
            <?= $form->field($model, 'status')->dropDownList($model->statuslist) ?>

            <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'meta_keys')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'meta_descr')->textarea(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="clearfix"></div>

    <?= $form->field($model, 'atachments')->textInput(['maxlength' => true]) ?>

    <?php ActiveForm::end(); ?>

</div>
