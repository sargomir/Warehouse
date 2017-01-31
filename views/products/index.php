<?php
/**
 *	PHP 7.0 required
 */

use yii\helpers\Html;

use app\modules\warehouse\Warehouse as Module;
use app\modules\warehouse\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user;

//Buttons
$btnProductCreate = $user->can('warehouse_write')
	? Html::a('<i class="fa fa-plus"></i> ' . Module::t('app', 'Create'),
		['products/create'], ['class' => 'btn btn-success btn-create']
	  )
	: null;
	
//ActionButtons
$template = '{view} {track}';
if ($user->can('warehouse_write')) $template .= ' {update}';
if ($user->can('admin')) $template .= ' {delete}';

?>
<div class="products-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => isset($readOnly) ? GridView::TYPE_DEFAULT : GridView::TYPE_PRIMARY],
		'toolbar' => ['contents' => $btnProductCreate],
        'columns' => [
//             ['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'product_id',
				'width' => '1%',
			],
            'product',
            [
              'attribute' => 'manufacturer',
              'value' => 'manufacturer.manufacturer',
			  'label' => Module::t('app', 'Manufacturer'),
            ],
            'article',
            'description',
			[
				'attribute' => 'availability',
				'value' => function ($model) { return $model->availability->availability_info ?? ''; }, //'availability.availability',
				'label' => Module::t('app', 'Availability'),
			],			
            [
              'class' => 'yii\grid\ActionColumn',
              'buttons'=>[
                'track'=>function ($url, $model) {
                  return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-search"></span>', ['products/track', 'id' => $model->product_id],
                  ['title' => Module::t('app', 'Tracking'), 'data-pjax' => '0']);
                }
              ],
              'template' => $template,
            ],   
        ],
    ]); ?>

</div>
