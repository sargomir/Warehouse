<?php
namespace app\modules\warehouse;

use kartik\grid\GridView as KartikGridView;
use yii\helpers\Html;
use kartik\grid\CheckboxColumn;
use yii\grid\ActionColumn;

/*
 * This is our custom GridView
 * We will enable top-panel
 * Enable export options
 * Add checkbox column
 * And actions with many rows
 *
 * Selected rows indexes are stored in session
 * We need to set it right in controller
 */
class GridView extends KartikGridView
{
    public static $my_counter = 0;
    
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->exportConfig = [
            GridView::CSV => ['label' => 'CSV'],
            GridView::HTML => ['label' => 'HTML'],
            GridView::EXCEL => ['label' => 'EXCEL'],
        ];
        $this->export = [
            'showConfirmAlert'=>false,
            'target'=>GridView::TARGET_BLANK
        ];
        
        $this->condensed = true;
        
        $this->pjax = true;
        // We don't want to change gridview #id if it is already set in view.php
        if (! $this->pjaxSettings)
        $this->pjaxSettings = [
            'neverTimeout'=>true,
            'options' => [
                'id'=>'kv-unique-id-1' . GridView::$my_counter++,
            ]
        ];
        
        $this->pager = [
            'firstPageLabel' => '««',
            'lastPageLabel' => '»»',
        ];      
        
        //$this->toolbar = array_merge($this->toolbar, [
        //    //[
        //    //    'content' => Html::a(\Yii::t('app', 'Create'), ['create'], [
        //    //        'class' => 'btn btn-success btn-create glyphicon glyphicon-plus',
        //    //        'style' => 'top: 0px;'
        //    //    ])  
        //    //],
        //    [
        //        'content' => Html::button(\Yii::t('app', 'Filter'), [
        //            'id' => 'btn-filter',
        //            'class' => isset(\Yii::$app->session[\Yii::$app->controller->id . \Yii::$app->controller->action->id]) ? 'btn btn-default glyphicon postkeylist active glyphicon-check' : 'btn btn-default glyphicon postkeylist glyphicon-unchecked', //'$filter_btn_class',
        //            'style' => 'top: 0px;', //glyphicon has 1 px
        //            'onclick' => '
        //                var keys = $("#w0").yiiGridView("getSelectedRows");
        //                if (keys.length) {
        //                    $.ajax({
        //                        url: window.location,
        //                        type: "POST",
        //                        timeout: 5000,
        //                        data: {keylist: keys},
        //                        success: function(data) {
        //                            $.pjax.reload({container : "#kv-unique-id-1"});
        //                        },
        //                        error: function(data) {
        //                            console.log("Error!", data);
        //                        }
        //                    });
        //                } else {
        //                    if ( $( "#btn-filter" ).is(".active") ) 
        //                    $.ajax({
        //                        url: window.location,
        //                        type: "POST",
        //                        data: {keylist: "reset"},
        //                        success: function(data) {
        //                            $.pjax.reload({container : "#kv-unique-id-1"});
        //                        },
        //                        error: function(data) {
        //                            console.log("Error!", data);
        //                        }
        //                    });
        //                }
        //            '
        //        ])
        //    ],
            //[
            //    'content' => Html::a(\Yii::t('app', 'Copy'),
            //        [
            //            'contents/copy',
            //            //'controller' => \Yii::$app->controller->id,
            //            //'action' => \Yii::$app->controller->action->id,
            //            'id' => 43,
            //        ],
            //        [
            //            'data' => [
            //                'method' => 'post',
            //                'confirm' => 'Are you sure?',
            //                'params' => [
            //                    'search' => $this->filterModel->find()->asArray()->all(),
            //                ]
            //            ],
            //            'id' => 'btn-copy',
            //            'class' => 'btn btn-default glyphicon glyphicon-right', 
            //            'style' => 'top: 0px;', 
            //        ]
            //    )
            //],
        //]);
        if (!in_array ('{export}', $this->toolbar)) $this->toolbar[] = '{export}';
        if (!in_array ('{toggleData}', $this->toolbar)) $this->toolbar[] = '{toggleData}';

        
		if ( ! count($this->panel) )
			$this->panel = [ 
					'heading' => "" 
			];

        //$this->columns[] = new CheckboxColumn([
        //    'grid' => $this,
        //    'headerOptions' => ['class' => 'kartik-sheet-style'],
        //    'rowSelectedClass' => GridView::TYPE_SUCCESS,
        //    'pageSummary' => true,
        //]);
        
        //$this->columns[] = new ActionColumn([
        //    'grid' => $this,
        //    'options' => [
        //        'class' => 'action-column skip-export',
        //    ],
        //    'buttons' => [
        //        'list'=>function ($url, $model) {
        //          return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-search"></span>', ['contents/index', 'id' => $model->ID],
        //                ['title' => \Yii::t('yii', 'List'), 'data-pjax' => '0']);
        //        },
        //        'track'=>function ($url, $model) {
        //            return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-search"></span>', ['products/track', 'id' => $model->ID],
        //                ['title' => \Yii::t('yii', 'Track'), 'data-pjax' => '0']);
        //        },
        //    ],
        //    'template'=>'{view} {list} {track} {update} {delete}',            
        //]);
        
    }
}
