<?php

namespace app\modules\warehouse\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\warehouse\models\Manufacturers;

/**
 * ManufacturersSearch represents the model behind the search form about `app\models\Manufacturers`.
 */
class ManufacturersSearch extends Manufacturers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manufacturer_id'], 'integer'],
            [['manufacturer'], 'safe'],
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
        $query = Manufacturers::find();

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
            'manufacturer_id' => $this->manufacturer_id,
        ]);

        $query->andFilterWhere(['like', 'manufacturer', $this->manufacturer]);

        return $dataProvider;
    }
}
