<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use greeschenko\file\widgets\Upload;

$model->atachments = ($model->atachments != '')
    ? $model->atachments
    : md5('c_e_'.Yii::$app->user->identity->id.time());

/* @var $this yii\web\View */
/* @var $model greeschenko\contentelements\models\Elements */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="elements-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group text-right">
    <?= Html::submitButton(
        $model->isNewRecord
            ? Yii::t('cont_elem', 'Create')
            : Yii::t('cont_elem', 'Save'),
        ['class' => 'btn btn-success']) ?>
    </div>

    <div class="row">
        <div class="col-md-9">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'preview')->textarea(['rows' => 3]) ?>
            <?= $form->field($model, 'content')->widget(Widget::className(), [
                /*'options' => ['id'=>'content-field'],*/
                'settings' => [
                    /*'lang' => \Yii::$app->language,*/
                    'lang' => 'ru',
                    'minHeight' => 400,
                    'plugins' => [
                        'clips',
                        'counter',
                        'definedlinks',
                        'fontcolor',
                        'fontfamily',
                        'fontsize',
                        'fullscreen',
                        'limiter',
                        'table',
                        'textexpander',
                        'video'
                    ]
                ],
                'plugins' => [
                    'advanced' => 'greeschenko\contentelements\assets\EditorUploadAsset',
                ]
            ]); ?>

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

    <div class="">
            <?= $form->field($model,'atachments')->hiddenInput();?>
            <?= Upload::widget([
                'id' => 'element_upload_file',
                'groupcode' => $model->atachments,
            ]);?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
