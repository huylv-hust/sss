<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SDP_TD06_DM_QUERY".
 *
 * @property string $D06_KANRI_ID
 * @property string $D06_STS
 * @property string $D06_TAISYO_KBN
 * @property string $D06_TITLE
 * @property string $D06_TARGET_CUST
 * @property string $D06_HAN
 * @property string $D06_TAISYO_YMD_FROM
 * @property string $D06_TAISYO_YMD_TO
 * @property string $D06_SS1
 * @property string $D06_SS2
 * @property integer $D06_KIND_COM_NO1
 * @property integer $D06_KIND_COM_NO2
 * @property integer $D06_KIND_COM_NO3
 * @property string $D06_COM_CD_FROM1
 * @property string $D06_NST_CD_FROM1
 * @property string $D06_COM_CD_TO1
 * @property string $D06_NST_CD_TO1
 * @property string $D06_COM_CD_FROM2
 * @property string $D06_NST_CD_FROM2
 * @property string $D06_COM_CD_TO2
 * @property string $D06_NST_CD_TO2
 * @property string $D06_COM_CD_FROM3
 * @property string $D06_NST_CD_FROM3
 * @property string $D06_COM_CD_TO3
 * @property string $D06_NST_CD_TO3
 * @property string $D06_COM_CD_FROM4
 * @property string $D06_NST_CD_FROM4
 * @property string $D06_COM_CD_TO4
 * @property string $D06_NST_CD_TO4
 * @property string $D06_COM_CD_FROM5
 * @property string $D06_NST_CD_FROM5
 * @property string $D06_COM_CD_TO5
 * @property string $D06_NST_CD_TO5
 * @property string $D06_JIKAI_SHAKEN_YM_FROM
 * @property string $D06_JIKAI_SHAKEN_YM_TO
 * @property string $D06_SHAKEN_GAITOU
 * @property string $D06_INP_DATE
 * @property string $D06_INP_USER_ID
 * @property string $D06_UPD_DATE
 * @property string $D06_UPD_USER_ID
 */
class Sdptd06dmquery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SDP_TD06_DM_QUERY';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['D06_KANRI_ID', 'D06_STS', 'D06_TAISYO_KBN'], 'required'],
            [['D06_KIND_COM_NO1', 'D06_KIND_COM_NO2', 'D06_KIND_COM_NO3'], 'integer'],
            [['D06_KANRI_ID'], 'string', 'max' => 17],
            [['D06_STS', 'D06_TAISYO_KBN', 'D06_TARGET_CUST', 'D06_SHAKEN_GAITOU'], 'string', 'max' => 1],
            [['D06_TITLE'], 'string', 'max' => 256],
            [['D06_HAN'], 'string', 'max' => 500],
            [['D06_TAISYO_YMD_FROM', 'D06_TAISYO_YMD_TO'], 'string', 'max' => 8],
            [['D06_SS1', 'D06_SS2'], 'string', 'max' => 2000],
            [['D06_COM_CD_FROM1', 'D06_COM_CD_TO1', 'D06_COM_CD_FROM2', 'D06_COM_CD_TO2', 'D06_COM_CD_FROM3', 'D06_COM_CD_TO3', 'D06_COM_CD_FROM4', 'D06_COM_CD_TO4', 'D06_COM_CD_FROM5', 'D06_COM_CD_TO5', 'D06_JIKAI_SHAKEN_YM_FROM', 'D06_JIKAI_SHAKEN_YM_TO'], 'string', 'max' => 6],
            [['D06_NST_CD_FROM1', 'D06_NST_CD_TO1', 'D06_NST_CD_FROM2', 'D06_NST_CD_TO2', 'D06_NST_CD_FROM3', 'D06_NST_CD_TO3', 'D06_NST_CD_FROM4', 'D06_NST_CD_TO4', 'D06_NST_CD_FROM5', 'D06_NST_CD_TO5'], 'string', 'max' => 3],
            [['D06_INP_DATE', 'D06_UPD_DATE'], 'string', 'max' => 7],
            [['D06_INP_USER_ID', 'D06_UPD_USER_ID'], 'string', 'max' => 20],
            [['D06_KANRI_ID'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'D06_KANRI_ID' => 'D06  Kanri  ID',
            'D06_STS' => 'D06  Sts',
            'D06_TAISYO_KBN' => 'D06  Taisyo  Kbn',
            'D06_TITLE' => 'D06  Title',
            'D06_TARGET_CUST' => 'D06  Target  Cust',
            'D06_HAN' => 'D06  Han',
            'D06_TAISYO_YMD_FROM' => 'D06  Taisyo  Ymd  From',
            'D06_TAISYO_YMD_TO' => 'D06  Taisyo  Ymd  To',
            'D06_SS1' => 'D06  Ss1',
            'D06_SS2' => 'D06  Ss2',
            'D06_KIND_COM_NO1' => 'D06  Kind  Com  No1',
            'D06_KIND_COM_NO2' => 'D06  Kind  Com  No2',
            'D06_KIND_COM_NO3' => 'D06  Kind  Com  No3',
            'D06_COM_CD_FROM1' => 'D06  Com  Cd  From1',
            'D06_NST_CD_FROM1' => 'D06  Nst  Cd  From1',
            'D06_COM_CD_TO1' => 'D06  Com  Cd  To1',
            'D06_NST_CD_TO1' => 'D06  Nst  Cd  To1',
            'D06_COM_CD_FROM2' => 'D06  Com  Cd  From2',
            'D06_NST_CD_FROM2' => 'D06  Nst  Cd  From2',
            'D06_COM_CD_TO2' => 'D06  Com  Cd  To2',
            'D06_NST_CD_TO2' => 'D06  Nst  Cd  To2',
            'D06_COM_CD_FROM3' => 'D06  Com  Cd  From3',
            'D06_NST_CD_FROM3' => 'D06  Nst  Cd  From3',
            'D06_COM_CD_TO3' => 'D06  Com  Cd  To3',
            'D06_NST_CD_TO3' => 'D06  Nst  Cd  To3',
            'D06_COM_CD_FROM4' => 'D06  Com  Cd  From4',
            'D06_NST_CD_FROM4' => 'D06  Nst  Cd  From4',
            'D06_COM_CD_TO4' => 'D06  Com  Cd  To4',
            'D06_NST_CD_TO4' => 'D06  Nst  Cd  To4',
            'D06_COM_CD_FROM5' => 'D06  Com  Cd  From5',
            'D06_NST_CD_FROM5' => 'D06  Nst  Cd  From5',
            'D06_COM_CD_TO5' => 'D06  Com  Cd  To5',
            'D06_NST_CD_TO5' => 'D06  Nst  Cd  To5',
            'D06_JIKAI_SHAKEN_YM_FROM' => 'D06  Jikai  Shaken  Ym  From',
            'D06_JIKAI_SHAKEN_YM_TO' => 'D06  Jikai  Shaken  Ym  To',
            'D06_SHAKEN_GAITOU' => 'D06  Shaken  Gaitou',
            'D06_INP_DATE' => 'D06  Inp  Date',
            'D06_INP_USER_ID' => 'D06  Inp  User  ID',
            'D06_UPD_DATE' => 'D06  Upd  Date',
            'D06_UPD_USER_ID' => 'D06  Upd  User  ID',
        ];
    }
}
