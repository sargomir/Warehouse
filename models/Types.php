<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "{{%Types}}".
 *
 * @property integer $ID
 * @property string $Type
 * @property boolean $From
 * @property boolean $To
 *
 * @property Documents[] $documents
 */
class Types extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%Types}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Type'], 'required'],
            [['From', 'To'], 'boolean'],
            [['Type'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'Код'),
            'Type' => Yii::t('app', 'Тип документа'),
            'From' => Yii::t('app', 'Склад источник'),
            'To' => Yii::t('app', 'Склад получатель'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Documents::className(), ['Type' => 'ID']);
    }

    /**
     * @inheritdoc
     * @return TypesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MyActiveQuery(get_called_class());
    }
}
