<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SDP_TM07_KIND_DM".
 *
 * @property integer $M07_KIND_DM_NO
 * @property string $M07_KIND_DM_NAMEN
 * @property string $M07_MEMO
 * @property string $M07_INP_DATE
 * @property string $M07_INP_USER_ID
 * @property string $M07_UPD_DATE
 * @property string $M07_UPD_USER_ID
 */
class Sdptm07kinddm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SDP_TM07_KIND_DM';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['M07_KIND_DM_NO'], 'required'],
            [['M07_KIND_DM_NO'], 'integer'],
            [['M07_KIND_DM_NAMEN'], 'string', 'max' => 100],
            [['M07_MEMO'], 'string', 'max' => 500],
            [['M07_INP_DATE', 'M07_UPD_DATE'], 'string', 'max' => 7],
            [['M07_INP_USER_ID', 'M07_UPD_USER_ID'], 'string', 'max' => 20],
            [['M07_KIND_DM_NO'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'M07_KIND_DM_NO' => 'M07  Kind  Dm  No',
            'M07_KIND_DM_NAMEN' => 'M07  Kind  Dm  Namen',
            'M07_MEMO' => 'M07  Memo',
            'M07_INP_DATE' => 'M07  Inp  Date',
            'M07_INP_USER_ID' => 'M07  Inp  User  ID',
            'M07_UPD_DATE' => 'M07  Upd  Date',
            'M07_UPD_USER_ID' => 'M07  Upd  User  ID',
        ];
    }
}
