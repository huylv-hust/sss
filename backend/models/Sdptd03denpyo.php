<?php

namespace app\models;

use yii\db\Expression;
use yii\db\Query;
use Yii;

/**
 * This is the model class for table "SDP_TD03_DENPYO".
 *
 * @property integer $D03_DEN_NO
 * @property integer $D03_CUST_NO
 * @property string $D03_STATUS
 * @property string $D03_SS_CD
 * @property string $D03_KITYOHIN
 * @property string $D03_KAKUNIN
 * @property string $D03_SEISAN
 * @property integer $D03_CAR_SEQ
 * @property string $D03_CAR_NAMEN
 * @property string $D03_RIKUUN_NAMEN
 * @property string $D03_CAR_ID
 * @property string $D03_HIRA
 * @property string $D03_CAR_NO
 * @property integer $D03_METER_KM
 * @property string $D03_SEKOU_YMD
 * @property string $D03_AZU_BEGIN_HH
 * @property string $D03_AZU_BEGIN_MI
 * @property string $D03_AZU_END_HH
 * @property string $D03_AZU_END_MI
 * @property integer $D03_YOYAKU_SAGYO_NO
 * @property string $D03_SAGYO_OTHER
 * @property integer $D03_SUM_KINGAKU
 * @property string $D03_NOTE
 * @property string $D03_TAISYO
 * @property string $D03_INP_DATE
 * @property string $D03_INP_USER_ID
 * @property string $D03_UPD_DATE
 * @property string $D03_UPD_USER_ID
 * @property string $D03_JIKAI_SHAKEN_YM
 * @property string $D03_TANTO_SEI
 * @property string $D03_TANTO_MEI
 * @property string $D03_KAKUNIN_SEI
 * @property string $D03_KAKUNIN_MEI
 * @property string $D03_POS_DEN_NO
 */
class Sdptd03denpyo extends \yii\db\ActiveRecord
{

    public $obj;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SDP_TD03_DENPYO';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['D03_DEN_NO', 'D03_CUST_NO'], 'required'],
            [['D03_DEN_NO', 'D03_CUST_NO', 'D03_CAR_SEQ', 'D03_METER_KM', 'D03_YOYAKU_SAGYO_NO', 'D03_SUM_KINGAKU'], 'integer'],
            [['D03_STATUS', 'D03_KITYOHIN', 'D03_KAKUNIN', 'D03_SEISAN', 'D03_TAISYO'], 'string', 'max' => 1],
            [['D03_SS_CD'], 'string', 'max' => 6],
            [['D03_CAR_NAMEN', 'D03_POS_DEN_NO'], 'string', 'max' => 100],
            [['D03_RIKUUN_NAMEN'], 'string', 'max' => 10],
            [['D03_CAR_ID'], 'string', 'max' => 3],
            [['D03_HIRA', 'D03_AZU_BEGIN_HH', 'D03_AZU_BEGIN_MI', 'D03_AZU_END_HH', 'D03_AZU_END_MI'], 'string', 'max' => 2],
            [['D03_CAR_NO'], 'string', 'max' => 4],
            [['D03_SEKOU_YMD', 'D03_JIKAI_SHAKEN_YM'], 'string', 'max' => 8],
            [['D03_SAGYO_OTHER', 'D03_NOTE'], 'string', 'max' => 2000],
            [['D03_INP_USER_ID', 'D03_UPD_USER_ID'], 'string', 'max' => 20],
            [['D03_TANTO_SEI', 'D03_TANTO_MEI', 'D03_KAKUNIN_SEI', 'D03_KAKUNIN_MEI'], 'string', 'max' => 30],
            [['D03_DEN_NO'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'D03_DEN_NO' => 'D03  Den  No',
            'D03_CUST_NO' => 'D03  Cust  No',
            'D03_STATUS' => 'D03  Status',
            'D03_SS_CD' => 'D03  Ss  Cd',
            'D03_KITYOHIN' => 'D03  Kityohin',
            'D03_KAKUNIN' => 'D03  Kakunin',
            'D03_SEISAN' => 'D03  Seisan',
            'D03_CAR_SEQ' => 'D03  Car  Seq',
            'D03_CAR_NAMEN' => 'D03  Car  Namen',
            'D03_RIKUUN_NAMEN' => 'D03  Rikuun  Namen',
            'D03_CAR_ID' => 'D03  Car  ID',
            'D03_HIRA' => 'D03  Hira',
            'D03_CAR_NO' => 'D03  Car  No',
            'D03_METER_KM' => 'D03  Meter  Km',
            'D03_SEKOU_YMD' => 'D03  Sekou  Ymd',
            'D03_AZU_BEGIN_HH' => 'D03  Azu  Begin  Hh',
            'D03_AZU_BEGIN_MI' => 'D03  Azu  Begin  Mi',
            'D03_AZU_END_HH' => 'D03  Azu  End  Hh',
            'D03_AZU_END_MI' => 'D03  Azu  End  Mi',
            'D03_YOYAKU_SAGYO_NO' => 'D03  Yoyaku  Sagyo  No',
            'D03_SAGYO_OTHER' => 'D03  Sagyo  Other',
            'D03_SUM_KINGAKU' => 'D03  Sum  Kingaku',
            'D03_NOTE' => 'D03  Note',
            'D03_TAISYO' => 'D03  Taisyo',
            'D03_INP_DATE' => 'D03  Inp  Date',
            'D03_INP_USER_ID' => 'D03  Inp  User  ID',
            'D03_UPD_DATE' => 'D03  Upd  Date',
            'D03_UPD_USER_ID' => 'D03  Upd  User  ID',
            'D03_JIKAI_SHAKEN_YM' => 'D03  Jikai  Shaken  Ym',
            'D03_TANTO_SEI' => 'D03  Tanto  Sei',
            'D03_TANTO_MEI' => 'D03  Tanto  Mei',
            'D03_KAKUNIN_SEI' => 'D03  Kakunin  Sei',
            'D03_KAKUNIN_MEI' => 'D03  Kakunin  Mei',
            'D03_POS_DEN_NO' => 'D03  Pos  Den  No',
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
        $data['D03_UPD_DATE'] = new Expression("CURRENT_DATE");
        $data['D03_UPD_USER_ID'] = $login_info['M50_USER_ID'];

        if ($id) {
            $obj = static::findOne($id);
        } else {
            $obj = new Sdptd03denpyo();
            $obj->D03_INP_DATE = new Expression("CURRENT_DATE");
            $data['D03_STATUS'] = 0;
            $data['D03_INP_USER_ID'] = $login_info['M50_USER_ID'];
        }

        $obj->attributes = $data;
        foreach ($obj->attributes as $k => $v) {
            if ($k != 'D03_UPD_DATE' && $k != 'D03_INP_DATE') {
                $obj->{$k} = trim($v) != '' ? trim($v) : null;
            }
        }

        $obj->D03_UPD_DATE = new Expression("CURRENT_DATE");
        $this->obj = $obj;
    }

    public function getData($filters = [], $select = '*')
    {
        $query = new Query();
        if (isset($filters['D03_CUST_NO']) && $filters['D03_CUST_NO'] != '') {
            $query->andwhere('SDP_TD03_DENPYO.D03_CUST_NO=:D03_CUST_NO', [':D03_CUST_NO' => $filters['D03_CUST_NO']]);
        }

        if (isset($filters['D03_DEN_NO']) && $filters['D03_DEN_NO'] != '') {
            $query->andwhere('SDP_TD03_DENPYO.D03_DEN_NO=:D03_DEN_NO', [':D03_DEN_NO' => $filters['D03_DEN_NO']]);
        }

        $query = $this->getWhere($filters, $select);
        $query->orderBy('D03_DEN_NO ASC');

        return $query->all();
    }

    private function getWhereInspection($filters)
    {
        $query = new Query();
        $query->select(["SDP_TD03_DENPYO.*, TO_CHAR(D03_UPD_DATE, 'YYYY/mm/DD') as CHAR_D03_UPD_DATE,DENPYO_INSPECTION.*,
            SDP_TD01_CUSTOMER.D01_CUST_NAMEN, SDP_TD01_CUSTOMER.D01_KAKE_CARD_NO, SDP_TD01_CUSTOMER.D01_YUBIN_BANGO, SDP_TD01_CUSTOMER.D01_ADDR, SDP_TD01_CUSTOMER.D01_TEL_NO, SDP_TD01_CUSTOMER.D01_MOBTEL_NO, SDP_TD01_CUSTOMER.D01_CUST_NAMEK, SDP_TD01_CUSTOMER.D01_NOTE"])
            ->from(static::tableName())
            ->innerJoin('DENPYO_INSPECTION', 'SDP_TD03_DENPYO.D03_DEN_NO = DENPYO_INSPECTION.DENPYO_NO')
            ->leftJoin('SDP_TD01_CUSTOMER', 'SDP_TD03_DENPYO.D03_CUST_NO = SDP_TD01_CUSTOMER.D01_CUST_NO')
            ->orderBy('D03_DEN_NO');

        if (isset($filters['start_time']) && $filters['start_time']) {
            $query->andwhere(['>=', 'SDP_TD03_DENPYO.D03_SEKOU_YMD', $filters['start_time']]);
        }

        if (isset($filters['end_time']) && $filters['end_time']) {
            $query->andwhere(['<=', 'SDP_TD03_DENPYO.D03_SEKOU_YMD', $filters['end_time']]);
        }

        if (isset($filters['offset']) && $filters['offset']) {
            $query->offset($filters['offset']);
        }


        if (isset($filters['limit']) && $filters['limit']) {
            $query->limit($filters['limit']);
        }

        return $query;
    }

    public function getDataInspection($filters)
    {
        $query = $this->getWhereInspection($filters);
        $query->orderBy('SDP_TD03_DENPYO.D03_DEN_NO DESC');
        return $query->all();
    }

    public function countDataInspection($filters)
    {
        $query = $this->getWhereInspection($filters);
        return $query->count();
    }

    //detail-huylv
    private function getWhereSearch($filters)
    {
        $query = new Query();
        $query->select(["SDP_TD03_DENPYO.*, TO_CHAR(D03_UPD_DATE, 'YYYY/mm/DD') as CHAR_D03_UPD_DATE,
            SDP_TD01_CUSTOMER.D01_CUST_NAMEN, SDP_TD01_CUSTOMER.D01_KAKE_CARD_NO, SDP_TD01_CUSTOMER.D01_YUBIN_BANGO, SDP_TD01_CUSTOMER.D01_ADDR, SDP_TD01_CUSTOMER.D01_TEL_NO, SDP_TD01_CUSTOMER.D01_MOBTEL_NO, SDP_TD01_CUSTOMER.D01_CUST_NAMEK, SDP_TD01_CUSTOMER.D01_NOTE"])
            ->from(static::tableName())
            ->leftJoin('SDP_TD01_CUSTOMER', 'SDP_TD03_DENPYO.D03_CUST_NO = SDP_TD01_CUSTOMER.D01_CUST_NO')
            ->orderBy('D03_DEN_NO');

        if (isset($filters['status']) && $filters['status'] != '') {
            if ($filters['status'] == 1) {
                $query->andwhere('SDP_TD03_DENPYO.D03_STATUS=:status', [':status' => 0]);
                $query->andWhere(['>', 'SDP_TD03_DENPYO.D03_SEKOU_YMD', date('Ymd')]);
            }

            if ($filters['status'] == 2) {
                $query->andwhere('SDP_TD03_DENPYO.D03_STATUS=:status', [':status' => 1]);
            }

            if ($filters['status'] == 0) {
                $query->andwhere('SDP_TD03_DENPYO.D03_STATUS=:status', [':status' => 0]);
                $query->andWhere(['<=', 'SDP_TD03_DENPYO.D03_SEKOU_YMD', date('Ymd')]);
            }
        }

        if (isset($filters['car']) && $filters['car']) {
            $query->andwhere(['like', 'SDP_TD03_DENPYO.D03_CAR_NO', $filters['car']]);
        }

        if (isset($filters['job']) && $filters['job'] != '') {
            $subQuery = new Query();
            $subQuery->select(['*'])->from('SDP_TD04_DENPYO_SAGYO')
                ->andWhere('SDP_TD03_DENPYO.D03_DEN_NO = SDP_TD04_DENPYO_SAGYO.D04_DEN_NO')
                ->andWhere(['=', 'D04_SAGYO_NO', $filters['job']]);
            $query->andWhere(['exists', $subQuery]);
        }

        if (isset($filters['D03_CUST_NO']) && count($filters['D03_CUST_NO'])) {
            $query->andwhere(['in', 'SDP_TD03_DENPYO.D03_CUST_NO', $filters['D03_CUST_NO']]);
        }

        if (isset($filters['start_time']) && $filters['start_time']) {
            $query->andwhere(['>=', 'SDP_TD03_DENPYO.D03_SEKOU_YMD', $filters['start_time']]);
        }

        if (isset($filters['end_time']) && $filters['end_time']) {
            $query->andwhere(['<=', 'SDP_TD03_DENPYO.D03_SEKOU_YMD', $filters['end_time']]);
        }

        if (isset($filters['detail_no'])) {
            $query->andwhere('SDP_TD03_DENPYO.D03_DEN_NO=:den_no', [':den_no' => $filters['detail_no']]);
        }

        if (isset($filters['m50_ss_cd']) && $filters['m50_ss_cd']) {
            $query->andwhere('SDP_TD03_DENPYO.D03_SS_CD =:m50_ss_cd', [':m50_ss_cd' => $filters['m50_ss_cd']]);
        }

        if (isset($filters['D03_POS_DEN_NO']) && $filters['D03_POS_DEN_NO']) {
            $query->andwhere('SDP_TD03_DENPYO.D03_POS_DEN_NO like:pos_den_no', [':pos_den_no' => '%' . $filters['D03_POS_DEN_NO'] . '%']);
        }

        if (isset($filters['DENPYO_NO_IN']) && $filters['DENPYO_NO_IN']) {
            $query->andwhere('SDP_TD03_DENPYO.D03_DEN_NO IN (' . $filters['DENPYO_NO_IN'] . ')');
        }

        if (isset($filters['DENPYO_NO_NOT_IN']) && !empty($filters['DENPYO_NO_NOT_IN'])) {
            foreach ($filters['DENPYO_NO_NOT_IN'] as $k => $v) {
                $query->andWhere('SDP_TD03_DENPYO.D03_DEN_NO NOT IN (' . implode(',', $v) . ')');
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

    public function getDataSearch($filters)
    {
        $query = $this->getWhereSearch($filters);
        $query->orderBy('SDP_TD03_DENPYO.D03_DEN_NO DESC');
        return $query->all();
    }

    public function countDataSearch($filters)
    {
        $query = $this->getWhereSearch($filters);
        return $query->count();
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

    public function deleteData($primaryKey = [])
    {
        $transaction = $this->getDb()->beginTransaction();
        try {
            if (isset($primaryKey['cus_no']) && isset($primaryKey['car_no'])) {
                if ($obj = Sdptd02car::findOne([$primaryKey['cus_no'], $primaryKey['car_no']])) {
                    if (!$obj->delete()) {
                        $transaction->rollback();
                        return false;
                    }
                }
            }

            $delete_job = Sdptd04denpyosagyo::deleteAll('D04_DEN_NO =' . $primaryKey['den_no']);
            $delete_product = Sdptd05denpyocom::deleteAll('D05_DEN_NO =' . $primaryKey['den_no']);
            if (!isset($delete_job) || !isset($delete_product)) {
                $transaction->rollback();
                return false;
            }
            if ($obj = static::findOne($primaryKey['den_no'])) {
                if (!$obj->delete()) {
                    $transaction->rollback();
                    return false;
                }
            }

            $insert = new Sdptw01deldenpyo();
            $insert->setData(['W01_DEN_NO' => $primaryKey['den_no']]);
            if (!$insert->saveData()) {
                $transaction->rollback();
                return false;
            }

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollback();
        }

        return false;
    }

    public function deleteDataInspection($primaryKey = [], $type)
    {
        $transaction = $this->getDb()->beginTransaction();
        try {
            $delete_job = Sdptd04denpyosagyo::deleteAll('D04_DEN_NO =' . $primaryKey['den_no']);
            $delete_product = Sdptd05denpyocom::deleteAll('D05_DEN_NO =' . $primaryKey['den_no']);
            if ($type == 'denpyo') {
                $delete_inspection = DenpyoInspection::deleteAll('DENPYO_NO =' . $primaryKey['den_no']);
            } else {
                $delete_inspection = 1;
            }
            if (!isset($delete_job) || !isset($delete_product) || !$delete_inspection) {
                $transaction->rollback();
                return false;
            }
            if ($obj = static::findOne($primaryKey['den_no'])) {
                if (!$obj->delete()) {
                    $transaction->rollback();
                    return false;
                }
            }

            $insert = new Sdptw01deldenpyo();
            $insert->setData(['W01_DEN_NO' => $primaryKey['den_no']]);
            if (!$insert->saveData()) {
                $transaction->rollback();
                return false;
            }

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollback();
        }

        return false;
    }

    public function getSeq()
    {
        $command = \Yii::$app->db->createCommand('SELECT SDP_TD03_DENPYO_SEQ.nextval FROM dual');
        $res = $command->queryAll();
        return $res['0']['NEXTVAL'];
    }

}
