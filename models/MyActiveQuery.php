<?php

namespace app\modules\warehouse\models;

/**
* This is the ActiveQuery class for [[Availability]].
*
* @see Availability
*/
class MyActiveQuery extends \yii\db\ActiveQuery
{
    public function all($db = null)
    {
        return parent::all($db);
    }
 
    public function one($db = null)
    {
        return parent::one($db);
    }
  
} 
