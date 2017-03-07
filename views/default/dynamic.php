<?php
use yii\widgets\ListView;
?>

<?php if (isset($model) and $model != null): ?>
    <?php echo $this->render('static_min', ['model' => $model]); ?>
<?php endif; ?>

<?php echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="raw cont_elem_list">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_view',
    ]); ?>
</div>

