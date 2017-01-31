<?php

use yii\helpers\Html;
use yii\helpers\Url;

use app\modules\warehouse\Warehouse as Module;
//use app\modules\warehouse\GridView;

use kartik\builder\TabularForm;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Products');
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Documents'), 'url' => ['documents/index']];
$this->params['breadcrumbs'][] = ['label' => $document_id, 'url' => ['contents/index', 'id' => $document_id]];

$id = $document_id;

$documentModel = app\modules\warehouse\models\Documents::findOne($id);

/**
 * Data Toggle button
 */
$dataToggle = Yii::$app->request->get('dataToggle');
$btnToggleData = Html::a(
        '<i class="glyphicon glyphicon-resize-full"></i> ' . Module::t('app', 'All'),
        Url::current(['dataToggle' => isset($dataToggle) ? null : true]),
        [
            'class' => isset($dataToggle) ? 'btn btn-default active' : 'btn btn-default',
            //'data-confirm' => !isset ($dataToggle) && $dataProvider->totalCount > 100
            //    ? Module::t('app', 'There are {0} records. Are you sure you want to display them all?', ['0' => $dataProvider->totalCount])
            //    : null,
        ]
    );
if (isset ($dataToggle))
    $dataProvider->pagination->pageSize=0;

/**
 * Select maximum amount button
 */
$selectAll = Yii::$app->request->get('selectAll');
$btnSelectAll = Html::a(
        '<i class="glyphicon glyphicon-plus"></i> ' . Module::t('app', 'Максимум'),
        Url::current(['selectAll' => isset($selectAll) ? null : true]),
        [
            'class' => isset($selectAll) ? 'btn btn-default active' : 'btn btn-default',
        ]
    );

/**
 * Form table
 */
$this->beginBlock('table-input-contents');
    $form = ActiveForm::begin();
    echo TabularForm::widget([
       'dataProvider' => $dataProvider,
       'form' => $form,
       'attributes' => [
            // primary key column
            'product'=>[
                'label' => Module::t('app', 'Product'),
                'type' => TabularForm::INPUT_HIDDEN_STATIC,
                'value' => function ($model) { return $model->product->product; },
            ],
            'manufacturer'=>[
                //'attribute' => 'product.manufacturer.manufacturer',
                'label' => Module::t('app', 'Manufacturer'),
                'type' => TabularForm::INPUT_STATIC,
                'value' => function ($model) { return $model->product->manufacturer->manufacturer; },
            ],
            'article'=>[
                'label' => Module::t('app', 'Article'),
                'type' => TabularForm::INPUT_STATIC,
                'value' => function ($model) { return $model->product->article; },
            ],
            'warehouse'=>[
                'label' => Module::t('app', 'Warehouse'),
                'type' => TabularForm::INPUT_STATIC,
                'value' => function($model) {return $model->warehouse->warehouse; },
                'visible' => false
            ],
            'availability'=>[
                'label' => Module::t('app', 'Amount'),
                'type' => TabularForm::INPUT_TEXT,
                //'hint' => function ($model) {return 'В наличии: ' . $model->availability_info; },
                //'options' => ['value' => isset ($selectAll) ? null : 0],
                'options' => function ($model) {
                    $selectAll = Yii::$app->request->get('selectAll');
                    return ['value' => isset ($selectAll) ? null : 0, 'placeholder' => 'Максимум ' . $model->availability];
                },
                'container' => ['class' => 'input-group'],
                //'type' => TabularForm::INPUT_WIDGET,
                //'widgetClass' => GridView::FILTER_SPIN,
                //'options' => [
                //    'pluginOptions' => [
                //        'min' => 0,
                //        'verticalbuttons' => true,
                //    ]
                //]
            ],
        ],
        'gridSettings'=>[
            //'floatHeader'=>true,
            'panel'=>[
                'heading' => '<h3 class="panel-title"><i class="fa fa-cube"></i> ' . Module::t('app', 'Products availability') . '</h3>',
                'type' => GridView::TYPE_PRIMARY,
                'before' => "<strong>{$documentModel->from_warehouse->warehouse}</strong>",
                'after'=> $documentModel->type_id == 2
                    ? Html::submitButton('<i class="glyphicon glyphicon-circle-arrow-left"></i> ' . Module::t('app', 'Transfer'), ['class'=>'btn btn-success'])
                    : Html::submitButton('<i class="glyphicon glyphicon-remove-sign"></i> ' . Module::t('app', 'Write-off'), ['class'=>'btn btn-warning'])
                //Html::a('<i class="glyphicon glyphicon-plus"></i> Add New', '#', ['class'=>'btn btn-success']) . ' ' . 
                //Html::a('<i class="glyphicon glyphicon-remove"></i> Delete', '#', ['class'=>'btn btn-danger']) . ' ' .
                //Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['class'=>'btn btn-primary'])
            ],
            'toolbar' => [
                //['content' => '<input style="width: 80px; height: 30px" type=button onclick="window.location=\'' . Url::current(['dataToggle' => isset($dataToggle) ? null : true]). '\'" value=Variant3>'],
                //['content' => Html::a(
                //        '<i class="glyphicon glyphicon-resize-full"></i> ' . Module::t('app', 'All'),
                //        Url::current(['dataToggle' => isset($dataToggle) ? null : true]),
                //        [
                //            'class' => isset($dataToggle) ? 'btn btn-default active' : 'btn btn-default',
                //            'data-confirm' => !isset ($dataToggle) && $dataProvider->totalCount > 100
                //                ? Module::t('app', 'There are {0} records. Are you sure you want to display them all?', ['0' => $dataProvider->totalCount])
                //                : null,
                //        ]
                //    )
                //]
                ['content' => $btnSelectAll],
                ['content' => $btnToggleData],
                //[
                //    'content' => '<div class="btn btn-default"><i class="glyphicon glyphicon-resize-full"></i> Всё</div>',  
                //],
                //'{toggleData}',
            ],
        ],
        'rowSelectedClass' => $documentModel->type_id == 2 ? 'success' : 'warning',
        'actionColumn'=>false,
    ]);
    ActiveForm::end();
$this->endBlock();
    
?>


<div class="products-index">
    <p class="alert alert-info">
        <?= Module::t('app', 'Select many hint'); ?>
    </p>
    <?= $this->blocks['table-input-contents'] ?>
</div>