<?php

namespace app\modules\warehouse\controllers;

use Yii;

use app\modules\warehouse\Warehouse as Module;

use app\modules\warehouse\models\Products;
use app\modules\warehouse\models\ProductsSearch;
use app\modules\warehouse\models\AvailabilitySearch;
use app\modules\warehouse\models\ContentsSearch;

use app\modules\warehouse\models\Contents;
use app\modules\warehouse\models\Documents;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'track'],
                        'allow' => true,
                        'roles' => ['warehouse_read'],
                    ],
					[
                        'actions' => ['create', 'update', 'select', 'selectmany'],
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
                //'actions' => [
                //    'delete' => ['post'],
                //],
            ],
        ];
    }

    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        // Save return url
        Yii::$app->getUser()->setReturnUrl(Yii::$app->request->getUrl());
        
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model with all linked Warehouses
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		return $this->redirect(['view', 'id' => $model->product_id]);
    	} else {
			$searchModel = new AvailabilitySearch();
			$searchModel->product_id = $id;
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			
			return $this->render('view', [
				'model' => $model,
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}
    }

     /**
     * Displays a single Products model with all linked Documents
     * @param integer $id
     * @return mixed
     */   
    public function actionTrack($id)
    {
        $searchModel = new ContentsSearch();
        $searchModel->product_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		// Change default sort order
		$dataProvider->getSort()->defaultOrder = ['_date' => SORT_DESC];		
		
        return $this->render('track', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Products();
        $model->document_id = Yii::$app->request->get('did');
        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            // Model saved
            if (isset ($model->document_id))
                return $this->redirect(['contents/create', 'id' => $model->document_id, 'pid' => $model->product_id]);
            else
                return $this->redirect(['products/view', 'id' => $model->product_id]);
        } else {
            // Edit model
            return $this->render('/products/create', ['model' => $model]);
        }
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
            //if (Yii::$app->request->post('did') != null)
            //    return $this->redirect(['contents/create',
            //        'id' => Yii::$app->request->post('did'),
            //        'pid' => $model->product_id,
            //    ]);
            //else 
                return $this->redirect(['view', 'id' => $model->product_id]);
        else
            if (Yii::$app->request->isAjax)
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
            else
            return $this->render('update', [
                'model' => $model,
            ]);
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $product = $this->findModel($id);
        try {
            $product->delete();
            return $this->goBack();
        } catch (yii\db\Exception $e) {
            if ($e->errorInfo[0] == 23000)
            {
                // Relation Restriction
                $documents = Documents::find()
                    ->joinWith('contents')
                    ->select('*')
                    ->where(['Product'=>$product->ID])
                    //->groupBy('Documents.ID')
                    ->asArray()->all();
                $rows = [];
                foreach ($documents as $document)
                    $rows[] = '№' . $document['Document'] . ' "Поставка" от ' . date('d.m.Y');
                //var_dump($rows);exit;
                throw new \yii\web\HttpException(400, "Невозможно удалить Товар \"{$product->productInfo}\", т.к. на него ссылаются документы: \n" . implode("\n", $rows));
            } else {
                throw new \yii\web\HttpException(400, implode(' ', $e->errorInfo)); 
            }
        }
    }

    /**
     * Show form to select or create new product
     * and add it to contents of document $id
     */
    public function actionSelect($id)
    {
      $searchModel = new ProductsSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

      return $this->render('select', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
          'document_id' => $id,
      ]);
    }
    
    
    public function CreateContents($did, $wid, $items)
    {
        if ($did && $wid && count($items) ) {
            // Begin transaction for multiple updates
            $transaction = Yii::$app->getModule('warehouse')->db->beginTransaction();
            try {
                $result = true; //Transaction status
                foreach ($items as $item) {
                    // For each selected [product, amount] we create content in Document_ID = $did
                    $content = new Contents();
                    $content->document_id = $did;
                    $content->product_id = $item['product'];
                    $content->amount = $item['availability'];

                    $result = $result && $content->save(); // If we fail once rollback all
                }
            
                if ($result) {
                    // All OK. Commit transaction
                    $transaction->commit();
                    return 1; //$this->goBack();
                }
            } catch (Exception $e) {
                // Transaction failed
                Yii::trace($e, 'app-exception');
                $transaction->rollback();
				return 0;
            }
        }
		return 0;
    }
    
    /**
     * Show form to select multiple products from $wh
     * and add them to contents of document $id
     */
    public function actionSelectmany($id, $wh)
    {
					//				var_dump(Yii::$app->request->post('selection'));
					//exit;
        if ($selection = Yii::$app->request->post('selection')) {
            $items = [];
            foreach ($selection as $selected) {
                $selected = json_decode($selected);
                $availability_pkey = "{$selected->product_id}_{$selected->warehouse_id}";
                $items[] = Yii::$app->request->post('Availability')[$availability_pkey];
            }
            if ($this->CreateContents($id, $wh, $items))
				$this->goBack();
			else
				throw new \yii\web\HttpException(403, Module::t('app', 'Data integrity check failure.'));
        }
        
        $searchModel = new AvailabilitySearch();
        $searchModel->warehouse_id = $wh;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
  
        return $this->render('selectmany', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'document_id' => $id,
        ]);
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
