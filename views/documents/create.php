<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Documents */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="documents-create">

    <?= $this->render('_form', [
        'model' => $model,
    	'create' => true,
    ]) ?>

</div>
