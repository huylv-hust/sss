<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "RM07_SS_MEMBER_RIREKI".
 *
 * @property string $M07_KAIIN_CD
 * @property string $M07_CARD_KBN
 * @property string $M07_CARD_NO
 * @property string $M07_SORT
 * @property string $M07_TEI_CARD_KBN1
 * @property string $M07_TEI_CARD_NO1
 * @property string $M07_TEI_CARD_KBN2
 * @property string $M07_TEI_CARD_NO2
 * @property string $M07_TEI_CARD_KBN3
 * @property string $M07_TEI_CARD_NO3
 * @property string $M07_TEI_CARD_KBN4
 * @property string $M07_TEI_CARD_NO4
 * @property string $M07_TEI_CARD_KBN5
 * @property string $M07_TEI_CARD_NO5
 * @property string $M07_OPE_ID
 * @property string $M07_INP_YMDHMS
 * @property string $M07_UPD_YMDHMS
 */
class Rm07ssmemberrireki extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'RM07_SS_MEMBER_RIREKI';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['M07_KAIIN_CD'], 'required'],
            [['M07_KAIIN_CD'], 'string', 'max' => 12],
            [['M07_CARD_KBN', 'M07_SORT', 'M07_TEI_CARD_KBN1', 'M07_TEI_CARD_KBN2', 'M07_TEI_CARD_KBN3', 'M07_TEI_CARD_KBN4', 'M07_TEI_CARD_KBN5'], 'string', 'max' => 2],
            [['M07_CARD_NO'], 'string', 'max' => 16],
            [['M07_TEI_CARD_NO1', 'M07_TEI_CARD_NO2', 'M07_TEI_CARD_NO3', 'M07_TEI_CARD_NO4', 'M07_TEI_CARD_NO5'], 'string', 'max' => 30],
            [['M07_OPE_ID'], 'string', 'max' => 20],
            [['M07_INP_YMDHMS', 'M07_UPD_YMDHMS'], 'string', 'max' => 14]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'M07_KAIIN_CD' => 'M07  Kaiin  Cd',
            'M07_CARD_KBN' => 'M07  Card  Kbn',
            'M07_CARD_NO' => 'M07  Card  No',
            'M07_SORT' => 'M07  Sort',
            'M07_TEI_CARD_KBN1' => 'M07  Tei  Card  Kbn1',
            'M07_TEI_CARD_NO1' => 'M07  Tei  Card  No1',
            'M07_TEI_CARD_KBN2' => 'M07  Tei  Card  Kbn2',
            'M07_TEI_CARD_NO2' => 'M07  Tei  Card  No2',
            'M07_TEI_CARD_KBN3' => 'M07  Tei  Card  Kbn3',
            'M07_TEI_CARD_NO3' => 'M07  Tei  Card  No3',
            'M07_TEI_CARD_KBN4' => 'M07  Tei  Card  Kbn4',
            'M07_TEI_CARD_NO4' => 'M07  Tei  Card  No4',
            'M07_TEI_CARD_KBN5' => 'M07  Tei  Card  Kbn5',
            'M07_TEI_CARD_NO5' => 'M07  Tei  Card  No5',
            'M07_OPE_ID' => 'M07  Ope  ID',
            'M07_INP_YMDHMS' => 'M07  Inp  Ymdhms',
            'M07_UPD_YMDHMS' => 'M07  Upd  Ymdhms',
        ];
    }
}
