<?php

use app\modules\warehouse\Warehouse as Module;
use app\modules\warehouse\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Manufacturers */

$this->title = Module::t('app', 'Manufacturers');
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Manufacturers'), 'url' => ['manufacturers/index']];
$this->params['breadcrumbs'][] = ['label' => $model->manufacturer_id];
?>

<div class="manufacturers-view">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
     	'panel' => [
    		'type' => GridView::TYPE_DEFAULT,
    		'heading' => Module::t('app', 'Products'), 
    	],         
        'columns' => [
            // ID
            [
              'attribute' => 'product_id',
              'options' => ['width' => '1%'],
            ],
            'product',
            'article',
            'description',
        ],
    ]); ?>

</div>
