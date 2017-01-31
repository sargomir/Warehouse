<?php

use app\modules\warehouse\Warehouse as Module;

/* @var $this yii\web\View */
/* @var $model app\models\Warehouses */

$this->title = Module::t('app', 'Warehouses');
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Warehouses'), 'url' => ['warehouses/index']];
$this->params['breadcrumbs'][] = ['label' => $model->warehouse_id];
?>
<div class="warehouses-update">

    <?= $this->render('_form', [
        'model' => $model,
    	'create' => true,
    ]) ?>

</div>
