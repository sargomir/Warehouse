<?php

use app\modules\warehouse\Warehouse as Module;

/* @var $this yii\web\View */
/* @var $model app\models\Documents */

$this->title = Module::t('app', 'Documents');
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Documents'), 'url' => ['documents/index']];
$this->params['breadcrumbs'][] = ['label' => $model->document_id];
?>

<div class="documents-update">
    <?= $this->render('_form', [
        'model' => $model,
    	'create' => true,
    ]) ?>
</div>

<div class="contents-index">
	<?php 
		$contentsSearch = new app\modules\warehouse\models\ContentsSearch();
        $contentsSearch->document_id = $model->document_id;
        $contentsProvider = $contentsSearch->search(Yii::$app->request->queryParams);
    ?>
    <?php 
    	echo $this->render('/contents/index', [
        	'searchModel' => $contentsSearch,
    		'dataProvider' => $contentsProvider,
			'readOnly' => true,
			//'documentModel' => $model,
    	]) 
    ?>    
</div>
