<?php

namespace app\models;

use yii\base\Exception;
use yii\db\Expression;
use yii\db\Query;
use Yii;
use common\components\DatabaseLight;

/**
 * This is the model class for table "SDP_TM05_COM".
 *
 * @property string $M05_COM_CD
 * @property string $M05_NST_CD
 * @property integer $M05_KIND_COM_NO
 * @property integer $M05_LARGE_COM_NO
 * @property integer $M05_MIDDLE_COM_NO
 * @property integer $M05_KIND_DM_NO
 * @property string $M05_COM_NAMEN
 * @property integer $M05_LIST_PRICE
 * @property integer $M05_ORDER
 * @property string $M05_MEMO
 * @property string $M05_INP_DATE
 * @property string $M05_INP_USER_ID
 * @property string $M05_UPD_DATE
 * @property string $M05_UPD_USER_ID
 */
class Sdptm05com extends \yii\db\ActiveRecord
{
    public $header = [
        '商品コード',
        '荷姿コード',
        'M05_KIND_COM_NO',
        'M05_LARGE_COM_NO',
        'M05_MIDDLE_COM_NO',
        'DM分類コード',
        '商品名',
        '参考価格',
        'M05_ORDER',
        'M05_MEMO'
    ];

    public $error_import = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SDP_TM05_COM';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['M05_COM_CD', 'M05_NST_CD', 'M05_KIND_COM_NO', 'M05_LARGE_COM_NO', 'M05_MIDDLE_COM_NO'], 'required'],
            [
                [
                    'M05_KIND_COM_NO',
                    'M05_LARGE_COM_NO',
                    'M05_MIDDLE_COM_NO',
                    'M05_KIND_DM_NO',
                    'M05_LIST_PRICE',
                    'M05_ORDER'
                ],
                'integer'
            ],
            [['M05_COM_CD'], 'string', 'max' => 6],
            [['M05_NST_CD'], 'string', 'max' => 3],
            [['M05_COM_NAMEN'], 'string', 'max' => 100],
            [['M05_MEMO'], 'string', 'max' => 500],
            [['M05_INP_USER_ID', 'M05_UPD_USER_ID'], 'string', 'max' => 20],
            [
                ['M05_COM_CD', 'M05_NST_CD'],
                'unique', 'targetAttribute' => ['M05_COM_CD', 'M05_NST_CD'],
                'message' => 'The combination of M05  Com  Cd and M05  Nst  Cd has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'M05_COM_CD' => 'M05  Com  Cd',
            'M05_NST_CD' => 'M05  Nst  Cd',
            'M05_KIND_COM_NO' => 'M05  Kind  Com  No',
            'M05_LARGE_COM_NO' => 'M05  Large  Com  No',
            'M05_MIDDLE_COM_NO' => 'M05  Middle  Com  No',
            'M05_KIND_DM_NO' => 'M05  Kind  Dm  No',
            'M05_COM_NAMEN' => 'M05  Com  Namen',
            'M05_LIST_PRICE' => 'M05  List  Price',
            'M05_ORDER' => 'M05  Order',
            'M05_MEMO' => 'M05  Memo',
            'M05_INP_DATE' => 'M05  Inp  Date',
            'M05_INP_USER_ID' => 'M05  Inp  User  ID',
            'M05_UPD_DATE' => 'M05  Upd  Date',
            'M05_UPD_USER_ID' => 'M05  Upd  User  ID',
        ];
    }

    public function setData($data = [])
    {
        foreach ($data as $k => $v) {
            $data[$k] = trim($v) != '' ? trim($v) : null;
        }

        return $data;
    }

    /**
     * validate import data
     * @param $line
     * @param array $data
     */
    public function validateImport($line, $data = [])
    {
        if (count($data) != 10) {
            $this->error_import[] = $line . '行目:CSVファイルのフォーマットが正しくありません';
        } else {
            if ($data[0] == '') {
                $this->error_import[] = $this->setMessageRequire($line, '商品コード');
            }

            if ($data[1] == '') {
                $this->error_import[] = $this->setMessageRequire($line, '荷姿コード');
            }

            if ($data[2] == '') {
                $this->error_import[] = $this->setMessageRequire($line, 'M05_KIND_COM_NO');
            }

            if ($data[3] == '') {
                $this->error_import[] = $this->setMessageRequire($line, 'M05_LARGE_COM_NO');
            }

            if ($data[4] == '') {
                $this->error_import[] = $this->setMessageRequire($line, 'M05_MIDDLE_COM_NO');
            }

            if (mb_strlen($data[0]) > 0 && !preg_match('/^[0-9]{6}$/', $data[0])) {
                $this->error_import[] = $this->setMessageEqualLength($line, '商品コード', 6);
            }

            if (mb_strlen($data[1]) > 0 && !preg_match('/^[0-9]{3}$/', $data[1])) {
                $this->error_import[] = $this->setMessageEqualLength($line, '荷姿コード', 3);
            }

            if (mb_strlen($data[2]) > 3 || (mb_strlen($data[2]) > 0 && !preg_match('/^[0-9]+$/', $data[2]))) {
                $this->error_import[] = $this->setMessageOverLength($line, 'M05_KIND_COM_NO', 3);
            }

            if (mb_strlen($data[3]) > 3 || (mb_strlen($data[3]) > 0 && !preg_match('/^[0-9]+$/', $data[3]))) {
                $this->error_import[] = $this->setMessageOverLength($line, 'M05_LARGE_COM_NO', 3);
            }

            if (mb_strlen($data[4]) > 3 || (mb_strlen($data[4]) > 0 && !preg_match('/^[0-9]+$/', $data[4]))) {
                $this->error_import[] = $this->setMessageOverLength($line, 'M05_MIDDLE_COM_NO', 3);
            }

            if (mb_strlen($data[5]) > 3 || (mb_strlen($data[5]) > 0 && !preg_match('/^[0-9]+$/', $data[5]))) {
                $this->error_import[] = $this->setMessageOverLength($line, 'DM分類コード', 3);
            }

            if (mb_strlen($data[6]) > 0 && mb_strlen($data[6]) > 50) {
                $this->error_import[] = $this->setMessageOverLength($line, '商品名', 50);
            }

            if ((mb_strlen($data[7]) > 0 && !preg_match('/^[0-9]+$/', $data[7])) || mb_strlen($data[7]) > 10) {
                $this->error_import[] = $this->setMessageOverLength($line, '参考価格', 10);
            }

            if (mb_strlen($data[8]) > 3 || (mb_strlen($data[8]) > 0 && !preg_match('/^[0-9]+$/', $data[8]))) {
                $this->error_import[] = $this->setMessageOverLength($line, 'M05_ORDER', 3);
            }

            if (mb_strlen($data[9]) > 250) {
                $this->error_import[] = $this->setMessageOverLength($line, 'M05_MEMO', 250);
            }
        }
    }

    private function setMessageEqualLength($line, $column, $length)
    {
        return $line . '行目:' . $column . 'が' . $length . '桁の半角数字で入力して下さい';
    }

    private function setMessageOverLength($line, $column, $length)
    {
        return $line . '行目:' . $column . 'が' . $length . '文字以内を入力して下さい';
    }

    private function setMessageRequire($line, $column)
    {
        return $line . '行目:' . $column . 'を入力して下さい。';
    }

    /**
     * save data import
     * @param $file
     * @return array
     * @throws \yii\db\Exception
     */
    public function saveImport($file)
    {
        $lightdb = DatabaseLight::singleton();

        $login_info = Yii::$app->session->get('login_info');
        $header = fgetcsv($file);
        if (count($header) != 10) {
            $this->error_import[] = '1行目:CSVファイルのフォーマットが正しくありません';
        }
        $line = 2;
        $insert_data = [];
        $count_error_update = 0;
        $count_error_insert = 0;

        //update
        $update_data['update'] = " M05_KIND_COM_NO=:M05_KIND_COM_NO, M05_LARGE_COM_NO=:M05_LARGE_COM_NO"
            . ", M05_MIDDLE_COM_NO=:M05_MIDDLE_COM_NO, M05_KIND_DM_NO=:M05_KIND_DM_NO"
            . ", M05_COM_NAMEN=:M05_COM_NAMEN, M05_LIST_PRICE=:M05_LIST_PRICE"
            . ", M05_ORDER=:M05_ORDER, M05_MEMO=:M05_MEMO, M05_UPD_DATE=:M05_UPD_DATE"
            . ", M05_UPD_USER_ID=:M05_UPD_USER_ID";
        $update_data['where'] = " M05_COM_CD=:M05_COM_CD and M05_NST_CD=:M05_NST_CD";
        $query = 'update ' . self::tableName() . ' set' . $update_data['update'] . ' where ' . $update_data['where'];

        $sthUpdate = $lightdb->prepare($query);

        //insert
        $insert_data['data'] = ":M05_COM_CD,:M05_NST_CD,:M05_KIND_COM_NO,:M05_LARGE_COM_NO,:M05_MIDDLE_COM_NO,:M05_KIND_DM_NO"
            . ",:M05_COM_NAMEN,:M05_LIST_PRICE,:M05_ORDER,:M05_MEMO,:M05_INP_DATE,:M05_INP_USER_ID"
            . ",:M05_UPD_DATE,:M05_UPD_USER_ID";
        $query = "insert into " . self::tableName() . " values (" . $insert_data['data'] . ")";
        $sthInsert = $lightdb->prepare($query);

        $savedTraceLevel = Yii::$app->log->traceLevel;
        Yii::$app->log->traceLevel = 0;

        $lightdb->begin();
        try {
            while (($data = fgetcsv($file)) !== false) {
                $this->validateImport($line, $data);
                if (count($data) == 10) {
                    if ($obj = self::findOne(['M05_COM_CD' => $data[0], 'M05_NST_CD' => $data[1]])) {
                        $result = $lightdb->execute($sthUpdate, [
                            ':M05_KIND_COM_NO' => $data[2],
                            ':M05_LARGE_COM_NO' => $data[3],
                            ':M05_MIDDLE_COM_NO' => $data[4],
                            ':M05_KIND_DM_NO' => $data[5],
                            ':M05_COM_NAMEN' => $data[6],
                            ':M05_LIST_PRICE' => $data[7],
                            ':M05_ORDER' => $data[8],
                            ':M05_MEMO' => $data[9],
                            ':M05_UPD_DATE' => date('y-M-d'),
                            ':M05_UPD_USER_ID' => $login_info['M50_USER_ID'],
                            ':M05_COM_CD' => $data[0],
                            ':M05_NST_CD' => $data[1]
                        ]);
                        if (!$result) {
                            $count_error_update++;
                        }

                    } else {
                        $result = $lightdb->execute($sthInsert, [
                            ':M05_COM_CD' => $data[0],
                            ':M05_NST_CD' => $data[1],
                            ':M05_KIND_COM_NO' => $data[2],
                            ':M05_LARGE_COM_NO' => $data[3],
                            ':M05_MIDDLE_COM_NO' => $data[4],
                            ':M05_KIND_DM_NO' => $data[5],
                            ':M05_COM_NAMEN' => $data[6],
                            ':M05_LIST_PRICE' => $data[7],
                            ':M05_ORDER' => $data[8],
                            ':M05_MEMO' => $data[9],
                            ':M05_INP_DATE' => date('y-M-d'),
                            ':M05_INP_USER_ID' => $login_info['M50_USER_ID'],
                            ':M05_UPD_DATE' => date('y-M-d'),
                            ':M05_UPD_USER_ID' => $login_info['M50_USER_ID']
                        ]);
                        if (!$result) {
                            $count_error_insert++;
                        }
                    }
                }
                $line++;
            }

            if ($count_error_update > 0 || $count_error_insert > 0) {
                $lightdb->rollback();
                return ['insert' => false, 'error' => $this->error_import];
            }

            if (!empty($this->error_import)) {
                $lightdb->rollback();
                return ['insert' => false, 'error' => $this->error_import];
            }

            $lightdb->commit();
        } catch (Exception $e) {
            $lightdb->rollback();
            $this->error_import[] = 'インポートができません';
            return ['insert' => false, 'error' => $this->error_import];
        }

        Yii::$app->log->traceLevel = $savedTraceLevel;

        return ['insert' => true, 'error' => $this->error_import];
    }

    /**
     * @param array $filters
     * @param string $select
     * @return Query
     */
    private function getWhere($filters = [], $select = '*')
    {

        $query = new Query();
        $query->select($select)->from(static::tableName());
		$query->andwhere(['>',  'CONCAT(M05_COM_CD,M05_NST_CD)', '020000']);

		if (isset($filters['M05_COM_CD_IN']) && $filters['M05_COM_CD_IN'] != '') {
            $query->andwhere(['IN', 'M05_COM_CD', $filters['M05_COM_CD_IN']]);
        }

        if (isset($filters['M05_COM_CD']) && $filters['M05_COM_CD'] != '') {
            $query->andwhere(['LIKE', 'M05_COM_CD', $filters['M05_COM_CD']]);
        }

        if (isset($filters['code']) && $filters['code'] != '') {
            $query->andwhere(['LIKE', 'CONCAT(M05_COM_CD,M05_NST_CD)', $filters['code']]);
        }

        if (isset($filters['M05_COM_NAMEN']) && $filters['M05_COM_NAMEN'] != '') {
            $query->andwhere(['LIKE', 'M05_COM_NAMEN', $filters['M05_COM_NAMEN']]);
        }

        if (isset($filters['M05_NST_CD']) && $filters['M05_NST_CD'] != '') {
            $query->andwhere(['LIKE', 'M05_NST_CD', $filters['M05_NST_CD']]);
        }

        if (isset($filters['in']) && count($filters['in'])) {
            $query->andwhere(['IN', 'M05_KIND_DM_NO', $filters['in']]);
        }

		if (isset($filters['in_concat']) && count($filters['in_concat'])) {
            $query->andwhere(['IN', 'CONCAT(M05_COM_CD,M05_NST_CD)', $filters['in_concat']]);
        }

        if (isset($filters['not_in']) && count($filters['not_in'])) {
			$query->andwhere(['NOT IN', 'CONCAT(M05_COM_CD,M05_NST_CD)',  Yii::$app->params['search_cate']]);
            $query->andwhere('M05_KIND_DM_NO NOT IN (' . implode(',', $filters['not_in']) . ') OR M05_KIND_DM_NO IS NULL');
        }

        if (isset($filters['offset']) && $filters['offset']) {
            $query->offset($filters['offset']);
        }

        if (isset($filters['limit']) && $filters['limit']) {
            $query->limit($filters['limit']);
        }

        return $query;
    }

    /**
     * @return mixed
     */
    public function saveData()
    {
        return $this->obj->save();
    }

    /**
     * @param array $filters
     * @param string $select
     * @return array
     */
    public function getData($filters = [], $select = '*')
    {
        $query = $this->getWhere($filters, $select);
        $query->orderBy('M05_COM_CD ASC');
        return $query->all();
    }

    /**
     * @param $filters
     * @return int|string
     */
    public function coutData($filters)
    {
        $query = $this->getWhere($filters);
        return $query->count();
    }
}
