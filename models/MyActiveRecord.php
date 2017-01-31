<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * MyActiveRecord extends ActiveRecord for history output and custom database settings
 */
class MyActiveRecord extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->getModule('warehouse')->db;
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // Create history record
        if($insert) {
            // 4 insert command
            $keys = implode(', ',array_keys(array_filter($this->attributes)));
            $values = implode(', ',array_values(array_filter($this->attributes)));
            $sql = "INSERT INTO `{$this->tableName()}` ({$keys}) VALUES ({$values});";
        } else {
            // 4 update command
            $arr = [];
            foreach(($changedAttributes) as $key => $value) {
                $newvalue = $this->attributes[$key] === "" ? 'NULL' : $this->attributes[$key];
                if($this->attributes[$key] != $value)
                $arr[] = "`$key` = {$newvalue}";
            }
            $values = implode(', ',$arr);
            $sql = "UPDATE `{$this->tableName()}` SET {$values} WHERE `ID` = {$this->primaryKey};";
        }
        $this->db->createCommand()->insert('history', [
            'user' => Yii::$app->user->identity->username,
            'command' => $sql,
        ])->execute();
        return true;
    }

    public function afterDelete()
    {
        parent::afterDelete();
        // Create history record
        $sql = "DELETE FROM `{$this->tableName()}` WHERE `ID` = {$this->primaryKey};";
        $this->db->createCommand()->insert('history', [
            'user' => Yii::$app->user->identity->username,
            'command' => $sql,
        ])->execute();
        return true;
    }
}
