<?php

namespace app\modules\warehouse\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\warehouse\models\Availability;
use app\modules\warehouse\models\Products;
use yii\db\Query;

/**
 * ContentsSearch represents the model behind the search form about `app\models\Contents`.
 */
class AvailabilitySearch extends Availability
{
    public $product;
    public $article;
    public $manufacturer;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['availability'], 'number'],
            [['product', 'article', 'manufacturer'], 'safe'],
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
        $query = Availability::find();
        $query->joinWith(['product']);
        $query->joinWith(['product.manufacturer']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'attributes'=>[
                'product' => [
                    'asc' => ['products.product' => SORT_ASC],
                    'desc' => ['products.product' => SORT_DESC],
                ],
                'manufacturer' => [
                    'asc' => ['manufacturers.manufacturer' => SORT_ASC],
                    'desc' => ['manufacturers.manufacturer' => SORT_DESC],
                ],
                'article',
                'availability'
            ],
            'defaultOrder' => ['product' => SORT_ASC],
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
             //uncomment the following line if you do not want to return any records when validation fails
             //$query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'get_products_availability.product_id' => $this->product_id,
            'get_products_availability.warehouse_id' => $this->warehouse_id,
            'get_products_availability.availability' => $this->availability,
        ])->andFilterWhere(['like', 'products.product', $this->product])
        ->andFilterWhere(['like', 'products.article', $this->article])
        ->andFilterWhere(['like', 'manufacturers.manufacturer', $this->manufacturer]);

        return $dataProvider;
    }
}
