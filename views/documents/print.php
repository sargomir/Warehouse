<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Documents */

$this->title = $model->Document;
$header;
if ($model->Type == 0) {
    // Yii::$app->formatter->locale = 'ru_RU.UTF-8';
    $date = Yii::$app->formatter->asDatetime($model->Date, "php:d.m.Y");
    $this->title = "Накладная № $model->Document от $date";
    $warehouse = $model->to->Warehouse;
    $header = "
    <p>
        Покупатель: <b> $model->Company </b>
    </p>
    <p>
        Склад: <b> $warehouse </b>
    </p>";
} else {
    $this->title = "Требование-накладная № $model->Document от $model->Date";
    $header = "Организация: $model->Company
    Склад: $model->To";
}
$footer = "
<br />
<span>
  <span style='float:left;'>
    Отпустил ____________________
  </span>
  <span style='float:right;'>
    Получил ____________________
  </span>
</span>";
?>

<style type="text/css">
    table {
        width: 100%;
    }
    table, th, tr, td {
        border-style: solid;
        border-width: 1px;
        border-collapse: collapse;
    }
</style>
<body onload="window.print();">
    
    <div class="documents-view">
    
        <h1><?= Html::encode($this->title) ?></h1>
    
        <?= $header ?>
    
        <br />
        <?= GridView::widget([
            'id' => 'tbl_doc_print',
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                          'label' => Yii::t('app', 'Product'),
                          'attribute' => 'Product',
                          'value' => 'product.Product'
                ],
                'Amount',
                'Price',
            ],
            'summary'=>"",
        ]); ?>
    
        <?= $footer ?>
    
    </div>

</body>