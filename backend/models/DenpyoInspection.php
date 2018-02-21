<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;

class DenpyoInspection extends ActiveRecord
{
    public static function tableName()
    {
        return 'DENPYO_INSPECTION';
    }

    public $obj;
    public $fields = [
        'PARENT_DISCOUNT_ID',
        'DENPYO_NO',
        'DENPYO_SUGGEST_NO',
        'CAR_SIZE',
        'CAR_WEIGHT',
        'EARNEST_MONEY',
        'WEIGHT_TAX',
        'FEE_BASIC_ID',
        'FEE_REGISTRATION_ID',
        'PARENT_DISCOUNT_ID',
        'DISCOUNTS',
        'COMMENTS',
        'STATUS'
    ];

    private function getWhere($filters = [], $select = '*')
    {
        $query = new Query();
        $query->select($select)->from(static::tableName());

        if (isset($filters['DENPYO_NO']) && $filters['DENPYO_NO']) {
            $query->andwhere('DENPYO_NO =:den_no', [':den_no' => $filters['DENPYO_NO']]);
        }
        if (isset($filters['DENPYO_SUGGEST_NO']) && $filters['DENPYO_SUGGEST_NO']) {
            $query->andwhere('DENPYO_SUGGEST_NO=:den_suggest_no', [':den_suggest_no' => $filters['DENPYO_SUGGEST_NO']]);
        }

        if (isset($filters['DENPYO_NO']) && $filters['DENPYO_NO']) {
            $query->andwhere('DENPYO_NO=:den_no', [':den_no' => $filters['DENPYO_NO']]);
        }

        if (isset($filters['start_time']) && $filters['start_time']) {
            $query->andwhere('CREATED_AT >= TO_DATE(\'' . $filters['start_time'] . '\',\'yyyymmdd\')');
        }

        if (isset($filters['end_time']) && $filters['end_time']) {
            $query->andwhere('CREATED_AT <= TO_DATE(\'' . $filters['end_time'] . '\',\'yyyymmdd\')');
        }
        if (isset($filters['COMMENTS']) && $filters['COMMENTS']) {
            foreach ($filters['COMMENTS'] as $value) {
                $query->orWhere(['LIKE', 'COMMENTS', ",$value,"]);
            }
        }

        if (isset($filters['PARENT_DISCOUNT_ID']) && $filters['PARENT_DISCOUNT_ID']) {
            $query->andwhere('PARENT_DISCOUNT_ID=:parent_discount_id', [':parent_discount_id' => $filters['PARENT_DISCOUNT_ID']]);
        }

        if (isset($filters['DISCOUNTS']) && $filters['DISCOUNTS']) {
            foreach ($filters['DISCOUNTS'] as $value) {
                $query->orWhere(['LIKE', 'DISCOUNTS', ",$value,"]);
            }
        }

        if (isset($filters['offset']) && $filters['offset']) {
            $query->offset($filters['offset']);
        }

        if (isset($filters['limit']) && $filters['limit']) {
            $query->limit($filters['limit']);
        }

        return $query;
    }

    public function getData($filters = [], $select = '*')
    {
        $query = $this->getWhere($filters, $select);
        $query->orderBy('DENPYO_NO DESC');
        return $query->all();
    }

    public function countData($filters = [], $select = 'ID')
    {
        $query = $this->getWhere($filters, $select);
        return $query->count();
    }

    public function setData($data = [], $id = null)
    {
        $obj = new DenpyoInspection();
        $obj->UPDATED_AT = new Expression("CURRENT_DATE");
        if ($id) {
            $obj = static::findOne($id);
        } else {
            $obj->CREATED_AT = new Expression("CURRENT_DATE");
        }

        foreach ($this->fields as $k => $v) {
            $obj->{$v} = isset($data[$v]) && trim($data[$v]) != '' ? trim($data[$v]) : null;
        }
        $obj->STATUS = 0;
        $this->obj = $obj;
    }

    public function saveData()
    {
        return $this->obj->save();
    }
}
