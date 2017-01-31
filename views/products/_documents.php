<?php

use app\modules\warehouse\Warehouse as Module;
use app\modules\warehouse\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="products-documents">
<?= GridView::widget([
        'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
    	'panel' => [
    		'type' => isset($readOnly) ? GridView::TYPE_DEFAULT : GridView::TYPE_PRIMARY,
    		'heading' => Module::t('app', 'Related documents'), // . (isset($readOnly) ? $btnContentIndex : ''),
    	],        
        'columns' => [
            [
		'attribute' => '_type_id',
		'label' => Module::t('app', 'Document type'),
		'value' => function ($content) {
			switch ($content->document->type_id) {
				case 1: return Module::t('app', 'Supply');
				case 2: return Module::t('app', 'Transfer');
				case 3: return Module::t('app', 'Write-off');
			}
		},
		'filter' => yii\helpers\Html::activeDropDownList(
			$searchModel,
			'_type_id',
			[
				1 => Module::t('app', 'Supply'),
				2 => Module::t('app', 'Transfer'),
				3 => Module::t('app', 'Write-off')
			],
			[
				'class' => 'form-control',
				'prompt' => Module::t('app', 'All')
			]
		),
	    ],
            [
                'attribute' => '_document',
		'label' => Module::t('app', 'Document'),
                'value' => function ($model, $key, $index, $widget) {
                    return \yii\helpers\Html::a(
                        $model->document->document,
                        ['/warehouse/contents/index', 'id' => $model->document_id],
                        ['class' => 'kv-editable-link']
                    );
                },
                'format' => 'html'
            ],
            [
                'attribute' => '_date',
		'label' => Module::t('app', 'Date'),
		'value' => 'document.date',
                'format' =>  'Date',
                'options' => ['width' => '100']
            ],
            [
		'attribute' => '_from_warehouse',
		'label' => Module::t('app', 'From'),
		'value' => 'document.from_warehouse.warehouse'
            ],
            [
              'attribute' => '_to_warehouse',
              'label' => Module::t('app', 'To'),
              'value' => 'document.to_warehouse.warehouse'
            ],
            [
		'attribute' => 'amount',
                'value' => 'amount_info'
            ]
        ],
    ]);
?>
</div>