<?php

namespace app\modules\warehouse\models;

use Yii;

use app\modules\warehouse\Warehouse as Module;

/**
 * This is the model class for table "{{%contents}}".
 *
 * @property integer $content_id
 * @property integer $document_id
 * @property integer $product_id
 * @property string $amount
 * @property double $price
 *
 * @property Documents $document
 * @property Products $product
 */
class Contents extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contents}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_id', 'product_id', 'amount'], 'required'],
            [['document_id', 'product_id'], 'integer'],
			[['amount', 'price'], 'number'],
			[['amount', 'price'], 'default', 'value' => null], // we don't want zero af default value
            [['amount', 'price'], 'compare', 'compareValue' => 0, 'operator' => '>', 'message' => Module::t('app', 'Required greater than zero')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'content_id' => Module::t('app', 'Content Id'),
			'document_id' => Module::t('app', 'Document Id'),
			'product_id' => Module::t('app', 'Product Id'),
			'amount' => Module::t('app', 'Amount'),
			'price' => Module::t('app', 'Price'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Documents::className(), ['document_id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturer()
    {
        return $this->hasOne(Manufacturers::className(), ['manufacturer_id' => 'manufacturer_id'])
            ->via('product');
    }
	
    public function Save($runValidation = true, $attributeNames = NULL)
    {
        $transaction = $this->db->beginTransaction();
        try {
            parent::Save();
            $availability = new \app\modules\warehouse\models\Availability();
            if (!$availability->integrityErrors) {
                $transaction->commit();
				return true;
			} else {
                $transaction->rollBack();
			}
        }
        catch (Exception $e) {
            $transaction->rollBack();
        }
		return false;
    }
	
	/**
	 * Return displayable amount and measure
	 */
    public function getAmount_info()
    {
		$formatter = new \NumberFormatter("ru-RU", \NumberFormatter::DECIMAL);
		$formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 0);
		$formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 5);
        if (isset ($this->product->dimension->dimension))
			return $formatter->format($this->amount) . ' ' . $this->product->dimension->dimension;
		return $formatter->format($this->amount);
    }

    /**
     * @inheritdoc
     * @return ContentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MyActiveQuery(get_called_class());
    }
}
