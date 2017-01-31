<?php

use app\modules\warehouse\Warehouse as Module;
use app\modules\warehouse\GridView;
use app\modules\warehouse\models\AvailabilitySearch;
use app\modules\warehouse\models\Products;

use app\modules\warehouse\models\ProductsSearch;
use app\modules\warehouse\models\Manufacturers;
use app\modules\warehouse\models\Measures;
use app\modules\warehouse\models\Contents;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use yii\widgets\DetailView;

$user = Yii::$app->user;

$this->title = Module::t('app', 'Documents');
$this->params['breadcrumbs'] = null;
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Documents'), 'url' => ['documents/index']];
$this->params['breadcrumbs'][] = ['label' => $searchModel->document->document_id];

// Show document details
if (isset ($documentModel))
	echo $this->render('/documents/_form', [
	   'model' => $documentModel,
	   'readOnly' => true,
	]);

/**
 * Buttons to display
 */
$btnContentIndex = null;
if ($user->can('warehouse_write'))
	$btnContentIndex = Html::a(' <div class="btn btn-primary"><i class="fa fa-pencil"></i> ' . Module::t('app', 'Update') . '</div>',
		['contents/index', 'id' => isset($searchModel) ? $searchModel->document_id : null], ['data-pjax'=>0]
	);

$btnContentAdd = null;
if ($user->can('warehouse_write'))
	if ($searchModel->document->type_id == 1)
		// В документ поставки записи создаются без ограничений
		$btnContentAdd = Html::a( '<span class="btn btn-success"><span class="glyphicon glyphicon-plus">'
			. Module::t('app', 'Create') . '</span></span>',
			['contents/create', 'id' => isset($documentModel) ? $documentModel->document_id : null],
			['title' => Module::t('app', 'Create Product'), 'data-pjax' => '0']
		);
	else
		// В документ перемещения/списания можно добавлять только записи из имеющихся в ниличии товаров
		$btnContentAdd = Html::a( '<span class="btn btn-success"><span class="glyphicon glyphicon-plus">'
			. Module::t('app', 'Create') . '</span></span>',
			[
				'products/selectmany',
				'id' => isset($documentModel) ? $documentModel->document_id : null,
				'wh' => isset($documentModel) ? $documentModel->from : null,
			],
			['title' => Module::t('app', 'Create Product'), 'data-pjax' => '0']
		);

$btnContentDeleteMany = null;
if ($user->can('warehouse_write')) {
	$document = $searchModel->document;
	if ($dataProvider->getTotalCount())
	$btnContentDeleteMany = Html::a(
		'<div class="btn btn-danger"><i class="glyphicon glyphicon-remove-sign"></i> ' . Module::t('app', 'Delete') . '</div>',
		['contents/deletemany', 'did' => $document->document_id],
		['title' => Module::t('app', 'Delete')]
	);
}
?>

<div class="contents-index">

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export' => false,
    	'panel' => [
    		'type' => !$user->can('warehouse_write') || isset($readOnly) ? GridView::TYPE_DEFAULT : GridView::TYPE_PRIMARY,
    		'heading' => Module::t('app', 'Document content'), // . (isset($readOnly) ? $btnContentIndex : ''),
			'before' => isset($readOnly) ? $btnContentIndex : $btnContentDeleteMany,
    	],
		'toolbar' => [
			['content' => isset($readOnly) ? '' : $btnContentAdd],
		],	

        'columns' => [
            [// ID
                'attribute' => 'content_id',
                'width' => '1%',
            ],  
            [// Product
                'label' => Module::t('app', 'Product'),
                'attribute' => '_product',
                'value' => 'product.product',
            ],
            [// Article
                'label' => Module::t('app', 'Article'),
                'attribute' => '_article',
                'value' => 'product.article',
            ],
            [// Description
                'label' => Module::t('app', 'Description'),
                'attribute' => '_description',
                'value' => 'product.description',
            ],
            [// Manufacturer
                'label' => Module::t('app', 'Manufacturer'),
                'attribute' => '_manufacturer',
                'value' => 'product.manufacturer.manufacturer',
            ],
            [// Amount
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'amount',
                'value' => 'amount_info',
				'readonly' => isset($readOnly) || !Yii::$app->user->can('warehouse_write'),//function($model, $key, $index, $widget) {
                    // Disable ajax-update for restricted users
                    //return Yii::$app->user->can('warehouse_write');; 
                //},
                'editableOptions' => [
                    'header' => Module::t('app', 'Amount'), 
                    'inputType' => \kartik\editable\Editable::INPUT_TEXT,
//                    'options' => [
//						'pluginOptions' => [
//							'min' => 0.00001,
//							'max' => 1000000,
//							'step' => 0.00001,
//							//'decimals' => 5,
//							'boostat' => 5,
//							'maxboostedstep' => 10
//						]
//                    ],
                	'formOptions' => ['action' => Url::toRoute(['contents/index', 'id'=>$searchModel->document->document_id])],
                ],
                //'hAlign' => 'left', 
                //'vAlign' => 'middle',
                'width' => '7%',
                //'format' => ['decimal', 2],
                'pageSummary' => true
            ],
			[// Price
                'attribute' => 'price',
				'width' => '7%',
            ],

            [// Action Buttons
            	'class' => 'yii\grid\ActionColumn',
				'visible' => $user->can('warehouse_write') && !isset($readOnly), // Disable actions in readonly mode
            ],
        ],
    ]); ?>
</div>