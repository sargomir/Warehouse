<?php

use yii\helpers\Html;

use app\modules\warehouse\Warehouse as Module;

/* @var $this yii\web\View */
/* @var $model app\models\Contents */

$this->title = Module::t('app', 'Document content');
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Documents'), 'url' => ['documents/index']];
$this->params['breadcrumbs'][] = ['label' => isset($model->document->document_id) ? $model->document->document_id : "", 'url' => ['documents/view', 'id' => $model->document_id]];
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Document content'), 'url' => ['contents/index', 'id' => $model->document_id]];
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Create')];
?>

<div class="documents-view"> 
    <?php
     echo $this->render('/documents/_form', [
         'model' => $model->document,
		 'readOnly' => true,
     ])
	?>
</div> 

<div class="contents-create">

    <?= $this->render('/contents/_form', [
        'model' => $model,
    	'create' => true,
    ]) ?>
    
</div>

<div class="contents-index"> 
    <?php
	$searchModel = new app\modules\warehouse\models\ContentsSearch();
    $searchModel->document_id = $model->document->document_id;
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
     echo $this->render('/contents/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'readOnly' => true,
     ]);
	 ?>
</div>