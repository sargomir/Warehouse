<?php

namespace app\modules\warehouse\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\warehouse\models\Warehouses;

/**
 * WarehousesSearch represents the model behind the search form about `app\models\Warehouses`.
 */
class WarehousesSearch extends Warehouses
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['warehouse_id'], 'integer'],
            [['warehouse', 'description'], 'string'],
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
        $query = Warehouses::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'warehouse_id' => $this->warehouse_id,
        ]);

        $query->andFilterWhere(['like', 'warehouse', $this->warehouse])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
