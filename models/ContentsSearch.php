<?php

namespace app\modules\warehouse\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\warehouse\models\Contents;

/**
 * ContentsSearch represents the model behind the search form about `app\models\Contents`.
 */
class ContentsSearch extends Contents
{
    /**
     * Search fields
     */
    // Product
    public $_product;
    public $_article;
    public $_description;
    
    // Manufacturer
    public $_manufacturer;
    
    // Document
    public $_document;
    public $_type_id;
    public $_date;
    public $_from;
    public $_to;
    
    // Warehouses
    public $_from_warehouse;
    public $_to_warehouse;
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content_id', 'document_id'], 'integer'],
            [['amount', 'price'], 'number'],


            // Product
            [['_product', '_article', '_description'], 'safe'],
            [['_product_id'], 'safe'],
            
            // Manufacturer
            [['_manufacturer'], 'string'],
            
            // Document
            [['_document', '_date', '_from', '_to'], 'string'],
            [['_type_id'], 'integer'],
            
            // Warehouses
            [['_from_warehouse', '_to_warehouse'], 'string'],
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
        $query = Contents::find();
        
        // Product
        $query->joinWith(['product']);
        
        // Manufacturer
        $query->joinWith(['manufacturer']);
        
        // Document
        $query->joinWith(['document']);
        
        // From_warehouse
        $query->joinWith(['document.from_warehouse']);
        
        // To_warehouse
        $query->joinWith(['document.to_warehouse']);
        
        /**
         * Apply sort
         */
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    // Content
                    'content_id',
                    'amount',
                    'price',
                    
                    // Product
                    '_product' => [
                        'asc' => ['products.product' => SORT_ASC],
                        'desc' => ['products.product' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    '_article' => [
                        'asc' => ['products.article' => SORT_ASC],
                        'desc' => ['products.article' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    '_description' => [
                        'asc' => ['products.description' => SORT_ASC],
                        'desc' => ['products.description' => SORT_DESC],
                        'default' => SORT_ASC
                    ],                    
                    
                    // Manufacturer
                    '_manufacturer' => [
                        'asc' => ['manufacturers.manufacturer' => SORT_ASC],
                        'desc' => ['manufacturers.manufacturer' => SORT_DESC],
                        'default' => SORT_ASC
                    ],                    
                    
                    // Document
                    '_type_id' => [
                        'asc' => ['documents.type_id' => SORT_ASC],
                        'desc' => ['documents.type_id' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    '_document' => [
                        'asc' => ['documents.document' => SORT_ASC],
                        'desc' => ['documents.document' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    '_date' => [
                        'asc' => ['documents.date' => SORT_ASC],
                        'desc' => ['documents.date' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    
                    //Warehouses
                    '_from_warehouse'=> [
                        'asc' => ['from_warehouse.warehouse' => SORT_ASC],
                        'desc' => ['from_warehouse.warehouse' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    '_to_warehouse'=> [
                        'asc' => ['to_warehouse.warehouse' => SORT_ASC],
                        'desc' => ['to_warehouse.warehouse' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                ],
                'defaultOrder' => ['_product' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /**
         * Apply filter
         */
        // Content
        $query->andFilterWhere([
            'contents.content_id' => $this->content_id,
            'contents.document_id' => $this->document_id,
            'contents.product_id' => $this->product_id,
            'contents.amount' => $this->amount,
            'contents.price' => $this->price]);
        
        // Product
        $query->andFilterWhere(['like', 'products.product', $this->_product])
            ->andFilterWhere(['like', 'products.article', $this->_article])
            ->andFilterWhere(['like', 'products.description', $this->_description])
        ;

        // Manufacturer
        $query->andFilterWhere(['like', 'manufacturers.manufacturer', $this->_manufacturer]);
        
        // Document
        $query->andFilterWhere(['documents.type_id' => $this->_type_id])
            ->andFilterWhere(['like', 'documents.document', $this->_document])
            ->andFilterWhere(['like', 'documents.date', $this->_date]);
        ;
        
        // Warehouses;
        $query->andFilterWhere(['like', 'from_warehouse.warehouse', $this->_from_warehouse])
            ->andFilterWhere(['like', 'to_warehouse.warehouse', $this->_to_warehouse])
        ;

        return $dataProvider;
    }

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['article']);
    }

}
