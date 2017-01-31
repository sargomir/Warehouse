<?php

namespace app\modules\warehouse\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\warehouse\models\Products;

/**
 * ProductsSearch represents the model behind the search form about `app\models\Products`
 */
class ProductsSearch extends Products
{
    public $manufacturer;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'dimension_id', 'manufacturer_id'], 'integer'],
            [['product', 'article', 'description', 'manufacturer'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Products::find();
        $query->joinWith(['manufacturer'])
			->joinWith(['availability']);
        //$controller = \Yii::$app->controller->id;
        //$action = \Yii::$app->controller->action->id;
        //$tn = ucfirst($controller);
        //if (isset (\Yii::$app->session["$controller$action"]))
        //     foreach (\Yii::$app->session["$controller$action"] as $key)
        //     {
        //         if (is_array($key))
        //             $query->orFilterWhere($key);
        //         else
        //             $query->orFilterWhere([$this->tableName() . '.' . $this->primaryKey()[0] => $key]);
        //     }            

             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->setSort([
        	'attributes' => [
     			'product_id',
        		'product',
        		'article',
        		'description',
        		'manufacturer',
				'availability',
        	],
        	'defaultOrder' => [
        		'manufacturer' => SORT_ASC,
       			'product' => SORT_ASC,
       		],
        ]);        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //$query->andFilterWhere([
        //    'product_id' => $this->product_id,
        //    "{$this->tableName()}.manufacturer" => $this->manufacturer,
        //    'dimension_id' => $this->dimension_id,
        //]);

        $query->andFilterWhere(['product_id' => $this->product_id])
			->andFilterWhere(['products.manufacturer_id' => $this->manufacturer_id])
			->andFilterWhere(['like', 'product', $this->product])
            ->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'manufacturers.manufacturer', $this->manufacturer]);
            
        return $dataProvider;
    }
}