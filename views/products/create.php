<?php

use app\modules\warehouse\Warehouse as Module;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
$this->title = Module::t('app', 'Products');
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'New product'), 'url' => ['products/create']];
?>

<div class="products-create">
    <?= $this->render('_form', [
        'model' => $model,
    	'create' => true,
    ]) ?>
</div>
