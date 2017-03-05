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
        ['class' => 'btn btn-success','id' => 'create_element_submit']) ?>
    <?= Html::a(
        Yii::t('cont_elem', 'Create New'),
        ['create'],
        ['class' => 'btn btn-default','target' => '_blunk']) ?>
    <?php if (!$model->isNewRecord): ?>
        <?= Html::a(
            Yii::t('cont_elem', 'Copy'),
            ['copy','id' => $model->id],
            ['class' => 'btn btn-info']) ?>
        <?= Html::a(
            Yii::t('cont_elem', 'Delete'),
            ['delete','id' => $model->id],
            ['class' => 'btn btn-danger']) ?>
    <?php endif; ?>
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
            <?= $form->field($model, 'parent')
                ->dropDownList($model->getParentList(),
                    ['options' => [$model->id => ['disabled' => true]]]) ?>
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

    <div class="hidden">
            <?= Upload::widget([
                'id' => 'elements-contentupload',
                'groupcode' => $model->atachments.'_tmp',
                'clientEvents' => [
                    'fileuploaddone' => 'function(e, data) {
                        var item = data.result.files;
                        if ( item.type == 1 ) {
                            var html = \'<img src="\'+item.big+\'" alt=""/>\'
                            $("#elements-content").redactor("insert.html", html);
                        }else{
                            var html = \'<a href="\'+item.url+\'">\'+item.name+\'</a>\'
                            $("#elements-content").redactor("insert.html", html);
                        }
                    }',
                    'fileuploadfail' => 'function(e, data) {
                                            alert("error");
                                            console.log(e);
                                            console.log(data);
                                        }',
                ],
            ]);?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
