<?php
namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "SDP_TD05_DENPYO_COM".
 *
 * @property integer $D05_DEN_NO
 * @property string $D05_COM_CD
 * @property string $D05_NST_CD
 * @property integer $D05_COM_SEQ
 * @property string $D05_SURYO
 * @property integer $D05_TANKA
 * @property integer $D05_KINGAKU
 * @property string $D05_INP_DATE
 * @property string $D05_INP_USER_ID
 * @property string $D05_UPD_DATE
 * @property string $D05_UPD_USER_ID
 */
class Sdptd05denpyocom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SDP_TD05_DENPYO_COM';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['D05_DEN_NO', 'D05_COM_CD', 'D05_NST_CD', 'D05_COM_SEQ'], 'required'],
            [['D05_DEN_NO', 'D05_COM_SEQ', 'D05_TANKA', 'D05_KINGAKU'], 'integer'],
            [['D05_SURYO'], 'number'],
            [['D05_COM_CD'], 'string', 'max' => 6],
            [['D05_NST_CD'], 'string', 'max' => 3],
            [['D05_INP_USER_ID', 'D05_UPD_USER_ID'], 'string', 'max' => 20],
            [['D05_DEN_NO', 'D05_COM_CD', 'D05_NST_CD', 'D05_COM_SEQ'], 'unique', 'targetAttribute' => ['D05_DEN_NO', 'D05_COM_CD', 'D05_NST_CD', 'D05_COM_SEQ'], 'message' => 'The combination of D05  Den  No, D05  Com  Cd, D05  Nst  Cd and D05  Com  Seq has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'D05_DEN_NO' => 'D05  Den  No',
            'D05_COM_CD' => 'D05  Com  Cd',
            'D05_NST_CD' => 'D05  Nst  Cd',
            'D05_COM_SEQ' => 'D05  Com  Seq',
            'D05_SURYO' => 'D05  Suryo',
            'D05_TANKA' => 'D05  Tanka',
            'D05_KINGAKU' => 'D05  Kingaku',
            'D05_INP_DATE' => 'D05  Inp  Date',
            'D05_INP_USER_ID' => 'D05  Inp  User  ID',
            'D05_UPD_DATE' => 'D05  Upd  Date',
            'D05_UPD_USER_ID' => 'D05  Upd  User  ID',
        ];
    }

    private function getWhere($filters = [], $select = '*')
    {
        $query = new Query();
        $query->select($select)->from(static::tableName());

        if (isset($filters['D05_DEN_NO']) && $filters['D05_DEN_NO']) {
            $query->andwhere('D05_DEN_NO=:den_no', [':den_no' => $filters['D05_DEN_NO']]);
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
        $obj = new Sdptd05denpyocom();
        if ($id) {
            $obj = static::findOne($id);
        }

        $obj->attributes = $data;
        foreach ($obj->attributes as $k => $v) {
            $obj->{$k} = trim($v) != '' ? trim($v) : null;
        }

        $this->obj = $obj;
    }

    public function getData($filters = [], $select = '*')
    {
        $query = $this->getWhere($filters, $select);
        $query->orderBy('D05_COM_SEQ ASC');
        return $query->all();
    }

    public function saveDataMuti($insertData)
    {

        $columnNameArray = array_keys(current($insertData));
        $data = [];
        foreach ($insertData as $key => $row) {
            $data[] = array_values($row);
        }

        //$columnNameArray = array_keys($this->attributeLabels());
        $insertCount = Yii::$app->db->createCommand()
            ->batchInsert(self::tableName(), $columnNameArray, $data)
            ->execute();
        return $insertCount;
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

    public function deleteData($where)
    {
        if ($where) {
            return Sdptd05denpyocom::deleteAll($where);
        }

        return false;
    }

    public function getListName($product)
    {
        $sql = 'SELECT * FROM SDP_TM05_COM WHERE CONCAT(M05_COM_CD, M05_NST_CD) IN (' . trim($product, ',') . ')';
        return $this->getDB()->createCommand($sql)->queryAll();
    }
}
