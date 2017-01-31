<?php

namespace app\modules\warehouse\models;

use app\modules\warehouse\Warehouse as Module;
use Yii;

/**
 * This is the model class for table "{{%warehouses}}".
 *
 * @property integer $warehouse_id
 * @property string $warehouse
 * @property string $description
 *
 * @property Documents[] $documents
 * @property Documents[] $documents0
 */
class Warehouses extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%warehouses}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['warehouse'], 'required'],
            [['warehouse', 'description'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'warehouse_id' => Module::t('app', 'Warehouse Id'),
            'warehouse' => Module::t('app', 'Warehouse'),
            'description' => Module::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentsTo()
    {
        return $this->hasMany(Documents::className(), ['to' => 'warehouse_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentsFrom()
    {
        return $this->hasMany(Documents::className(), ['from' => 'warehouse_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAvailability()
    {
        return $this->hasMany(Availability::className(), ['Warehouse' => 'ID']);
    }

    /**
     * @inheritdoc
     * @return WarehousesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MyActiveQuery(get_called_class());
    }
}
