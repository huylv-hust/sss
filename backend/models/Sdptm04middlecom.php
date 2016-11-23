<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SDP_TM04_MIDDLE_COM".
 *
 * @property integer $M04_KIND_COM_NO
 * @property integer $M04_LARGE_COM_NO
 * @property integer $M04_MIDDLE_COM_NO
 * @property string $M04_MIDDLE_COM_NAMEN
 * @property integer $M04_ORDER
 * @property string $M04_MEMO
 * @property string $M04_INP_DATE
 * @property string $M04_INP_USER_ID
 * @property string $M04_UPD_DATE
 * @property string $M04_UPD_USER_ID
 */
class Sdptm04middlecom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SDP_TM04_MIDDLE_COM';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['M04_KIND_COM_NO', 'M04_LARGE_COM_NO', 'M04_MIDDLE_COM_NO'], 'required'],
            [['M04_KIND_COM_NO', 'M04_LARGE_COM_NO', 'M04_MIDDLE_COM_NO', 'M04_ORDER'], 'integer'],
            [['M04_MIDDLE_COM_NAMEN'], 'string', 'max' => 100],
            [['M04_MEMO'], 'string', 'max' => 500],
            [['M04_INP_DATE', 'M04_UPD_DATE'], 'string', 'max' => 7],
            [['M04_INP_USER_ID', 'M04_UPD_USER_ID'], 'string', 'max' => 20],
            [['M04_KIND_COM_NO', 'M04_LARGE_COM_NO', 'M04_MIDDLE_COM_NO'], 'unique', 'targetAttribute' => ['M04_KIND_COM_NO', 'M04_LARGE_COM_NO', 'M04_MIDDLE_COM_NO'], 'message' => 'The combination of M04  Kind  Com  No, M04  Large  Com  No and M04  Middle  Com  No has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'M04_KIND_COM_NO' => 'M04  Kind  Com  No',
            'M04_LARGE_COM_NO' => 'M04  Large  Com  No',
            'M04_MIDDLE_COM_NO' => 'M04  Middle  Com  No',
            'M04_MIDDLE_COM_NAMEN' => 'M04  Middle  Com  Namen',
            'M04_ORDER' => 'M04  Order',
            'M04_MEMO' => 'M04  Memo',
            'M04_INP_DATE' => 'M04  Inp  Date',
            'M04_INP_USER_ID' => 'M04  Inp  User  ID',
            'M04_UPD_DATE' => 'M04  Upd  Date',
            'M04_UPD_USER_ID' => 'M04  Upd  User  ID',
        ];
    }
}
