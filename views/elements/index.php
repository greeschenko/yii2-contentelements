<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel greeschenko\contentelements\models\ElementsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('cont_elem', 'Manage content elements');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elements-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="text-right">
        <?= Html::a(Yii::t('cont_elem', 'Create Element'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'urld:url',
            'user_id',
            'parent',
            // 'preview:ntext',
            // 'content:ntext',
            // 'tags',
            // 'meta_title',
            // 'meta_descr',
            // 'meta_keys',
            // 'atachments',
            // 'created_at',
            // 'updated_at',
            // 'type',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
