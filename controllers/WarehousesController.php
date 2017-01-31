<?php

namespace app\modules\warehouse\controllers;

use Yii;

use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

use app\modules\warehouse\models\MyActiveRecord;
use app\modules\warehouse\models\Warehouses;
use app\modules\warehouse\models\WarehousesSearch;
use app\modules\warehouse\models\Availability;
use app\modules\warehouse\models\AvailabilitySearch;

/**
 * WarehousesController implements the CRUD actions for Warehouses model.
 */
class WarehousesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['warehouse_read'],
                    ],
					[
                        'actions' => ['create', 'update'],
                        'allow' => true,
                        'roles' => ['warehouse_write'],
					],
					[
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['admin'],
					]	
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Warehouses models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WarehousesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Warehouses model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	$model = $this->findModel($id);
    	 
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		return $this->redirect(['view', 'id' => $model->warehouse_id]);
    	} else {
    		$searchModel = new AvailabilitySearch();
    		$searchModel->warehouse_id = $id;
    		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);    		
    		return $this->render('view', [
    				'model' => $model,
    				'searchModel' => $searchModel,
    				'dataProvider' => $dataProvider,    				
    		]);
    	};
    }
        
    /**
     * Creates a new Warehouses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Warehouses();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->warehouse_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Warehouses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->warehouse_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Warehouses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$deleted = $this->findModel($id)->delete();
    
    	if (Yii::$app->request->isAjax) {
    		if ($deleted)
	    		echo Json::encode([
	    			'success' => true,
	    			'messages' => ['kv-detail-info' => 'Deleted'], 	
	    		]);
    		else 
    			echo Json::encode([
    				'success' => false,
    				'messages' => ['kv-detail-error' => 'Cannot'],
    			]);
    		return;
    	}
    	
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Warehouses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Warehouses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Warehouses::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
