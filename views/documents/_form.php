<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use kartik\detail\DetailView;

use app\modules\warehouse\Warehouse as Module;
use app\modules\warehouse\GridView;
use app\modules\warehouse\models\Warehouses;
use app\modules\warehouse\models\Types;
use app\modules\warehouse\models\Companies;
use app\modules\warehouse\models\ContentsSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Documents */
/* @var $form yii\widgets\ActiveForm */

$user = Yii::$app->user;

/* OBSOLETE
 * We have to enable form elements or they will not be submitted to model!
 */
//$this->registerJs('
//    jQuery("form").submit(function(e) {
//        document.getElementById("documents-from").disabled = false;
//        document.getElementById("documents-to").disabled = false;
//    });
//');

// Buttons
$btnDocumentUpdate = null;
if ($user->can('warehouse_write'))
	$btnDocumentUpdate = Html::a(' <div class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> ' . Module::t('app', 'Update') . '</div>',
	['documents/update', 'id' => isset($model->document_id) ? $model->document_id : null], ['data-pjax'=>0]);

//ActionButtons
$template = '';
if ($user->can('warehouse_write') && !isset($readOnly))
	$template .= ' {update}';
else
	$template = $btnDocumentUpdate;
if ($user->can('admin') && !isset($readOnly)) $template .= ' {delete}';
?>

<div class="documents-form">

	<?= DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'mode' => isset($create) ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => isset($model->document)
				? Module::t('app', 'Document')
				: Module::t('app', 'New document'),
            'type' => !$user->can('warehouse_write') || isset($readOnly) ? DetailView::TYPE_DEFAULT : DetailView::TYPE_PRIMARY,
//            'headingOptions' => ['template'=> $user->can('warehouse_write') && !isset($readOnly)
//				? '{buttons}{title}'
//				: '<h3 class="panel-title">' . Module::t('app', 'Document'). '<div class="pull-right">' . $btnDocumentUpdate . '</div></h3>'
//            ],
        ],
		'buttons1' => $template,
		'deleteOptions' => ['url' => ['documents/delete','id' => $model->document_id]],
        'attributes' => [
            [// Document
                'attribute' => 'document',
                'format' => 'raw',
                'type' => DetailView::INPUT_TEXT,
			],
            [// Company
                'attribute' => 'company_id',
				'label' => Module::t('app', 'Company'),
            	'value' => isset($model->company) ? $model->company->company : '',
                'format' => 'raw',
                'type' => DetailView::INPUT_SELECT2,
				'widgetOptions' => [
					'data' => ArrayHelper::map(Companies::find()->asArray()->all(), 'company_id', 'company'),
					'options' => ['placeholder' => Module::t('app', 'Select ...')],
					'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],                            
					'disabled' => ! $model->isAttributeActive('company_id'),
				],
			],
			[// Document Type
        		'attribute' => 'type_id',
				'label' => Module::t('app', 'Document type'),
				'value' => $model->document_type,
				
        		'format' => 'raw',
        		'type' => DetailView::INPUT_SELECT2,
        		'widgetOptions' => [
        				'data' => [1 => 'Поставка', 2 => 'Перемещение', 3 => 'Списание'],//ArrayHelper::map(Types::find()->asArray()->all(), 'ID', 'Type'),
        				'options' => [
        					'placeholder' => Module::t('app', 'Select ...'),
// OBSOLETE       					'onchange' => '
//				                JS: var doctype = (this.value);
//				                if(doctype == "1") {
//				                    // jQuery
//				                    $("#documents-from option:selected").prop("selected", false);
//				                    $("#documents-from option:first").attr("selected","selected");
//				                    $("#documents-from").attr("disabled", "disabled");
//				                    $("#documents-to").removeAttr("disabled");
//				                }
//				                if(doctype == "3") {
//				                    // JS
//				                    document.getElementById("documents-to").value = "";
//				                    document.getElementById("documents-from").disabled = false;
//				                    document.getElementById("documents-to").disabled = true;
//				                }
//				                if(doctype == "2") {
//				                    document.getElementById("documents-from").disabled = false;
//				                    document.getElementById("documents-to").disabled = false;
//				                }
//				            '
        				],
        				'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
        				'disabled' => ! $model->isAttributeActive('type_id'),
        		],
        	],
			[// Date
        		'attribute' => 'date',
				'value' => Yii::$app->formatter->asDate($model->date, 'php:d.m.Y'),
        		'type' => DetailView::INPUT_DATE,
        		'options' => [
        			'disabled' => ! $model->isAttributeActive('date'),
        			'value' => isset($model->date) ? Yii::$app->formatter->asDate($model->date, 'php:d.m.Y') : '',
        			'format' => 'raw',
        		],
        		'widgetOptions' => [
        			'pluginOptions' => [
			            'autoclose' => true,
			            'todayHighlight' => true,
			            'todayBtn' => 'linked',
					],
        		],
			],
        	[// From
        		'attribute' => 'from',
				'label' => Module::t('app', 'From'),
				'value' => isset($model->from_warehouse) ? $model->from_warehouse->warehouse: '',        			
        		'format' => 'raw',
        		'type' => DetailView::INPUT_SELECT2,
        		'widgetOptions' => [
        				'data' => ArrayHelper::map(Warehouses::find()->asArray()->all(), 'warehouse_id', 'warehouse'),
        				'options' => ['placeholder' => Module::t('app', 'Select ...')],
        				'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
        				'disabled' => ! $model->isAttributeActive('from'),
        		],
        	],
        	[// To
        		'attribute' => 'to',
				'label' => Module::t('app', 'To'),
				'value' => isset($model->to_warehouse) ? $model->to_warehouse->warehouse: '',
        		'format' => 'raw',
        		'type' => DetailView::INPUT_SELECT2,
        		'widgetOptions' => [
        				'data' => ArrayHelper::map(Warehouses::find()->asArray()->all(), 'warehouse_id', 'warehouse'),
        				'options' => ['placeholder' => Module::t('app', 'Select ...')],
        				'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
        				'disabled' => ! $model->isAttributeActive('to'),
        		],
        	],
			[// Comment
				'attribute' => 'comment',
			],
        ],
	]) ?>

</div>