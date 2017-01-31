<?php

namespace app\modules\warehouse\models;

use app\modules\warehouse\Warehouse as Module;
use Yii;

/**
 * This is the model class for table "{{%manufacturers}}".
 *
 * @property integer $manufacturer_id
 * @property string $manufacturer
 *
 * @property Products[] $products
 */
class Manufacturers extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%manufacturers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manufacturer'], 'required'],
            [['manufacturer'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'manufacturer_id' => Module::t('app', 'Manufacturer Id'),
            'manufacturer' => Module::t('app', 'Manufacturer'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['manufacturer_id' => 'manufacturer_id']);
    }

    /**
     * @inheritdoc
     * @return ManufacturersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MyActiveQuery(get_called_class());
    }
}
