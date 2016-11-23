<?php

namespace app\models;

use yii\db\Expression;
use yii\db\Query;
use Yii;

/**
 * This is the model class for table "SDP_TD01_CUSTOMER".
 *
 * @property integer $D01_CUST_NO
 * @property string $D01_KAIIN_CD
 * @property string $D01_KAKE_CARD_NO
 * @property string $D01_UKE_TAN_NAMEN
 * @property string $D01_CUST_NAMEN
 * @property string $D01_CUST_NAMEK
 * @property string $D01_YUBIN_BANGO
 * @property string $D01_ADDR
 * @property string $D01_TEL_NO
 * @property string $D01_MOBTEL_NO
 * @property string $D01_NOTE
 * @property string $D01_SS_CD
 * @property string $D01_TAISYO
 * @property string $D01_INP_DATE
 * @property string $D01_INP_USER_ID
 * @property string $D01_UPD_DATE
 * @property string $D01_UPD_USER_ID
 * @property string $D01_UKE_JYUG_CD
 */
class Sdptd01customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    private $obj;

    public static function tableName()
    {
        return 'SDP_TD01_CUSTOMER';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['D01_CUST_NO'], 'required'],
            [['D01_CUST_NO'], 'integer'],
            [['D01_KAIIN_CD', 'D01_TEL_NO', 'D01_MOBTEL_NO'], 'string', 'max' => 12],
            [['D01_KAKE_CARD_NO'], 'string', 'max' => 16],
            [['D01_UKE_TAN_NAMEN'], 'string', 'max' => 100],
            [['D01_CUST_NAMEN'], 'string', 'max' => 45],
            [['D01_CUST_NAMEK'], 'string', 'max' => 60],
            [['D01_YUBIN_BANGO'], 'string'],
            [['D01_ADDR'], 'string', 'max' => 70],
            [['D01_NOTE'], 'string', 'max' => 2000],
            [['D01_SS_CD'], 'string', 'max' => 6],
            [['D01_TAISYO'], 'string', 'max' => 1],
            [['D01_INP_USER_ID', 'D01_UPD_USER_ID'], 'string', 'max' => 20],
            [['D01_UKE_JYUG_CD'], 'string', 'max' => 10],
            [['D01_CUST_NO'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'D01_CUST_NO' => 'D01  Cust  No',
            'D01_KAIIN_CD' => 'D01  Kaiin  Cd',
            'D01_KAKE_CARD_NO' => 'D01  Kake  Card  No',
            'D01_UKE_TAN_NAMEN' => 'D01  Uke  Tan  Namen',
            'D01_CUST_NAMEN' => 'D01  Cust  Namen',
            'D01_CUST_NAMEK' => 'D01  Cust  Namek',
            'D01_YUBIN_BANGO' => 'D01  Yubin  Bango',
            'D01_ADDR' => 'D01  Addr',
            'D01_TEL_NO' => 'D01  Tel  No',
            'D01_MOBTEL_NO' => 'D01  Mobtel  No',
            'D01_NOTE' => 'D01  Note',
            'D01_SS_CD' => 'D01  Ss  Cd',
            'D01_TAISYO' => 'D01  Taisyo',
            'D01_INP_DATE' => 'D01  Inp  Date',
            'D01_INP_USER_ID' => 'D01  Inp  User  ID',
            'D01_UPD_DATE' => 'D01  Upd  Date',
            'D01_UPD_USER_ID' => 'D01  Upd  User  ID',
            'D01_UKE_JYUG_CD' => 'D01  Uke  Jyug  Cd',
        ];
    }

    private function getWhere($filters = [], $select = '*')
    {
        $query = new Query();
        $query->select($select)->from(static::tableName());

        //$query->where('status=:status', [':status' => $status]);
        if (isset($filters['D01_KAIIN_CD']) && $filters['D01_KAIIN_CD']) {
            $query->andwhere(['D01_KAIIN_CD' => $filters['D01_KAIIN_CD']]);
        }

        if (isset($filters['D01_CUST_NO']) && $filters['D01_CUST_NO']) {
            $query->andwhere(['D01_CUST_NO' => $filters['D01_CUST_NO']]);
        }
        if (isset($filters['D01_KAKE_CARD_NO']) && $filters['D01_KAKE_CARD_NO']) {
            $query->andwhere(['D01_KAKE_CARD_NO' => $filters['D01_KAKE_CARD_NO']]);
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

    public function setData($data = [], &$id = null)
    {
        $login_info = Yii::$app->session->get('login_info');
        $data['D01_UPD_USER_ID'] = $login_info['M50_USER_ID'];
        if ($id) {
            $obj = static::findOne($id);
        } else {
            $obj = new Sdptd01customer();
            $data['D01_CUST_NO'] = $obj->getSeq();
            $data['D01_TAISYO'] = 1;
            $id = $data['D01_CUST_NO'];

            $obj->D01_INP_DATE = new Expression("CURRENT_DATE");
            $data['D01_INP_USER_ID'] = $login_info['M50_USER_ID'];

        }

        $obj->attributes = $data;
        foreach ($obj->attributes as $k => $v) {
            if ($k != 'D01_UPD_DATE' && $k != 'D01_INP_DATE') {
                $obj->{$k} = trim($v) != '' ? trim($v) : null;
            }
        }

        $obj->D01_UPD_DATE = new Expression("CURRENT_DATE");
        $this->obj = $obj;
    }

    public function getData($filters = [], $select = '*')
    {
        $query = $this->getWhere($filters, $select);
        $query->orderBy('D01_CUST_NO ASC');
        return $query->all();
    }

    public function setDataDefault()
    {
        $attri = $this->attributeLabels();
        $data = [];
        foreach ($attri as $key => $val) {
            $data[$key] = null;
        }
        return $data;
    }

    public function getSeq()
    {
        $command = \Yii::$app->db->createCommand('SELECT SDP_TD01_CUSTOMER_SEQ.nextval FROM dual');
        $res = $command->queryAll();
        return $res['0']['NEXTVAL'];
    }

    public function getPrimaryKeyAfterSave()
    {
        return $this->obj->getPrimaryKey();
    }
}
