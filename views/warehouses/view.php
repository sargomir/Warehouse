<?php

use yii\helpers\Html;

use app\modules\warehouse\Warehouse as Module;
use app\modules\warehouse\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Warehouses */

$this->title = Module::t('app', 'Warehouses');
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Warehouses'), 'url' => ['warehouses/index']];
$this->params['breadcrumbs'][] = ['label' => $model->warehouse_id];
?>
<div class="warehouses-view">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?= Gridview::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
     	'panel' => [
    		'type' => GridView::TYPE_DEFAULT,
    		'heading' => Module::t('app', 'Products availability'), // . (isset($readOnly) ? $btnContentIndex : ''),
    	], 
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
              'label' => Module::t('app', 'Product'),
              'attribute' => 'product',
              'value' => 'product.product'
            ],
            [
              'label' => Module::t('app', 'Manufacturer'),
              'attribute' => 'manufacturer',
              'value' => 'product.manufacturer.manufacturer'
            ],
            [
              'label' => Module::t('app', 'Article'),
              'attribute' => 'article',
              'value' => 'product.article'
            ],
            [
                'label' => Module::t('app', 'Amount'),
                'attribute' => 'availability',
                'value' => function ($model) {
                    $dimension = isset ($model->product->dimension->dimension)
                        ? " {$model->product->dimension->dimension}"
                        : "";
                    return floatval($model->availability) . $dimension;
                }
            ],
        ],
    ]); ?>
</div>
