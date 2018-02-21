<?php
namespace app\models;

use yii\db\Query;
use Yii;

/**
 * This is the model class for table "SDP_TM03_LARGE_COM".
 *
 * @property integer $M03_KIND_COM_NO
 * @property integer $M03_LARGE_COM_NO
 * @property string $M03_LARGE_COM_NAMEN
 * @property integer $M03_ORDER
 * @property integer $M03_HOZON_KIKAN
 * @property string $M03_MEMO
 * @property string $M03_INP_DATE
 * @property string $M03_INP_USER_ID
 * @property string $M03_UPD_DATE
 * @property string $M03_UPD_USER_ID
 */
class Sdptm03largecom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SDP_TM03_LARGE_COM';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['M03_KIND_COM_NO', 'M03_LARGE_COM_NO'], 'required'],
            [['M03_KIND_COM_NO', 'M03_LARGE_COM_NO', 'M03_ORDER', 'M03_HOZON_KIKAN'], 'integer'],
            [['M03_LARGE_COM_NAMEN'], 'string', 'max' => 100],
            [['M03_MEMO'], 'string', 'max' => 500],
            [['M03_INP_DATE', 'M03_UPD_DATE'], 'string', 'max' => 7],
            [['M03_INP_USER_ID', 'M03_UPD_USER_ID'], 'string', 'max' => 20],
            [['M03_KIND_COM_NO', 'M03_LARGE_COM_NO'], 'unique', 'targetAttribute' => ['M03_KIND_COM_NO', 'M03_LARGE_COM_NO'], 'message' => 'The combination of M03  Kind  Com  No and M03  Large  Com  No has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'M03_KIND_COM_NO' => 'M03  Kind  Com  No',
            'M03_LARGE_COM_NO' => 'M03  Large  Com  No',
            'M03_LARGE_COM_NAMEN' => 'M03  Large  Com  Namen',
            'M03_ORDER' => 'M03  Order',
            'M03_HOZON_KIKAN' => 'M03  Hozon  Kikan',
            'M03_MEMO' => 'M03  Memo',
            'M03_INP_DATE' => 'M03  Inp  Date',
            'M03_INP_USER_ID' => 'M03  Inp  User  ID',
            'M03_UPD_DATE' => 'M03  Upd  Date',
            'M03_UPD_USER_ID' => 'M03  Upd  User  ID',
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

    public function getData($filters = [], $select = '*')
    {
        $query = $this->getWhere($filters, $select);
        $query->orderBy('M03_KIND_COM_NO ASC');
        return $query->all();
    }

    public function coutData($filters)
    {
        $query = $this->getWhere($filters);
        return $query->count();
    }
}
