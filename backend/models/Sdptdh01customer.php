<?php
namespace app\models;

use yii\db\Query;
use Yii;

/**
 * This is the model class for table "SDP_TDH01_CUSTOMER".
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
 * @property string $D01_DLT_DATE
 */
class Sdptdh01customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SDP_TDH01_CUSTOMER';
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
            [['D01_YUBIN_BANGO', 'D01_INP_DATE', 'D01_UPD_DATE', 'D01_DLT_DATE'], 'string', 'max' => 7],
            [['D01_ADDR'], 'string', 'max' => 70],
            [['D01_NOTE'], 'string', 'max' => 2000],
            [['D01_SS_CD'], 'string', 'max' => 6],
            [['D01_TAISYO'], 'string', 'max' => 1],
            [['D01_INP_USER_ID', 'D01_UPD_USER_ID'], 'string', 'max' => 20],
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
            'D01_DLT_DATE' => 'D01  Dlt  Date',
        ];
    }
}
