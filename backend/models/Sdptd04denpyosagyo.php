<?php

namespace app\models;

use yii\db\Expression;
use yii\db\Query;
use Yii;

/**
 * This is the model class for table "SDP_TD04_DENPYO_SAGYO".
 *
 * @property integer $D04_DEN_NO
 * @property integer $D04_SAGYO_NO
 * @property string $D04_INP_DATE
 * @property string $D04_INP_USER_ID
 * @property string $D04_UPD_DATE
 * @property string $D04_UPD_USER_ID
 */
class Sdptd04denpyosagyo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SDP_TD04_DENPYO_SAGYO';
    }

    public $obj;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['D04_DEN_NO', 'D04_SAGYO_NO'], 'required'],
            [['D04_DEN_NO', 'D04_SAGYO_NO'], 'integer'],
            [['D04_INP_USER_ID', 'D04_UPD_USER_ID'], 'string', 'max' => 20],
            [['D04_DEN_NO', 'D04_SAGYO_NO'], 'unique', 'targetAttribute' => ['D04_DEN_NO', 'D04_SAGYO_NO'], 'message' => 'The combination of D04  Den  No and D04  Sagyo  No has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'D04_DEN_NO' => 'D04  Den  No',
            'D04_SAGYO_NO' => 'D04  Sagyo  No',
            'D04_INP_DATE' => 'D04  Inp  Date',
            'D04_INP_USER_ID' => 'D04  Inp  User  ID',
            'D04_UPD_DATE' => 'D04  Upd  Date',
            'D04_UPD_USER_ID' => 'D04  Upd  User  ID',
        ];
    }

    private function getWhere($filters = [], $select = '*')
    {
        $query = new Query();
        $query->select($select)->from(static::tableName());

        if (isset($filters['D04_DEN_NO']) && $filters['D04_DEN_NO']) {
            $query->andwhere('D04_DEN_NO=:den_no', [':den_no' => $filters['D04_DEN_NO']]);
        }

        if (isset($filters['D04_SAGYO_NO']) && $filters['D04_SAGYO_NO']) {
            $query->andwhere('D04_SAGYO_NO=:sagyo_no', [':sagyo_no' => $filters['D04_SAGYO_NO']]);
        }

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
        $login_info = Yii::$app->session->get('login_info');
        $obj = new Sdptd04denpyosagyo();
        $data['D04_UPD_USER_ID'] = $login_info['M50_USER_ID'];

        if ($id) {
            $obj = static::findOne($id);
        } else {
            $obj->D04_INP_DATE = new Expression("CURRENT_DATE");
            $data['D04_INP_USER_ID'] = $login_info['M50_USER_ID'];
        }

        $obj->attributes = $data;
        foreach ($obj->attributes as $k => $v) {
            if ($k != 'D04_UPD_DATE' && $k != 'D04_INP_DATE') {
                $obj->{$k} = trim($v) != '' ? trim($v) : null;
            }
        }

        $obj->D04_UPD_DATE = new Expression("CURRENT_DATE");
        $this->obj = $obj;
    }

    public function saveDataMuti($insertData)
    {

        $columnNameArray = array_keys(current($insertData));
        $data = [];
        foreach ($insertData as $key => $row) {
            $data[] = array_values($row);
        }
        //$columnNameArray = array_keys($this->attributeLabels());
        $insertCount = Yii::$app->db->createCommand()
            ->batchInsert(self::tableName(), $columnNameArray, $data)
            ->execute();
        return $insertCount;
    }

    public function getData($filters = [], $select = '*')
    {
        $query = $this->getWhere($filters, $select);
        $query->orderBy('D04_DEN_NO ASC');
        return $query->all();
    }

    public function deleteData($where)
    {
        if ($where) {
            return Sdptd04denpyosagyo::deleteAll($where);
        }

        return false;
    }
}
