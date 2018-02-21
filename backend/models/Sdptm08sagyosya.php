<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\db\Query;

/**
 * @author Thuanth6589 <thuanth6589@seta-asia.com.vn>
 * This is the model class for table "SDP_TM08_SAGYOSYA".
 *
 * @property string $M08_HAN_CD
 * @property string $M08_SS_CD
 * @property string $M08_JYUG_CD
 * @property string $M08_NAME_SEI
 * @property string $M08_NAME_MEI
 * @property integer $M08_ORDER
 * @property string $M08_INP_DATE
 * @property string $M08_INP_USER_ID
 * @property string $M08_UPD_DATE
 * @property string $M08_UPD_USER_ID
 */
class Sdptm08sagyosya extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SDP_TM08_SAGYOSYA';
    }

    public $obj;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['M08_HAN_CD', 'M08_SS_CD', 'M08_JYUG_CD'], 'required'],
            [['M08_ORDER'], 'integer'],
            [['M08_HAN_CD', 'M08_SS_CD'], 'string', 'max' => 6],
            [['M08_JYUG_CD'], 'string', 'max' => 10],
            [['M08_NAME_SEI', 'M08_NAME_MEI'], 'string', 'max' => 30],
            [['M08_INP_USER_ID', 'M08_UPD_USER_ID'], 'string', 'max' => 20],
            [
                ['M08_HAN_CD', 'M08_SS_CD', 'M08_JYUG_CD'],
                'unique',
                'targetAttribute' => ['M08_HAN_CD', 'M08_SS_CD', 'M08_JYUG_CD'],
                'message' => 'The combination of M08  Han  Cd, M08  Ss  Cd and M08  Jyug  Cd has already been taken.'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'M08_HAN_CD' => 'M08  Han  Cd',
            'M08_SS_CD' => 'M08  Ss  Cd',
            'M08_JYUG_CD' => 'M08  Jyug  Cd',
            'M08_NAME_SEI' => 'M08  Name  Sei',
            'M08_NAME_MEI' => 'M08  Name  Mei',
            'M08_ORDER' => 'M08  Order',
            'M08_INP_DATE' => 'M08  Inp  Date',
            'M08_INP_USER_ID' => 'M08  Inp  User  ID',
            'M08_UPD_DATE' => 'M08  Upd  Date',
            'M08_UPD_USER_ID' => 'M08  Upd  User  ID',
        ];
    }

    /**
     * @param array $filters
     * @param string $select
     * @return Query
     */
    private function getWhere($filters = [], $select = '*')
    {
        $query = new Query();
        $query->select($select)->from(static::tableName());

        if (isset($filters['M08_HAN_CD']) && $filters['M08_HAN_CD']) {
            $query->where('M08_HAN_CD=:han_cd', [':han_cd' => $filters['M08_HAN_CD']]);
        }

        if (isset($filters['M08_SS_CD']) && $filters['M08_SS_CD']) {
            $query->where('M08_SS_CD=:ss_cd', [':ss_cd' => $filters['M08_SS_CD']]);
        }

		if (isset($filters['M08_JYUG_CD']) && $filters['M08_JYUG_CD']) {
            $query->where('M08_JYUG_CD=:jy_cd', [':jy_cd' => $filters['M08_JYUG_CD']]);
        }

        if (isset($filters['offset']) && $filters['offset']) {
            $query->offset($filters['offset']);
        }

        if (isset($filters['limit']) && $filters['limit']) {
            $query->limit($filters['limit']);
        }

        return $query;
    }

    /**
     * @param array $filters
     * @param string $select
     * @return array
     */
    public function getData($filters = [], $select = '*')
    {
        $query = $this->getWhere($filters, $select);
        $query->orderBy('M08_ORDER ASC');
        return $query->all();
    }

    /**
     * @param array $filters
     * @param string $select
     * @return int|string
     */
    public function counData($filters = [], $select = '*')
    {
        $query = $this->getWhere($filters, $select);
        return $query->count();
    }

    /**
     * @param array $data
     * @param array $id
     */
    public function setData($data = [], $id = [])
    {
        $login_info = Yii::$app->session->get('login_info');
        $data['M08_UPD_USER_ID'] = $login_info['M50_USER_ID'];
        if ($id['M08_HAN_CD'] && $id['M08_SS_CD'] && $id['M08_JYUG_CD']) {
            $obj = static::findOne($id);
        } else {
            $obj = new Sdptm08sagyosya();
            $data['M08_INP_USER_ID'] = $login_info['M50_USER_ID'];
            $obj->M08_INP_DATE = new Expression("CURRENT_DATE");
        }

        $obj->attributes = $data;
        foreach ($obj->attributes as $k => $v) {
            if ($k != 'M08_INP_DATE' && $k != 'M08_UPD_DATE') {
                $obj->{$k} = trim($v) != '' ? trim($v) : null;
            }
        }

        $obj->M08_UPD_DATE = new Expression("CURRENT_DATE");
        $this->obj = $obj;
    }

    /**
     * save staff
     * @return mixed
     */
    public function saveData()
    {
        return $this->obj->save();
    }


    /**
     * get insert id
     * @return mixed
     */
    public function getPrimaryKeyAfterSave()
    {
        return $this->obj->getPrimaryKey();
    }

    /**
     * delete data
     * @param array $primaryKey
     * @return bool|false|int
     * @throws \Exception
     */
    public function deleteData($primaryKey = [])
    {
        if (!isset($primaryKey['M08_HAN_CD'])
            || !isset($primaryKey['M08_SS_CD'])
            || !isset($primaryKey['M08_JYUG_CD1'])
        ) {
            if ($obj = static::findOne($primaryKey)) {
                return $obj->delete();
            }
        }
        return false;
    }
}
