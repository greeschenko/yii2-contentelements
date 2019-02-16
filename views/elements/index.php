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
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p class="text-right">
        <?= Html::a(Yii::t('cont_elem', 'Create Element'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'urld:url',
            [
                'attribute' => 'user_id',
                'filter' => $searchModel->getAdminsList(),
                'content' => function ($data) {
                    return $data->user->username;
                },
            ],
            [
                'attribute' => 'parent',
                'filter' => $searchModel->getParentList(),
                'content' => function ($data) {
                    $res = Yii::t('cont_elem', 'root page');
                    if ($data->parent != 0 and isset($data->parentData)) {
                        $res = $data->parentData->title;
                    }

                    return $res;
                },
            ],
            [
                'attribute' => 'type',
                'filter' => $searchModel->typelist,
                'content' => function ($data) {
                    return $data->typelist[$data->type];
                },
            ],
            [
                'attribute' => 'status',
                'filter' => $searchModel->statuslist,
                'content' => function ($data) {
                    return $data->statuslist[$data->status];
                },
            ],
            'tags',
            // 'preview:ntext',
            // 'content:ntext',
            // 'meta_title',
            // 'meta_descr',
            // 'meta_keys',
            // 'atachments',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}<br>{delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
