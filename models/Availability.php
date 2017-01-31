<?php

namespace app\modules\warehouse\models;

use Yii;

use app\modules\warehouse\Warehouse as Module;

/**
 * This is the model class for table "GetProductAvailability".
 *
 * @property integer $product_id
 * @property integer $warehouse_id
 * @property double $availability
 */
class Availability extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%get_products_availability}}';
    }

    /**
     * Primary key is required for selecting in kartik/gridview
     */    
    public static function primaryKey(){
        return ['product_id', 'warehouse_id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'warehouse_id'], 'integer'],
            [['availability'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Module::t('app', 'Product Id'),
            'warehouse_id' => Module::t('app', 'Warehouse Id'),
            'availability' => Module::t('app', 'Availability'),
        ];
    }

    /**
     * Relation to Products table
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['product_id' => 'product_id']);
    }

    /**
     * Relation to Warehouses table
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouses::className(), ['warehouse_id' => 'warehouse_id']);
    }
    
    /**
     * @inheritdoc
     * @return AvailabilityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MyActiveQuery(get_called_class());
    }
    
	/**
	 * Return displayable amount and measure
	 */
    public function getAvailability_info()
    {
		$formatter = new \NumberFormatter("ru-RU", \NumberFormatter::DECIMAL);
		$formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 0);
		$formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 5);
        if (isset ($this->product->dimension->dimension))
			return $formatter->format($this->availability) . ' ' . $this->product->dimension->dimension;
		return $formatter->format($this->availability);
    }    
    
    /**
     * Check products availability
     * @return bool
     */
    public static function getIntegrityErrors()
    {
        $query = Availability::find()
            ->select(['IntegrityFails'=>'count(*)'])
            ->where('Availability < 0.00001')
            ->asArray()
            ->one();
        if (isset ($query['IntegrityFails'])) return $query['IntegrityFails'];
        return false;
    }
}
