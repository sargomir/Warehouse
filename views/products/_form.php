<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use kartik\detail\DetailView;

use app\modules\warehouse\Warehouse as Module;
use app\modules\warehouse\models\Manufacturers;
use app\modules\warehouse\models\Dimensions;

use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */

$user = Yii::$app->user;

//ActionButtons
$template = '';
if ($user->can('warehouse_write') && !isset($readOnly)) $template .= ' {update}';
if ($user->can('admin') && !isset($readOnly)) $template .= ' {delete}';
?>

<div class="products-form">
	<?= DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'mode' => isset($create) ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
		//'formOptions' => [
		//	'action' => Url::to(['products/create', 'did' => isset($did) ? $did : null]),
		//	//'options' => ['data-pjax' => true]
		//],
        'panel' => [
            'heading' => isset($model->product) ? Module::t('app', 'Product') : Module::t('app', 'New product'),
            'type' => isset($readOnly) ? DetailView::TYPE_DEFAULT :  DetailView::TYPE_PRIMARY,
        ],
		'buttons1' => $template,
		//'formOptions' => ['action' => ['products/update?id=' . $model->product_id]],
		'deleteOptions' => ['url' => ['products/delete','id' => $model->product_id]],
        'attributes' => [
            [// Product
                'attribute' => 'product',
                'format' => 'raw',
                'type' => DetailView::INPUT_TEXT,
			],
            [// Manufacturer
                'attribute' => 'manufacturer_id',
				'label' => Module::t('app', 'Manufacturer'),
            	'value' => isset($model->manufacturer) ? $model->manufacturer->manufacturer : '',
                'format' => 'raw',
                'type' => DetailView::INPUT_SELECT2,
				'widgetOptions' => [
					'data' => ArrayHelper::map(Manufacturers::find()->asArray()->all(), 'manufacturer_id', 'manufacturer'),
					'options' => ['placeholder' => Module::t('app', 'Select ...')],
					'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],                            
					'disabled' => ! $model->isAttributeActive('manufacturer_id'),
				],
			],
        	[// Article
        		'attribute' => 'article',
        		'format' => 'raw',
        		'type' => DetailView::INPUT_TEXT,
        	],
        	[// Description
        		'attribute' => 'description',
        		'format' => 'raw',
        		'type' => DetailView::INPUT_TEXT,
        	],
        	[// Measure
        		'attribute' => 'dimension_id',
        		'value' => isset($model->dimension) ? $model->dimension->dimension : '',
        		'format' => 'raw',
        		'type' => DetailView::INPUT_SELECT2,
        		'widgetOptions' => [
        				'data' => ArrayHelper::map(Dimensions::find()->asArray()->all(), 'dimension_id', 'description'),
        				'options' => ['placeholder' => Module::t('app', 'Select ...')],
        				'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
        				'disabled' => ! $model->isAttributeActive('dimension_id'),
        		],
        	],        		
        	//[// Price
        	//	'attribute' => 'price',
        	//	'format' => 'raw',
        	//	'type' => DetailView::INPUT_TEXT,
        	//],
        ],
	]) ?>
	<?php if (isset($product_last_insert_id)) echo '<input type="hidden" id="product-last-insert-id" class="form-control" name="pliid" value="' . $product_last_insert_id . '" type="text">'; ?>
</div>
