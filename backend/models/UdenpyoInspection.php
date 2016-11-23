<?php

namespace app\models;

use backend\components\api;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\Html;

class UdenpyoInspection
{
    private $fee_basic;
    private $fee_registration;
    private $parent_discount;
    private $discount_package;
    private $discount;
    private $parent_comment;
    private $comments;
    private $tm08_sagyosya;
    private $tm01_sagyo;
    private $customer;
    private $car;
    private $denpyo;
    private $denpyoSagyo;
    private $denpyoCom;
    private $tm05Com;
    private $tm08Sagyosya;
    private $tm03LagreCom;
    private $tm01Sagyo;
    private $tm09WarrantyNo;
    private $denpyoInspection;

    public function __construct()
    {
        $this->car = new Sdptd02car();
        $this->fee_basic = new FeeBasic();
        $this->fee_registration = new FeeRegistration();
        $this->parent_discount = new ParentDiscount();
        $this->discount_package = new DiscountPackages();
        $this->discount = new Discounts();
        $this->parent_comment = new ParentComment();
        $this->comments = new Comment();
        $this->tm08_sagyosya = new Sdptm08sagyosya();
        $this->tm01_sagyo = new Sdptm01sagyo();
        $this->customer = new Sdptd01customer();
        $this->denpyo = new Sdptd03denpyo();
        $this->denpyoSagyo = new Sdptd04denpyosagyo();
        $this->denpyoCom = new Sdptd05denpyocom();
        $this->tm05Com = new Sdptm05com();
        $this->tm08Sagyosya = new Sdptm08sagyosya();
        $this->tm03LagreCom = new Sdptm03largecom();
        $this->tm01Sagyo = new Sdptm01sagyo();
        $this->tm09WarrantyNo = new Sdptm09warrantyno();
        $this->denpyoInspection = new DenpyoInspection();
    }

    /**
     * convert data of customer
     * @param $customer_cookie
     * @return array
     */
    public function convertDataCustomerApi($customer_cookie)
    {
        if (isset($customer_cookie['D01_CUST_NO']) && $customer_cookie['D01_CUST_NO']) {
            $result = $customer_cookie;
            return $result;
        }

        if ($customer_cookie['type_redirect'] == 1) {
            $result = [
                'D01_KAIIN_CD' => $customer_cookie['member_kaiinCd'],
                'D01_CUST_NAMEN' => $customer_cookie['member_kaiinName'],
                'D01_CUST_NAMEK' => $customer_cookie['member_kaiinKana'],
                'D01_TEL_NO' => $customer_cookie['member_telNo1'],
                'D01_MOBTEL_NO' => $customer_cookie['member_telNo2'],
                'D01_ADDR' => $customer_cookie['member_address'],
                'D01_KAKE_CARD_NO' => '',
                'D01_YUBIN_BANGO' => $customer_cookie['member_yuubinBangou'],
                'D01_SS_CD' => $customer_cookie['member_ssCode'],
                'D01_NOTE' => '',
            ];
        } elseif ($customer_cookie['type_redirect'] == 2) {
            $result = $customer_cookie;
        } else {
            $result = [
                'D01_CUST_NO' => '',
                'D01_KAIIN_CD' => '',
                'D01_CUST_NAMEN' => '',
                'D01_CUST_NAMEK' => '',
                'D01_TEL_NO' => '',
                'D01_MOBTEL_NO' => '',
                'D01_ADDR' => '',
                'D01_KAKE_CARD_NO' => '',
                'D01_YUBIN_BANGO' => '',
                'D01_SS_CD' => '',
                'D01_NOTE' => '',
            ];
        }
        return $result;
    }

    /**
     * @param $ss_cd
     * @return array
     */
    public function getSagyosyaBySSCD($ss_cd)
    {
        $result = [
            'jyug_cd' => ['' => ''],
            'name' => ['' => '']
        ];
        $tm08_sagyosya = $this->tm08_sagyosya->getData(['M08_SS_CD' => $ss_cd]);
        foreach ($tm08_sagyosya as $tmp) {
            $result['jyug_cd'][$tmp['M08_JYUG_CD']] = $tmp['M08_NAME_SEI'] . $tmp['M08_NAME_MEI'];
            $result['name'][$tmp['M08_NAME_SEI'] . '[]' . $tmp['M08_NAME_MEI']] = $tmp['M08_NAME_SEI'] . $tmp['M08_NAME_MEI'];
        }
        return $result;
    }

    /**
     * convert data of car
     * @param $data
     * @return array
     */
    public function convertDataCarApi($data)
    {
        if (empty($data)) {
            return [
                'D02_CUST_NO' => '',
                'D02_CAR_SEQ' => '',
                'D02_CAR_NAMEN' => '',
                'D02_JIKAI_SHAKEN_YM' => '',
                'D02_METER_KM' => '',
                'D02_SYAKEN_CYCLE' => '',
                'D02_RIKUUN_NAMEN' => '',
                'D02_CAR_ID' => '',
                'D02_HIRA' => '',
                'D02_CAR_NO' => '',
                'D02_INP_DATE' => new Expression("CURRENT_DATE"),
                'D02_INP_USER_ID' => '',
                'D02_UPD_DATE' => new Expression("CURRENT_DATE"),
                'D02_UPD_USER_ID' => '',
                'D02_MAKER_CD' => '',
                'D02_MODEL_CD' => '',
                'D02_SHONENDO_YM' => '',
                'D02_TYPE_CD' => '',
                'D02_GRADE_CD' => '',
                'ApiCar' => [
                    'car_haikiRyou' => '0',
                    'car_syataiBangou' => '',
                    'car_ruibetuKbn' => '1',
                    'car_bodyColor' => '',
                    'car_styleBangou' => '',
                    'car_totalJyuuryou' => '0',
                    'car_yunyu' => '',
                    'car_gendokiStyle' => '',
                    'car_maxSekisai' => '0',
                    'car_modelNamen' => '',
                    'car_jyuuryou' => '0',
                    'car_style' => '',
                    'car_mission' => '',
                    'car_handler' => '',
                ]
            ];
        }

        $cars = [];
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $count = count($v);
                for ($i = 0; $i < $count; ++$i) {
                    $cars[$i][$k] = $v[$i];
                }
            } else {
                $info[$k] = $v;
            }
        }

        $arr = [];
        foreach ($cars as $car) {
            $arr[] = [
                'D02_CUST_NO' => '',
                'D02_CAR_SEQ' => $car['car_carSeq'],
                'D02_CAR_NAMEN' => $car['car_modelNamen'] ? $car['car_modelNamen'] : $car['car_carName'],
                'D02_JIKAI_SHAKEN_YM' => $car['car_jikaiSyakenYmd'],
                'D02_METER_KM' => $car['car_meterKm'],
                'D02_SYAKEN_CYCLE' => $car['car_syakenCycle'],
                'D02_RIKUUN_NAMEN' => $car['car_riunJimusyoName'],
                'D02_CAR_ID' => $car['car_syubetu'],
                'D02_HIRA' => $car['car_hiragana'],
                'D02_CAR_NO' => $car['car_carNo'],
                'D02_INP_DATE' => new Expression("CURRENT_DATE"),
                'D02_INP_USER_ID' => '',
                'D02_UPD_DATE' => new Expression("CURRENT_DATE"),
                'D02_UPD_USER_ID' => '',
                'D02_MAKER_CD' => $car['car_makerCd'],
                'D02_MODEL_CD' => $car['car_modelCd'],
                'D02_SHONENDO_YM' => $car['car_syoNendoInsYmd'],
                'D02_TYPE_CD' => $car['car_typeCd'],
                'D02_GRADE_CD' => $car['car_gradeCd'],
                'ApiCar' => [
                    'car_haikiRyou' => $car['car_haikiRyou'],
                    'car_syataiBangou' => $car['car_syataiBangou'],
                    'car_ruibetuKbn' => $car['car_ruibetuKbn'],
                    'car_bodyColor' => $car['car_bodyColor'],
                    'car_styleBangou' => $car['car_styleBangou'],
                    'car_totalJyuuryou' => $car['car_totalJyuuryou'],
                    'car_yunyu' => $car['car_yunyu'],
                    'car_gendokiStyle' => $car['car_gendokiStyle'],
                    'car_maxSekisai' => $car['car_maxSekisai'],
                    'car_carName' => $car['car_carName'],
                    'car_jyuuryou' => $car['car_jyuuryou'],
                    'car_style' => $car['car_style'],
                    'car_mission' => $car['car_mission'],
                    'car_handler' => $car['car_handler'],
                    'carLength' => $info['carLength']
                ]
            ];
        }

        return $arr;
    }

    /**
     * convert data to api
     * @param $data
     * @return array
     */
    public function convertDataCarToApi($data)
    {
        return [
            'car_carSeq' => $data['D02_CAR_SEQ'],
            'car_carName' => $data['D02_CAR_NAMEN'],
            'car_jikaiSyakenYmd' => $data['D02_JIKAI_SHAKEN_YM'],
            'car_meterKm' => $data['D02_METER_KM'],
            'car_syakenCycle' => $data['D02_SYAKEN_CYCLE'],
            'car_riunJimusyoName' => $data['D02_RIKUUN_NAMEN'],
            'car_hiragana' => $data['D02_HIRA'],
            'car_carNo' => $data['D02_CAR_NO'],
            'car_syubetu' => $data['D02_CAR_ID'],
            'car_makerCd' => $data['D02_MAKER_CD'],
            'car_modelCd' => $data['D02_MODEL_CD'],
            'car_syoNendoInsYmd' => $data['D02_SHONENDO_YM'],
            'car_typeCd' => $data['D02_TYPE_CD'],
            'car_gradeCd' => $data['D02_GRADE_CD'],
        ];
    }

    /**
     * @param $custNo
     * @param $data
     * @return array
     * @throws Exception
     */
    public function updateCar($custNo, $data)
    {
        $transaction = $this->car->getDb()->beginTransaction();
        try {
            $this->car->deleteData('D02_CUST_NO = ' . $custNo);
            if ($this->car->saveDataMuti($data) > 0) {
                $transaction->commit();
                return ['result' => 1];
            }
            $transaction->rollBack();
            return ['result' => 0];
        } catch (Exception $e) {
            $transaction->rollBack();
            return ['result' => 0];
        }
    }

    /**
     * @return array
     */
    public function getDataFeeBasic()
    {
        $fee_basic = $this->fee_basic->getData();
        $result = [
            'select' => ['' => ''],
            'value' => []
        ];
        foreach ($fee_basic as $k => $v) {
            $result['select'][$v['ID']] = $v['NAME'];
            $result['value'][$v['ID']] = $v['VALUE'];
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getDataFeeRegistration()
    {
        $fee_registration = $this->fee_registration->getData();
        $result = [
            'select' => ['' => ''],
            'value' => []
        ];
        foreach ($fee_registration as $k => $v) {
            $result['select'][$v['ID']] = $v['NAME'];
            $result['value'][$v['ID']]['WEIGHT_TAX'] = $v['WEIGHT_TAX'];
            $result['value'][$v['ID']]['MANDATORY_INSURANCE'] = $v['MANDATORY_INSURANCE'];
            $result['value'][$v['ID']]['STAMP_FEE'] = $v['STAMP_FEE'];
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getDataParentDiscount()
    {
        $parent_discount = $this->parent_discount->getData();
        $result = ['' => ''];
        foreach ($parent_discount as $k => $v) {
            $result[$v['ID']] = $v['NAME'];
        }
        return $result;
    }

    /**
     * @param $parent_discount_id
     * @return array
     */
    public function getDataDiscount($parent_discount_id)
    {
        $result = [];
        $parent_discount_id = !$parent_discount_id ? -1 : $parent_discount_id;
        $packages = $this->discount_package->getData(['PARENT_DISCOUNT_ID' => $parent_discount_id], 'ID,NAME');
        foreach ($packages as $k => $v) {
            $discount = $this->discount->getData(['DISCOUNT_PACKAGES_ID' => $v['ID']], 'ID,VALUE');
            $result[$v['ID']]['NAME'] = $v['NAME'];
            $result[$v['ID']]['type'] = 'checkbox';
            if (count($discount) > 1) {
                $result[$v['ID']]['type'] = 'select';
            }
            foreach ($discount as $discount_key => $discount_value) {
                $result[$v['ID']]['discount'][$discount_value['ID']] = $discount_value['VALUE'];
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getDataParentComment()
    {
        $parent_comment = $this->parent_comment->getData([], 'ID,NAME');
        $result = ['' => ''];
        foreach ($parent_comment as $k => $v) {
            $result[$v['ID']] = $v['NAME'];
        }
        return $result;
    }

    /**
     * @param $parent_comment_id
     * @return array
     */
    public function getListComment($parent_comment_id)
    {
        $result = [];
        $parent_comment_id = !$parent_comment_id ? -1 : $parent_comment_id;
        $comments = $this->comments->getData(['PARENT_COMMENT_ID' => $parent_comment_id], 'ID,CONTENT');
        foreach ($comments as $k => $v) {
            $result[$v['ID']] = $v['CONTENT'];
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getListWork()
    {
        return $this->tm01_sagyo->getData();
    }
    /**
     *
     * @param type $dataDenpyo // denpyo
     * @param type $dataDenpyoSagyo //  type work
     * @param type $dataCus // data cus
     * @param type $dataDenpyoCom // product
     * @param type $denpyoNo
     * @return boolean
     */
    /**
     * @param $obj
     * @param $where
     * @return mixed
     */
    public function deleteDataObj($obj, $where)
    {
        return $this->$obj->deleteData($where);
    }

    /**
     * @param $dataDenpyo
     * @param $dataDenpyoSagyo
     * @param $dataCus
     * @param $dataDenpyoCom
     * @param $denpyoNo
     * @return bool|int
     */
    public function saveDenpyo($dataDenpyo, $dataDenpyoSagyo, $dataCus, $dataDenpyoCom, $denpyoNo)
    {
        $checkSuccess = false;
        $this->denpyo->setData($dataDenpyo, $denpyoNo);
        $res = $this->denpyo->saveData();

        if ($res) {
            $this->customer->setData($dataCus, $dataCus['D01_CUST_NO']);
            $this->customer->saveData();
            if (count($dataDenpyoSagyo)) {
                $this->deleteDataObj('denpyoSagyo', 'D04_DEN_NO = ' . $dataDenpyo['D03_DEN_NO']);
                $checkSuccess = $this->denpyoSagyo->saveDataMuti($dataDenpyoSagyo);
            } else {
                $res = $this->deleteDataObj('denpyoSagyo', 'D04_DEN_NO = ' . $dataDenpyo['D03_DEN_NO']);
                $checkSuccess = false;
                if ($res >= 0) {
                    $checkSuccess = true;
                }
            }

            if ($checkSuccess) {
                if (count($dataDenpyoCom)) {
                    $this->deleteDataObj('denpyoCom', 'D05_DEN_NO = ' . $dataDenpyo['D03_DEN_NO']);
                    $checkSuccess = $this->denpyoCom->saveDataMuti($dataDenpyoCom);
                } else {
                    $res = $this->deleteDataObj('denpyoCom', 'D05_DEN_NO = ' . $dataDenpyo['D03_DEN_NO']);
                    $checkSuccess = false;
                    if ($res >= 0) {
                        $checkSuccess = true;
                    }
                }
            }
        }

        return $checkSuccess;
    }

    /**
     * @param $filters
     * @return array
     */
    public function getTm08Sagyosya($filters)
    {
        return $this->tm08Sagyosya->getData($filters);
    }

    /**
     * @param $dataDenpyo
     * @param $dataDenpyoSuggest
     * @param $dataInspection
     * @param $dataDenpyoSagyo
     * @param $dataCus
     * @param $dataDenpyoCom
     * @param $dataDenpyoComSuggest
     * @param $denpyoNo
     * @param $denpyoNoSuggest
     * @return bool
     * @throws Exception
     */
    public function saveDenpyoInspection($dataDenpyo, $dataDenpyoSuggest, $dataInspection, $dataDenpyoSagyo, $dataCus, $dataDenpyoCom, $dataDenpyoComSuggest, $denpyoNo, $denpyoNoSuggest)
    {

        $transaction = \Yii::$app->getDb()->beginTransaction();
        $res = $this->saveDenpyo($dataDenpyo, $dataDenpyoSagyo, $dataCus, $dataDenpyoCom, $denpyoNo);
        for ($i = 0; $i < count($dataDenpyoSagyo); ++$i) {
            $dataDenpyoSagyo[$i]['D04_DEN_NO'] = $denpyoNoSuggest;
        }
        // is insert
        if (!$denpyoNo) {
            $denpyoNoSuggest = 0;
        }

        $res_suggest = $this->saveDenpyo($dataDenpyoSuggest, $dataDenpyoSagyo, $dataCus, $dataDenpyoComSuggest, $denpyoNoSuggest);
        $this->denpyoInspection->setData($dataInspection, $denpyoNo);
        $res_inspection = $this->denpyoInspection->saveData();
        $result = $res && $res_suggest && $res_inspection;
        if ($result) {
            $transaction->commit();
        } else {
            $transaction->rollBack();
        }

        return $result;
    }

    /**
     * @param $cus_no
     * @param $car_seq
     * @param null $kaiin
     * @return array
     */
    public function getCar($cus_no, $car_seq, $car_no, $kaiin = null)
    {
        $obj = new Sdptd02car();
        $data = $obj->getData(['D02_CUST_NO' => $cus_no, 'D02_CAR_SEQ' => $car_seq, 'D02_CAR_NO' => $car_no]);
        $api = new api();
        if (!$data && !$kaiin) {
            return $data;
        }
        if ($data) {
            $data = $data[0];
        }
        if ($kaiin) {
            $info = $api->getInfoListCar($kaiin);
            $seq = array_search($car_seq, $info['car_carSeq']);
            $data['D02_MAKER_CD'] = $info['car_makerCd'][$seq];
            $data['D02_MODEL_CD'] = $info['car_modelCd'][$seq];
            $data['D02_SHONENDO_YM'] = $info['car_syoNendoInsYmd'][$seq];
            $data['D02_TYPE_CD'] = $info['car_typeCd'][$seq];
            $data['D02_GRADE_CD'] = $info['car_gradeCd'][$seq];
            $data['D02_SYAKEN_CYCLE'] = $info['car_syakenCycle'][$seq];
        }
        $maker_code = $data['D02_MAKER_CD'];
        $model_code = $data['D02_MODEL_CD'];
        $year = substr($data['D02_SHONENDO_YM'], 0, 4);
        $type_code = $data['D02_TYPE_CD'];
        $grade_code = $data['D02_GRADE_CD'];

        $maker = $api->getListMaker();
        foreach ($maker as $k => $v) {
            if (isset($v['maker_code']) && $v['maker_code'] == $maker_code) {
                $data['D02_MAKER_CD'] = $v['maker'];
                break;
            }
        }
        if ($data['D02_MAKER_CD'] == '-111') {
            $data['D02_MAKER_CD'] = '';
        }

        $type = $api->getListTypeCode($maker_code, $model_code, $year);
        foreach ($type as $key => $v) {
            if (isset($v['type_code']) && $v['type_code'] == $type_code) {
                $data['D02_TYPE_CD'] = $v['type'];
                break;
            }
        }

        $grade = $api->getListGradeCode($maker_code, $model_code, $year, $type_code);
        foreach ($grade as $key => $v) {
            if (isset($v['grade_code']) && $v['grade_code'] == $grade_code) {
                $data['D02_GRADE_CD'] = $v['grade'];
                break;
            }
        }

        return $data;
    }

    /**
     * @param $den_no
     * @return array
     */
    public function getProduct($den_no)
    {
        $obj = new Sdptd05denpyocom();
        $data = $obj->getData(['D05_DEN_NO' => $den_no]);
        $product = '';
        foreach ($data as $k => $v) {
            $product .= $v['D05_COM_CD'] . $v['D05_NST_CD'] . ',';
        }

        if ($product) {
            $result = $obj->getListName($product);
            foreach ($result as $k => $v) {
                foreach ($data as $key => $value) {
                    if ($v['M05_COM_CD'] . $v['M05_NST_CD'] == $value['D05_COM_CD'] . $value['D05_NST_CD']) {
                        $data[$key]['M05_COM_NAMEN'] = $v['M05_COM_NAMEN'];
                        $data[$key]['M05_LIST_PRICE'] = $v['M05_LIST_PRICE'];
                    }
                }
            }
        }

        return $data;
    }

}