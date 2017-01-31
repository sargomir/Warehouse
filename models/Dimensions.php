<?php

namespace app\modules\warehouse\models;

use Yii;

use app\modules\warehouse\Warehouse as Module;

/**
 * This is the model class for table "{{%dimensions}}".
 *
 * @property integer $dimension_id
 * @property string $dimension
 * @property string $description
 *
 * @property Products[] $products
 */
class Dimensions extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dimensions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dimension'], 'required'],
            [['dimension'], 'string', 'max' => 10],
            [['description'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dimension_id' => Module::t('app', 'Код размерности'),
            'dimension' => Module::t('app', 'Размерность'),
            'description' => Module::t('app', 'Описание'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['dimension_id' => 'dimension_id']);
    }
}