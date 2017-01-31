<?php

namespace app\modules\warehouse\models;

use Yii;
use yii\db\Query;

use app\modules\warehouse\Warehouse as Module;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property integer $product_id
 * @property string $product
 * @property integer $manufacturer_id
 * @property string $article
 * @property string $description
 * @property integer $dimension_id
 *
 * @property Contents[] $contents
 * @property Dimensions $dimension
 * @property Manufacturers $manufacturer
 */
class Products extends MyActiveRecord
{
    /**
     * When isset we redirect to content/create
     * for this document_id
     * and select this product_id
     */
    public $document_id;
    
    //public static function primaryKey(){
    //    return ['product_id'];
    //    $pk = parent::primaryKey();
    //    $tn = tableName();
    //    return ["{$tn}.{$pk[0]}"];
    //}    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manufacturer_id'], 'required'],
            [['manufacturer_id', 'dimension_id'], 'integer'],
            [['product'], 'string', 'max' => 100],
            [['article'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 1000],
            [['dimension_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dimensions::className(), 'targetAttribute' => ['dimension_id' => 'dimension_id']],
            [['manufacturer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturers::className(), 'targetAttribute' => ['manufacturer_id' => 'manufacturer_id']],
            [['document_id'], 'number']
        ];
    }    
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Module::t('app', 'Product Id'),
            'product' => Module::t('app', 'Product'),
            'manufacturer_id' => Module::t('app', 'Manufacturer Id'),
            'article' => Module::t('app', 'Article'),
            'description' => Module::t('app', 'Description'),
            'dimension_id' => Module::t('app', 'Dimension'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContents()
    {
        return $this->hasMany(Contents::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturer()
    {
        return $this->hasOne(Manufacturers::className(), ['manufacturer_id' => 'manufacturer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimension()
    {
        return $this->hasOne(Dimensions::className(), ['dimension_id' => 'dimension_id']);
    }

    /**
     * Returns calculated availability
     */
    public function getAvailability()
    {
        //$query = new Query;
        //$query->select('product_id, warehouse_id, availability')
        //    ->from('get_products_availability')
        //    ->where("product = $this->product_id");
        //$availability = $query->all();
        //return $availability;
        return $this->hasOne(Availability::className(), ['product_id' => 'product_id']);
    }

    /**
     * For dropDownList in contents/create
     */
    public function getProductInfo()
    {
        if(isset($this->product) && !empty($this->product)) $productInfo[] = $this->product;
        if(isset($this->manufacturer->manufacturer) && !empty($this->manufacturer->manufacturer)) $productInfo[] = $this->manufacturer->manufacturer;
        if(isset($this->article) && !empty($this->article)) $productInfo[] = $this->article;
        return implode(', ', $productInfo);
    }

    /**
     * @inheritdoc
     * @return ProductsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MyActiveQuery(get_called_class());
    }
}
