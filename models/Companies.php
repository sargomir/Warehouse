<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "{{%companies}}".
 *
 * @property integer $company_id
 * @property string $company
 *
 * @property Documents[] $documents
 */
class Companies extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%companies}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company'], 'required'],
            [['company'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'company_id' => Yii::t('app', 'Код'),
            'company' => Yii::t('app', 'Компания'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Documents::className(), ['company_id' => 'company_id']);
    }

    /**
     * @inheritdoc
     * @return CompaniesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MyActiveQuery(get_called_class());
    }
}
