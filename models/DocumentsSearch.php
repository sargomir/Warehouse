<?php

namespace app\modules\warehouse\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\warehouse\models\Documents;

/**
 * DocumentsSearch represents the model behind the search form about `app\models\Documents`.
 */
class DocumentsSearch extends Documents
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_id', 'company_id', 'type_id'], 'integer'],
            [['date'], 'string'],
            [['document'], 'string', 'max' => 20],
            [['comment'], 'string'],
            //[['to'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouses::className(), 'targetAttribute' => ['to' => 'warehouse_id']],
            //[['from'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouses::className(), 'targetAttribute' => ['from' => 'warehouse_id']],                        
            [['from', 'to'], 'safe'],
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
        $query = Documents::find();
        $query->joinWith(['from_warehouse', 'to_warehouse']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->setSort([
           'attributes' => [
                'document_id',
                'type_id',
                'document',
                'date',
                'from' => [
                    'asc' => ['from_warehouse.warehouse' => SORT_ASC],
                    'desc' => ['from_warehouse.warehouse' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'to' => [
                    'asc' => ['to_warehouse.warehouse' => SORT_ASC],
                    'desc' => ['to_warehouse.warehouse' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'comment',
                'company_id'
            ],
           'defaultOrder' => [
                'date' => SORT_DESC
           ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'document_id' => $this->document_id,
            'company_id' => $this->company_id,
            'type_id' => $this->type_id,
            // 'Date' => $this->Date,
            // 'From' => $this->From,
            // 'To' => $this->To,
        ])
        ->andFilterWhere(['like', 'document', $this->document])
        ->andFilterWhere(['like', 'from_warehouse.warehouse', $this->from])
        ->andFilterWhere(['like', 'to_warehouse.warehouse', $this->to])
        ->andFilterWhere(['like', 'date', $this->date]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
