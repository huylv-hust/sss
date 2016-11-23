<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SDP_TM02_KIND_COM".
 *
 * @property integer $M02_KIND_COM_NO
 * @property string $M02_KIND_COM_NAMEN
 * @property integer $M02_ORDER
 * @property string $M02_MEMO
 * @property string $M02_INP_DATE
 * @property string $M02_INP_USER_ID
 * @property string $M02_UPD_DATE
 * @property string $M02_UPD_USER_ID
 */
class Sdptm02kindcom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SDP_TM02_KIND_COM';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['M02_KIND_COM_NO'], 'required'],
            [['M02_KIND_COM_NO', 'M02_ORDER'], 'integer'],
            [['M02_KIND_COM_NAMEN'], 'string', 'max' => 100],
            [['M02_MEMO'], 'string', 'max' => 500],
            [['M02_INP_DATE', 'M02_UPD_DATE'], 'string', 'max' => 7],
            [['M02_INP_USER_ID', 'M02_UPD_USER_ID'], 'string', 'max' => 20],
            [['M02_KIND_COM_NO'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'M02_KIND_COM_NO' => 'M02  Kind  Com  No',
            'M02_KIND_COM_NAMEN' => 'M02  Kind  Com  Namen',
            'M02_ORDER' => 'M02  Order',
            'M02_MEMO' => 'M02  Memo',
            'M02_INP_DATE' => 'M02  Inp  Date',
            'M02_INP_USER_ID' => 'M02  Inp  User  ID',
            'M02_UPD_DATE' => 'M02  Upd  Date',
            'M02_UPD_USER_ID' => 'M02  Upd  User  ID',
        ];
    }
}
