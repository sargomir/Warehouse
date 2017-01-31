<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'Product') ?>

    <?= $form->field($model, 'Manufacturer') ?>

    <?= $form->field($model, 'Article') ?>

    <?= $form->field($model, 'Description') ?>

    <?php // echo $form->field($model, 'Measure') ?>

    <?php // echo $form->field($model, 'Price') ?>

    <?php // echo $form->field($model, 'ProductType') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
