<?php

namespace app\modules\warehouse\controllers;

use Yii;
use app\modules\warehouse\models\Contents;
use app\modules\warehouse\models\ContentsSearch;
use app\modules\warehouse\models\Availability;
use app\modules\warehouse\models\AvailabilitySearch;
use app\modules\warehouse\models\Documents;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
/**
 * ContentsController implements the CRUD actions for Contents model.
 */
class ContentsController extends Controller
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
                        'actions' => ['create', 'update', 'delete', 'deletemany', 'copy'],
                        'allow' => true,
                        'roles' => ['warehouse_write'],
					],
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
     * Lists all Contents models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $searchModel = new ContentsSearch();
        $searchModel->document_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->getUser()->setReturnUrl(Yii::$app->request->getUrl());

        //4 ajax editable column
        if (Yii::$app->request->post('hasEditable') && Yii::$app->user->can('warehouse_write')) {
            $cid = Yii::$app->request->post('editableKey');
            $model = Contents::findOne($cid);
            $out = \yii\helpers\Json::encode(['output'=>'', 'message'=>'']);

            if ($model->load( ['Contents' => current(Yii::$app->request->post('Contents'))] )) {
                //$measure = isset($model->product->measure->Measure) ? $model->product->measure->Measure : '';
                $output = $model->amount_info;
                //$output = isset($posted['Amount']) ? Yii::$app->formatter->asDecimal($model->Amount, 2) : '';
                if ($model->save())
                    $message = '';
                else
                    $message = 'Ошибка целостности данных!';
                $out = \yii\helpers\Json::encode(['output'=>$output, 'message'=>$message]);
            }
            echo $out;
            return;
        }
                
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'documentModel' => Documents::findOne($id),
        ]);
    }

    /**
     * Displays a single Contents model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Contents model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $id - Document ID
     * @return mixed
     */
    public function actionCreate($id)
    {   
        $model = new Contents();
        $model->document_id = $id;

        $model->product_id = Yii::$app->request->get('pid');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Model saved
            return $this->redirect(['contents/index', 'id' => $model->document_id]);
        } else {
            // Model edit
            return $this->render('create', [
                'model' => $model,
        		'did' => $id,
            ]);
        }
    }

    /**
     * Updates an existing Contents model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->ID]);
            return $this->goBack();
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Contents model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$model = $this->findModel($id);

        $searchModel = new ContentsSearch();
        $searchModel->document_id = $model->document_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model->delete();
        
		return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'documentModel' => Documents::findOne($searchModel->document),
        ]);
    }
    
    /**
     * Safe delete many contents.ID in $items
     */
    public function DeleteContents($cids)
    {
        if (count ($cids)) {
            // Begin transaction for multiple updates
            $transaction = Yii::$app->getModule('warehouse')->db->beginTransaction();
            try {
                $result = true; //Transaction status
                foreach ($cids as $cid) {
                    $result = $result && $this->findModel($cid)->delete();
                }
            
                if ($result && Availability::getIntegrityErrors() < 1) {
                    // All OK. Commit transaction
                    $transaction->commit();
                    $this->goBack();
                    return true;
                } else {
                    $transaction->rollback();
                    return "Невозможно удалить выбранные ". count($cids). " записи: [" . implode(', ', $cids) ."]. Ошибка целостности данных.";
                }
            } catch (Exception $e) {
                // Transaction failed
                Yii::trace($e, 'app-exception');
                $transaction->rollback();
            }
        }
    }    
    
    /**
     * Delete many content records from document.ID == $did
     */
    public function actionDeletemany($did)
    {
        $selection = Yii::$app->request->post('selection');
        $result = isset($selection) ? $this->DeleteContents($selection) : null;
        
        $searchContent = new ContentsSearch();
        $searchContent->document_id = $did;
        $contentProvider = $searchContent->search(Yii::$app->request->queryParams);
  
        return $this->render('deletemany', [
            'searchContent' => $searchContent,
            'contentProvider' => $contentProvider,
            'did' => $did,
            'result' => $result
        ]);
    }    
    
    /**
     * Create multiple items in document $id contents
     * recieving $keylist via POST-request
     */
    public function actionCopy($id)
    {
        $keys = \Yii::$app->request->post('keylist');
        if ($keys) {
            $availability = Availability::find();
            
            // Fiter selected items
            foreach ($keys as $key)
                $availability->orFilterWhere($key);
                
            // Begin transaction for multiple updates
            $transaction = \Yii::$app->getModule('warehouse')->db->beginTransaction();
            try {
                $result = true; //Transaction status
                foreach ($availability->asArray()->all() as $item) {
                    // For each selected item we create content in document $id
                    $content = new Contents();
                    $content->Document = $id;
                    $content->Product = $item['ProductID'];
                    $content->Amount = $item['Availability'];
                    $result = $result && $content->save(); // If we fail once rollback all
                }
            
                if ($result) {
                    // All OK. Commit transaction
                    $transaction->commit();
                    return $this->goBack();
                }
            } catch (Exception $e) {
                // Transaction failed
                Yii::trace($e, 'app-exception');
                $transaction->rollback();
            }
        }
    }

    /**
     * Finds the Contents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contents::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
