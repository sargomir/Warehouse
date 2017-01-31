<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

use kartik\detail\DetailView;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\select2\Select2;

use app\modules\warehouse\Warehouse as Module;
use app\modules\warehouse\models\Products;
use app\modules\warehouse\models\ProductsSearch;
use app\modules\warehouse\models\Manufacturers;
use app\modules\warehouse\models\Measures;

/* @var $this yii\web\View */
/* @var $model app\models\Contents */
/* @var $form yii\widgets\ActiveForm */

$productmodel = isset($model->product) ? $model->product : new Products();
$form_url = !empty($create) ? 'contents/create' : 'contents/update';
$this->beginBlock("content_widget");
echo DetailView::widget([
    'model' => $model,
    'condensed' => true,
    'hover' => true,
    'mode' => isset($create) ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
	'formOptions' => [
		'action' => Url::to([$form_url, 'id' => !empty($create) ? $model->document_id : $model->content_id]),
	//	'options' => ['data-pjax' => true]
	],	
    'panel' => [
        'heading' => Module::t('app', 'Document content'),
        'type' => DetailView::TYPE_PRIMARY,
        'headingOptions' => [
            'template' => Yii::$app->user->can('warehouse_write') ? '{buttons}{title}' : '{title}'
        ]
    ],
    'attributes' => [       
    /**
     * Product
     */
        [
            'group' => true,
            'label' => Module::t('app', 'Product'),
            'rowOptions' => ['class' => 'info']
        ],
        // Document (hidden fi)
        [
            'attribute' => 'document_id',
            'visible' => false,
        ],
        // Product
        [ 
            'attribute' => 'product_id',
            'label' => Module::t('app', 'Product'),
            'value' => isset($model->product->ProductInfo) ? $model->product->ProductInfo : '',
            'format' => 'raw',
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => ArrayHelper::map(Products::find()->orderBy('product')->all(), 'product_id', 'productInfo', 'manufacturer.manufacturer'),
                'options' => [
                    'placeholder' => Module::t('app', 'Select ...')
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width' => '100%'
                ],
                'disabled' => ! $model->isAttributeActive('product_id'),
                'addon' => [
                //	'prepend' => [
                //		'content' => Html::a( '<div class="btn btn-primary"><i class="fa fa-search"></i> ' .
                //                Module::t('app', 'Search') . '</div>',
                //            ['products/select', 'id' => $model->ID],
                //            ['title' => Module::t('app', 'Search Product')]
                //		),
                //		'asButton' => true
                //	],
                    'append' => [
                        'content' => Html::a( '<div class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> ' .
                                Module::t('app', 'Create') . '</div>',
                            ['products/create', 'did' => $model->document_id],
                            ['title' => Module::t('app', 'New product')]
                        ),
                        'asButton' => true
                    ]
                ]
            ]
        ],
        // Amount
        'amount',
        //[
        //    'attribute' => 'amount',
        //    'format' => 'raw',
        //    'type' => DetailView::INPUT_SPIN,
        //    'widgetOptions' => [
        //        'pluginOptions' => [
        //            'initval' => 0,
        //            'min' => 0.00001,
        //            'max' => 1000000,
        //            'step' => 0.00001,
        //            'decimals' => 5,
        //            'boostat' => 5,
        //            'maxboostedstep' => 10
        //        ],
        //        'disabled' => ! $model->isAttributeActive('amount')
        //    ]
        //],
        // Price
        [ 
            'attribute' => 'price',
            'format' => ['decimal', 2],
        ]
    ]
]);
$this->endBlock();
?>

<div class="contents-form">
	<?= $this->blocks["content_widget"]?>
</div>