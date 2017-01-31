<?php

namespace app\modules\warehouse\models;

use Yii;
use yii\behaviors\TimestampBehavior;

use app\modules\warehouse\Warehouse as Module;

/**
 * This is the model class for table "{{%documents}}".
 *
 * @property integer $document_id
 * @property string $document
 * @property integer $company_id
 * @property integer $type_id
 * @property string $date
 * @property integer $from
 * @property integer $to
 * @property string $comment
 *
 * @property Contents[] $contents
 * @property Companies $company
 * @property Warehouses $to0
 * @property Warehouses $from0
 */
class Documents extends MyActiveRecord
{
    
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    MyActiveRecord::EVENT_BEFORE_INSERT => 'date',
                    MyActiveRecord::EVENT_BEFORE_UPDATE => 'date',
                ],
                'value' => function() { return \Yii::$app->formatter->asDatetime($this->date, 'php:Y-m-d'); },
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%documents}}';
    }

    /**
     * @inheritdoc
     * php 7.0 syntax required!
     */
    public function rules()
    {
        return [
            [['document', 'company_id', 'type_id', 'date'], 'required'],
            [['company_id', 'type_id', 'from', 'to'], 'integer'],
            [['date'], 'date'],
            [['comment'], 'string'],
            [['document'], 'string', 'max' => 20],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'company_id']],
            [['to'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouses::className(), 'targetAttribute' => ['to' => 'warehouse_id']],
            [['from'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouses::className(), 'targetAttribute' => ['from' => 'warehouse_id']],

            /**
             * Проверяем установку необходимых полей в зависимости от типа документа
             */
            [['to'], 'required', 'enableClientValidation' => false, 'when' => function($model, $attribute) {
                return in_array($model->type_id, [1, 2]);
            }],
            [['from'], 'required', 'enableClientValidation' => false, 'when' => function($model, $attribute) {
                return in_array($model->type_id, [2, 3]);
            }],

            /**
             * Проверяем очистку ненужных полей в зависимости от типа документа
             */
            [
                ['to'], 'compare', 'compareValue' => 0, 'operator' => '==',
                'message' => Module::t('app', 'Depending on Document type this field must be empty'),
                'enableClientValidation' => false,
                'when' => function($model, $attribute) {
                   return $model->type_id == 3;
                }
            ],
            [
                ['from'], 'compare', 'compareValue' => 0, 'operator' => '==',
                'message' => Module::t('app', 'Depending on Document type this field must be empty'),
                'enableClientValidation' => false,
                'when' => function($model, $attribute) {
                   return $model->type_id == 1;
                }
            ],

            /**
             * Состав документа должен быть очищен перед изменением типа документа
             * Это правило не работает при создании нового документа
             */
            [
                ['type_id'], 'compare', 'compareValue' => $this->oldAttributes['type_id'] ?? -1, 'operator' => '==',
                'message' => Module::t('app', 'Document contents must be empty to change this field'),
                'enableClientValidation' => false,
                'when' => function($model, $attribute) { return count($model->contents); }
            ],
            [
                ['to'], 'compare', 'compareValue' => $this->oldAttributes['to'] ?? -1, 'operator' => '==',
                'message' => Module::t('app', 'Document contents must be empty to change this field'),
                'enableClientValidation' => false,
                'when' => function($model, $attribute) { return count($model->contents); }
            ],
            [
                ['from'], 'compare', 'compareValue' => $this->oldAttributes['from'] ?? -1, 'operator' => '==',
                'message' => Module::t('app', 'Document contents must be empty to change this field'),
                'enableClientValidation' => false,
                'when' => function($model, $attribute) { return count($model->contents); }
            ],            
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document_id' => Module::t('app', 'Document ID'),
            'document' => Module::t('app', 'Document'),
            'company_id' => Module::t('app', 'Company ID'),
            'type_id' => Module::t('app', 'Type ID'),
            'date' => Module::t('app', 'Date'),
            'from' => Module::t('app', 'From Warehouse ID'),
            'to' => Module::t('app', 'To Warehouse ID'),
            'comment' => Module::t('app', 'Comment'),    
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContents()
    {
        return $this->hasMany(Contents::className(), ['document_id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['company_id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrom_warehouse()
    {
        return $this->hasOne(Warehouses::className(), ['warehouse_id' => 'from'])->from(Warehouses::tableName() . ' from_warehouse');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTo_warehouse()
    {
        return $this->hasOne(Warehouses::className(), ['warehouse_id' => 'to'])->from(Warehouses::tableName() . ' to_warehouse');
    }
    
    public function getDocument_type()
    {
        switch ($this->type_id) {
            case 1 : return Module::t('app', 'Supply');
            case 2 : return Module::t('app', 'Transfer');
            case 3 : return Module::t('app', 'Write-off');
        }
        return null;
    }

    /**
     * @inheritdoc
     * @return DocumentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MyActiveQuery(get_called_class());
    }
}
