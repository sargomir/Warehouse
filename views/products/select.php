<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
$id = $document_id;
?>
<div class="products-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['products/create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php \yii\widgets\Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'Product',
            [
              'label' => Yii::t('app', 'Manufacturer'),
              'attribute' => 'Manufacturer',
              'value' => 'manufacturer.Manufacturer'
            ],
            'Article',
            'Description',
            [
                // 'attribute' => Yii::t('app', 'Select...'),
                'value' => function ($model) use ($id) {
                    return Html::a('<span class="glyphicon glyphicon-ok"/>', ['contents/create', 'id' => $id, 'pid' => $model->ID], ['class' => 'btn btn-success', 'data-pjax' => 0]);
                },
                'format' => 'raw',
            ],
        ],
        // 'rowOptions' => function ($model, $key, $index, $grid) {
        //     return ['id' => $model['ID'], 'onclick' => 'alert(this.id);'];
        // },
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>
