<?php

use yii\helpers\Html;

use kartik\detail\DetailView;

use app\modules\warehouse\Warehouse as Module;

/* @var $this yii\web\View */
/* @var $model app\models\Manufacturers */
/* @var $form yii\widgets\ActiveForm */

$user = Yii::$app->user;

//ActionButtons
$template = '';
if ($user->can('warehouse_write')) $template .= ' {update}';
if ($user->can('admin')) $template .= ' {delete}';
?>

<div class="manufacturers-form">

	<?= DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'mode' => isset($create) ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => isset($model->Manufacturer) ? Module::t('app', 'Manufacturer') : Module::t('app', 'New manufacturer'),
            'type' => DetailView::TYPE_PRIMARY,
        ],
		'buttons1' => $template,		
		'deleteOptions' => ['url' => ['manufacturers/delete','id' => $model->manufacturer_id]],
        'attributes' => [
            [// Manufacturer
                'attribute' => 'manufacturer',
                'format' => 'raw',
                'type' => DetailView::INPUT_TEXT,
			],
        ],
	]) ?>

</div>
