<?php

use app\modules\warehouse\Warehouse as Module;


/* @var $this yii\web\View */
/* @var $model app\models\Manufacturers */

$this->title = Module::t('app', 'Manufacturers');
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Manufacturers'), 'url' => ['manufacturers/index']];
$this->params['breadcrumbs'][] = Module::t('app', 'New manufacturer');
?>
<div class="manufacturers-create">

    <?= $this->render('_form', [
        'model' => $model,
    	'create' => true,
    ]) ?>

</div>
