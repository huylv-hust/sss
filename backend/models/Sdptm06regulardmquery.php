<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SDP_TM06_REGULAR_DM_QUERY".
 *
 * @property integer $M06_SEQ
 * @property string $M06_IDENTIFIER
 * @property string $M06_TAISYO_KBN
 * @property string $M06_TITLE
 * @property integer $M06_TAISYO_TERM
 * @property string $M06_TARGET_CUST
 * @property string $M06_HAN
 * @property string $M06_SS1
 * @property string $M06_SS2
 * @property integer $M06_KIND_COM_NO1
 * @property integer $M06_KIND_COM_NO2
 * @property integer $M06_KIND_COM_NO3
 * @property string $M06_COM_CD_FROM1
 * @property string $M06_NST_CD_FROM1
 * @property string $M06_COM_CD_TO1
 * @property string $M06_NST_CD_TO1
 * @property string $M06_COM_CD_FROM2
 * @property string $M06_NST_CD_FROM2
 * @property string $M06_COM_CD_TO2
 * @property string $M06_NST_CD_TO2
 * @property string $M06_COM_CD_FROM3
 * @property string $M06_NST_CD_FROM3
 * @property string $M06_COM_CD_TO3
 * @property string $M06_NST_CD_TO3
 * @property string $M06_COM_CD_FROM4
 * @property string $M06_NST_CD_FROM4
 * @property string $M06_COM_CD_TO4
 * @property string $M06_NST_CD_TO4
 * @property string $M06_COM_CD_FROM5
 * @property string $M06_NST_CD_FROM5
 * @property string $M06_COM_CD_TO5
 * @property string $M06_NST_CD_TO5
 * @property string $M06_JIKAI_SHAKEN_YM_FROM
 * @property string $M06_JIKAI_SHAKEN_YM_TO
 * @property string $M06_SHAKEN_GAITOU
 * @property string $M06_INP_DATE
 * @property string $M06_INP_USER_ID
 * @property string $M06_UPD_DATE
 * @property string $M06_UPD_USER_ID
 */
class Sdptm06regulardmquery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SDP_TM06_REGULAR_DM_QUERY';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['M06_SEQ', 'M06_TAISYO_KBN'], 'required'],
            [['M06_SEQ', 'M06_TAISYO_TERM', 'M06_KIND_COM_NO1', 'M06_KIND_COM_NO2', 'M06_KIND_COM_NO3'], 'integer'],
            [['M06_IDENTIFIER'], 'string', 'max' => 5],
            [['M06_TAISYO_KBN', 'M06_TARGET_CUST', 'M06_SHAKEN_GAITOU'], 'string', 'max' => 1],
            [['M06_TITLE'], 'string', 'max' => 256],
            [['M06_HAN'], 'string', 'max' => 500],
            [['M06_SS1', 'M06_SS2'], 'string', 'max' => 2000],
            [['M06_COM_CD_FROM1', 'M06_COM_CD_TO1', 'M06_COM_CD_FROM2', 'M06_COM_CD_TO2', 'M06_COM_CD_FROM3', 'M06_COM_CD_TO3', 'M06_COM_CD_FROM4', 'M06_COM_CD_TO4', 'M06_COM_CD_FROM5', 'M06_COM_CD_TO5', 'M06_JIKAI_SHAKEN_YM_FROM', 'M06_JIKAI_SHAKEN_YM_TO'], 'string', 'max' => 6],
            [['M06_NST_CD_FROM1', 'M06_NST_CD_TO1', 'M06_NST_CD_FROM2', 'M06_NST_CD_TO2', 'M06_NST_CD_FROM3', 'M06_NST_CD_TO3', 'M06_NST_CD_FROM4', 'M06_NST_CD_TO4', 'M06_NST_CD_FROM5', 'M06_NST_CD_TO5'], 'string', 'max' => 3],
            [['M06_INP_DATE', 'M06_UPD_DATE'], 'string', 'max' => 7],
            [['M06_INP_USER_ID', 'M06_UPD_USER_ID'], 'string', 'max' => 20],
            [['M06_SEQ'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'M06_SEQ' => 'M06  Seq',
            'M06_IDENTIFIER' => 'M06  Identifier',
            'M06_TAISYO_KBN' => 'M06  Taisyo  Kbn',
            'M06_TITLE' => 'M06  Title',
            'M06_TAISYO_TERM' => 'M06  Taisyo  Term',
            'M06_TARGET_CUST' => 'M06  Target  Cust',
            'M06_HAN' => 'M06  Han',
            'M06_SS1' => 'M06  Ss1',
            'M06_SS2' => 'M06  Ss2',
            'M06_KIND_COM_NO1' => 'M06  Kind  Com  No1',
            'M06_KIND_COM_NO2' => 'M06  Kind  Com  No2',
            'M06_KIND_COM_NO3' => 'M06  Kind  Com  No3',
            'M06_COM_CD_FROM1' => 'M06  Com  Cd  From1',
            'M06_NST_CD_FROM1' => 'M06  Nst  Cd  From1',
            'M06_COM_CD_TO1' => 'M06  Com  Cd  To1',
            'M06_NST_CD_TO1' => 'M06  Nst  Cd  To1',
            'M06_COM_CD_FROM2' => 'M06  Com  Cd  From2',
            'M06_NST_CD_FROM2' => 'M06  Nst  Cd  From2',
            'M06_COM_CD_TO2' => 'M06  Com  Cd  To2',
            'M06_NST_CD_TO2' => 'M06  Nst  Cd  To2',
            'M06_COM_CD_FROM3' => 'M06  Com  Cd  From3',
            'M06_NST_CD_FROM3' => 'M06  Nst  Cd  From3',
            'M06_COM_CD_TO3' => 'M06  Com  Cd  To3',
            'M06_NST_CD_TO3' => 'M06  Nst  Cd  To3',
            'M06_COM_CD_FROM4' => 'M06  Com  Cd  From4',
            'M06_NST_CD_FROM4' => 'M06  Nst  Cd  From4',
            'M06_COM_CD_TO4' => 'M06  Com  Cd  To4',
            'M06_NST_CD_TO4' => 'M06  Nst  Cd  To4',
            'M06_COM_CD_FROM5' => 'M06  Com  Cd  From5',
            'M06_NST_CD_FROM5' => 'M06  Nst  Cd  From5',
            'M06_COM_CD_TO5' => 'M06  Com  Cd  To5',
            'M06_NST_CD_TO5' => 'M06  Nst  Cd  To5',
            'M06_JIKAI_SHAKEN_YM_FROM' => 'M06  Jikai  Shaken  Ym  From',
            'M06_JIKAI_SHAKEN_YM_TO' => 'M06  Jikai  Shaken  Ym  To',
            'M06_SHAKEN_GAITOU' => 'M06  Shaken  Gaitou',
            'M06_INP_DATE' => 'M06  Inp  Date',
            'M06_INP_USER_ID' => 'M06  Inp  User  ID',
            'M06_UPD_DATE' => 'M06  Upd  Date',
            'M06_UPD_USER_ID' => 'M06  Upd  User  ID',
        ];
    }
}
