<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\modules\warehouse\Warehouse as Module;

/* @var $this yii\web\View */
/* @var $model app\models\Contents */

$this->title = Module::t('app', 'Document content');
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Documents'), 'url' => ['documents/index']];
$this->params['breadcrumbs'][] = ['label' => isset($model->document->document_id) ? $model->document->document_id : "", 'url' => ['documents/view', 'id' => $model->document_id]];
$this->params['breadcrumbs'][] = ['label' => Module::t('app', 'Document content'), 'url' => ['contents/index', 'id' => $model->document_id]];
$this->params['breadcrumbs'][] = ['label' => isset($model->content_id) ? $model->content_id : $model->content_id];
?>

<div class="contents-view">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
