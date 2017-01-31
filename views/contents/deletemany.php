<?php

use yii\helpers\Html;

use app\modules\warehouse\Warehouse as Module;

use kartik\builder\TabularForm;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Contents */

$this->title = 'Документ №' . $searchContent->document_id;
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Documents'), 'url' => ['documents/index']];
$this->params['breadcrumbs'][] = ['label' => isset($searchContent->document_id) ? $searchContent->document_id : "", 'url' => ['documents/view', 'id' => $searchContent->document_id]];
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Document content'), 'url' => ['contents/index', 'id' => $searchContent->document_id]];
$this->params['breadcrumbs'][] = Module::t('app', 'Delete selected');

// Data paging not working in tabular form
$contentProvider->setPagination(false);
$this->beginBlock('gridview-deletemany');
    $form = ActiveForm::begin();
    echo TabularForm::widget([
       'dataProvider' => $contentProvider,
       'form' => $form,
       'attributes' => [
            // primary key column
            'content_id' => [
                'label' => Module::t('app', 'Content Id'),
                'type' => TabularForm::INPUT_HIDDEN_STATIC,
                'value' => function ($model) { return $model->content_id; },
            ],
            '_product' => [
                'label' => Module::t('app', 'Product'),
                'type' => TabularForm::INPUT_RAW,
                'value' => function ($model) { return $model->product->product; },
            ],
            '_article' => [
                'label' => Module::t('app', 'Article'),
                'type' => TabularForm::INPUT_RAW,
                'value' => function ($model) { return $model->product->article; },
            ],
            '_description' => [
                'label' => Module::t('app', 'Description'),
                'type' => TabularForm::INPUT_RAW,
                'value' => function ($model) { return $model->product->description; },
            ],
            '_manufacturer' => [
                'label' => Module::t('app', 'Manufacturer'),
                'type' => TabularForm::INPUT_RAW,
                'value' => function ($model) { return $model->product->manufacturer->manufacturer; },
            ],
            'amount' => [
                'label' => Module::t('app', 'Amount'),
                'type' => TabularForm::INPUT_RAW,
                'value' => function ($model) { return $model->amount; },
            ],
        ],
        'gridSettings'=>[
            //'floatHeader'=>true,
            'panel'=>[
                'heading' => '<h3 class="panel-title"><i class="fa fa-file-text"></i> ' . Module::t('app', 'Delete selected') . '</h3>',
                'type' => GridView::TYPE_PRIMARY,
                //'before' => "<strong>{$searchContent->from->Warehouse}</strong>",
                'after'=> Html::submitButton('<i class="glyphicon glyphicon-remove-sign"></i> ' . Module::t('app', 'Delete'), ['class'=>'btn btn-danger'])
                //Html::a('<i class="glyphicon glyphicon-plus"></i> Add New', '#', ['class'=>'btn btn-success']) . ' ' . 
                //Html::a('<i class="glyphicon glyphicon-remove"></i> Delete', '#', ['class'=>'btn btn-danger']) . ' ' .
                //Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['class'=>'btn btn-primary'])
            ],
        ],
        'rowSelectedClass' => 'danger',
        'actionColumn'=>false,
    ]);
    ActiveForm::end();
$this->endBlock();
?>

<div class="contents-deletemany">
    <?php if (isset ($result)) echo "<p class='alert alert-danger'>$result</p>" ?>
    <?php if (isset ($this->blocks['gridview-deletemany'])) {echo $this->blocks['gridview-deletemany'];} ?>
</div>
