<?php

use app\modules\warehouse\Warehouse as Module;

/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = Module::t('app', 'Related documents');
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->product_id, 'url' => ['products/view', 'id' => $model->product_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="products-track">
    <?= $this->render('_form', [
        'model' => $model,
        'readOnly' => true
    ]) ?>
</div>

<div class="product-documents">
    <?= $this->render('/products/_documents', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'readOnly' => true
    ]) ?>
</div>
