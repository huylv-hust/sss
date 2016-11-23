<?php
namespace app\models;

use yii\db\Query;
use Yii;

/**
 * This is the model class for table "SDP_TM01_SAGYO".
 *
 * @property integer $M01_SAGYO_NO
 * @property string $M01_SAGYO_NAMEN
 * @property integer $M01_ORDER
 * @property string $M01_MEMO
 * @property string $M01_INP_DATE
 * @property string $M01_INP_USER_ID
 * @property string $M01_UPD_DATE
 * @property string $M01_UPD_USER_ID
 */
class Sdptm01sagyo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SDP_TM01_SAGYO';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['M01_SAGYO_NO'], 'required'],
            [['M01_SAGYO_NO', 'M01_ORDER'], 'integer'],
            [['M01_SAGYO_NAMEN'], 'string', 'max' => 100],
            [['M01_MEMO'], 'string', 'max' => 500],
            [['M01_INP_DATE', 'M01_UPD_DATE'], 'string', 'max' => 7],
            [['M01_INP_USER_ID', 'M01_UPD_USER_ID'], 'string', 'max' => 20],
            [['M01_SAGYO_NO'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'M01_SAGYO_NO' => 'M01  Sagyo  No',
            'M01_SAGYO_NAMEN' => 'M01  Sagyo  Namen',
            'M01_ORDER' => 'M01  Order',
            'M01_MEMO' => 'M01  Memo',
            'M01_INP_DATE' => 'M01  Inp  Date',
            'M01_INP_USER_ID' => 'M01  Inp  User  ID',
            'M01_UPD_DATE' => 'M01  Upd  Date',
            'M01_UPD_USER_ID' => 'M01  Upd  User  ID',
        ];
    }

    private function getWhere($filters = [], $select = '*')
    {
        $query = new Query();
        $query->select($select)->from(static::tableName());

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
        return $query->all();
    }

    public function saveData()
    {
        return $this->obj->save();
    }

    public function setData($data = [], $id = null)
    {
        $obj = new Sdptd04denpyosagyo();
        if ($id) {
            $obj = static::findOne($id);
        }

        $obj->attributes = $data;
        foreach ($obj->attributes as $k => $v) {
            $obj->{$k} = trim($v) != '' ? trim($v) : null;
        }

        $this->obj = $obj;
    }

    public function coutData($filters)
    {
        $query = $this->getWhere($filters);
        return $query->count();
    }
}
