<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<div class="<?=\Yii::$app->controller->module->listitemclass;?>">
    <div class="cont_elem_item">
        <div class="cont_elem_img"><img src="<?=$model->getTumb()['tumb']?>" alt=""></div>
        <div class="cont_elem_item_title"><?= Html::encode($model->title) ?></div>
        <div class="cont_elem_item_preview"><?= HtmlPurifier::process($model->preview) ?></div>
        <div class="cont_elem_time">
            <?=date('d/m/Y H:i',$model->created_at);?>
        </div>
        <div class="cont_elem_btn">
            <?=Html::a(Yii::t('cont_elem', 'More..'),$model->genUrl(),['class' => 'btn btn-default btn-xs']);?>
        </div>
    </div>
</div>

