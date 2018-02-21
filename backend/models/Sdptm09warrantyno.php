<?php
namespace app\models;

use yii\db\Query;
use Yii;

/**
 * This is the model class for table "SDP_TM09_WARRANTY_NO".
 *
 * @property string $M09_SS_CD
 * @property integer $M09_WARRANTY_NO
 * @property string $M09_INP_DATE
 * @property string $M09_INP_USER_ID
 * @property string $M09_UPD_DATE
 * @property string $M09_UPD_USER_ID
 */
class Sdptm09warrantyno extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    private $obj;

    public static function tableName()
    {
        return 'SDP_TM09_WARRANTY_NO';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['M09_SS_CD'], 'required'],
            [['M09_WARRANTY_NO'], 'integer'],
            [['M09_SS_CD'], 'string', 'max' => 6],
            [['M09_INP_USER_ID', 'M09_UPD_USER_ID'], 'string', 'max' => 20],
            [['M09_SS_CD'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'M09_SS_CD' => 'M09  Ss  Cd',
            'M09_WARRANTY_NO' => 'M09  Warranty  No',
            'M09_INP_DATE' => 'M09  Inp  Date',
            'M09_INP_USER_ID' => 'M09  Inp  User  ID',
            'M09_UPD_DATE' => 'M09  Upd  Date',
            'M09_UPD_USER_ID' => 'M09  Upd  User  ID',
        ];
    }

    private function getWhere($filters = [], $select = '*')
    {
        $query = new Query();
        $query->select($select)->from(static::tableName());
        if (count($filters)) {
            foreach ($filters as $field => $val) {
                if ($field != 'offset' && $field != 'limit') {
                    $query->andwhere($field . ' = ' . $val);
                }
            }
        }
        //$query->where('status=:status', [':status' => $status]);
        if (isset($filters['offset']) && $filters['offset']) {
            $query->offset($filters['offset']);
        }

        if (isset($filters['limit']) && $filters['limit']) {
            $query->limit($filters['limit']);
        }

        return $query;
    }

    public function saveData()
    {
        return $this->obj->save();
    }

    public function setData($data = [], $id = null)
    {
        $obj = new Sdptm09warrantyno();
        if ($id) {
            $obj = static::findOne($id);
        }
        $obj->attributes = $data;
        foreach ($obj->attributes as $k => $v) {
            if ($k != 'M09_INP_DATE' && $k != 'M09_UPD_DATE') {
                $obj->{$k} = trim($v) != '' ? trim($v) : null;
            } else {
                $obj->{$k} = $v;
            }
        }

        $this->obj = $obj;
    }

    public function getData($filters = [], $select = '*')
    {
        $query = $this->getWhere($filters, $select);
        $query->orderBy('M09_WARRANTY_NO ASC');
        return $query->all();
    }

    public function coutData($filters)
    {
        $query = $this->getWhere($filters);
        return $query->count();
    }
}
