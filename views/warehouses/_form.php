<?php

use yii\helpers\Html;

use kartik\detail\DetailView;

use app\modules\warehouse\Warehouse as Module;

/* @var $this yii\web\View */
/* @var $model app\models\Warehouses */
/* @var $form yii\widgets\ActiveForm */

$user = Yii::$app->user;

// Action buttons
$template = '';
if ($user->can('warehouse_write')) $template .= ' {update}';
if ($user->can('admin')) $template .= ' {delete}';
?>

<div class="warehouses-form">

	<?= DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'mode' => isset($create) ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
		'deleteOptions' => ['url' => ['warehouse/delete','id' => $model->warehouse_id]],
        'panel' => [
            'heading' => isset($model->Warehouse) ? Module::t('app', 'Warehouse') : Module::t('app', 'New warehouse'),
            'type' => DetailView::TYPE_PRIMARY,
        ],        
		'buttons1' => $template,
        'attributes' => [
            [// Warehouse
                'attribute' => 'warehouse',
                'format' => 'raw',
                'type' => DetailView::INPUT_TEXT,
			],
            [// Address
                'attribute' => 'description',
                'format' => 'raw',
                'type' => DetailView::INPUT_TEXT,
			],
        ],
	]) ?>

</div>
