<?php

use yii\helpers\Html;

use app\modules\warehouse\Warehouse as Module;
use app\modules\warehouse\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ManufacturersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Manufacturers');
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user;

//Buttons
$btnProductCreate = $user->can('warehouse_write')
	? Html::a('<i class="fa fa-plus"></i> ' . Module::t('app', 'Create'),
		['manufacturers/create'], ['class' => 'btn btn-success btn-create']
	  )
	: null;

//ActionButtons
$template = '{view}';
if ($user->can('warehouse_write')) $template .= ' {update}';
if ($user->can('admin')) $template .= ' {delete}';

?>
<div class="manufacturers-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => ['type' => isset($readOnly) ? GridView::TYPE_DEFAULT : GridView::TYPE_PRIMARY],
    	'toolbar' => ['contents' => $btnProductCreate],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'manufacturer_id',
				'width' => '1%',
			],
            'manufacturer',

            [
				'class' => 'yii\grid\ActionColumn',
				'template' => $template,
			],
        ],
    ]); ?>

</div>
