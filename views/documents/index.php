<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use app\modules\warehouse\Warehouse as Module;
use app\modules\warehouse\GridView;
use app\modules\warehouse\models\Types;
use app\modules\warehouse\models\Companies;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('app', 'Documents');
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user;

//Buttons
$btnDocumentCreate = $user->can('warehouse_write')
  ? Html::a('<i class="fa fa-plus"></i> ' . Module::t('app', 'Create'),
      ['documents/create'], ['class' => 'btn btn-success btn-create']
    )
  : null;

//ActionButtons
$template = '{view} {list}';
if ($user->can('warehouse_write')) $template .= ' {update}';
if ($user->can('admin')) $template .= ' {delete}';

?>

<div class="documents-index">

  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'panel' => [
          'type' => isset($readOnly) ? GridView::TYPE_DEFAULT : GridView::TYPE_PRIMARY
        ],
    'toolbar' => ['content' => $btnDocumentCreate],
    'columns' => [
      //['class' => 'yii\grid\SerialColumn'],
      [
        'attribute' => 'document_id',
        'options' => ['width' => '1%'],
      ],
      [
        'label' => Module::t('app', 'Document type'),
        'attribute' => 'type_id',
        'value' => function ($model) {
            switch ($model->type_id) {
                case 1: return Module::t('app', 'Supply');
                case 2: return Module::t('app', 'Transfer');
                case 3: return Module::t('app', 'Write-off');
                return '';
            }
        },
        'filter' => Html::activeDropDownList($searchModel, 'type_id', [1 => 'Поставка', 2 => 'Перемещение', 3 => 'Списание'],
          ['class'=>'form-control','prompt' => Module::t('app', 'All')]),
        //'filter' => Html::activeDropDownList($searchModel, 'Type', ArrayHelper::map(Types::find()->asArray()->all(), 'ID', 'Type'),['class'=>'form-control','prompt' => Yii::t('app', 'All')]),
      ],
      'document',
      [
         'attribute' => 'date',
         'format' =>  'date',
         'options' => ['width' => '100']
      ],
      [
        'label' => Module::t('app', 'From'),
        'attribute' => 'from',
        'value' => 'from_warehouse.warehouse'
      ],
      [
        'label' => Module::t('app', 'To'),
        'attribute' => 'to',
        'value' => 'to_warehouse.warehouse'
      ],
      'comment:ntext',
      [
        'label' => Module::t('app', 'Company'),
        'attribute' => 'company_id',
        'value' => 'company.company',
        'filter' => Html::activeDropDownList($searchModel, 'company_id', ArrayHelper::map(Companies::find()->asArray()->all(), 'company_id', 'company'),['class'=>'form-control','prompt' => Module::t('app', 'All')]),
      ],
      [
        'class' => 'yii\grid\ActionColumn',
        'buttons'=>[
          'list'=>function ($url, $model) {
            // $customurl=Yii::$app->getUrlManager()->createUrl(['log/view','id'=>$model['id']]); //$model->id для AR
            return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-search"></span>', ['contents/index', 'id' => $model->document_id],
              ['title' => Module::t('app', 'List'), 'data-pjax' => '0']);
          },
        ],
        'template' => $template,
      ],
    ],
    ]); ?>

  </div>
