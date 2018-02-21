<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use yii\db\Expression;
use yii\db\Query;

class Udenpyo
{

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

    function __construct()
    {

        $this->customer = new Sdptd01customer();
        $this->car = new Sdptd02car();
        $this->denpyo = new Sdptd03denpyo();
        $this->denpyoSagyo = new Sdptd04denpyosagyo();
        $this->denpyoCom = new Sdptd05denpyocom();
        $this->tm05Com = new Sdptm05com();
        $this->tm08Sagyosya = new Sdptm08sagyosya();
        $this->tm03LagreCom = new Sdptm03largecom();
        $this->tm01Sagyo = new Sdptm01sagyo();
        $this->tm09WarrantyNo = new Sdptm09warrantyno();
    }

    function getCar($d02CustNo = null)
    {
        if ($d02CustNo == null) {
            return [];
        }

        $dataCar = $this->car->getData(['D02_CUST_NO' => $d02CustNo]);
        return $dataCar;
    }

    public function getDenpyo($filters)
    {

        $dataDenpyo = $this->denpyo->getData($filters);
        return $dataDenpyo;
    }

    public function getDenpyoCom($filters = [])
    {
        $dataDenpyoCom = $this->denpyoCom->getData($filters);
        return $dataDenpyoCom;
    }

    public function countDataRecord($obj, $filters)
    {
        return $this->$obj->coutData($filters);
    }

    public function setDefaultDataObj($obj)
    {

        return $this->$obj->setDataDefault();
    }

    public function getDenpyoSagyo($filters)
    {
        return $this->denpyoSagyo->getData($filters);
    }

    public function getTm05Com($filters)
    {
        return $this->tm05Com->getData($filters);
    }

    public function getTm08Sagyosya($filters)
    {
        return $this->tm08Sagyosya->getData($filters);
    }

    public function getTm01Sagyo($filters)
    {

        return $this->tm01Sagyo->getData($filters);
    }

    public function getTd01Customer($filters)
    {

        return $this->customer->getData($filters);
    }

    public function getTm09WarrantyNo($filters)
    {

        return $this->tm09WarrantyNo->getData($filters);
    }

    public function gettm03LagreCom($filters)
    {
        return $this->tm03LagreCom->getData($filters);
    }

    public function convertKeyApiDB($cus)
    {

        return [
            'D01_KAIIN_CD' => $cus['member_kaiinCd'],
            'D01_CUST_NAMEN' => $cus['member_kaiinName'],
            'D01_CUST_NAMEK' => $cus['member_kaiinKana'],
            'D01_TEL_NO' => $cus['member_telNo1'],
            'D01_MOBTEL_NO' => $cus['member_telNo2'],
            'D01_ADDR' => $cus['member_address'],
            'D01_KAKE_CARD_NO' => '',
            'D01_YUBIN_BANGO' => $cus['member_yuubinBangou'],
            'D01_SS_CD' => $cus['member_ssCode'],
        ];
    }

    public function getSeqObj($obj)
    {
        return $this->$obj->getSeq();
    }

    public function defaultApiCar()
    {
        return [
            'D02_CUST_NO' => 0,
            'D02_CAR_SEQ' => 0,
            'D02_CAR_NAMEN' => '',
            'D02_JIKAI_SHAKEN_YM' => '',
            'D02_METER_KM' => '',
            'D02_SYAKEN_CYCLE' => '0',
            'D02_RIKUUN_NAMEN' => '',
            'D02_CAR_ID' => '', //
            'D02_HIRA' => '',
            'D02_CAR_NO' => '',
            'D02_INP_DATE' => new Expression("CURRENT_DATE"),
            'D02_INP_USER_ID' => '0', //
            'D02_UPD_DATE' => new Expression("CURRENT_DATE"),
            'D02_UPD_USER_ID' => '0', //
            'D02_MAKER_CD' => '0',
            'D02_MODEL_CD' => '0',
            'D02_SHONENDO_YM' => '',
            'D02_TYPE_CD' => '0',
            'D02_GRADE_CD' => '0',
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

    public function convertKeyApiDbCar($getCarApi)
    {
        if (count($getCarApi) == 0) {
            return $this->defaultApiCar();
        }

        $carApis = [];
        foreach ($getCarApi as $key => $row) {
            if (is_array($row)) {
                for ($i = 0; $i < count($row); ++$i) {
                    $carApis[$i][$key] = $row[$i];
                }
            } else {
                $info[$key] = $row;
            }
        }

        $arr = [];
        foreach ($carApis as $car) {
            $arr[] = [
                'D02_CUST_NO' => 0,
                'D02_CAR_SEQ' => $car['car_carSeq'],
                'D02_CAR_NAMEN' => $car['car_modelNamen'] ? $car['car_modelNamen'] : $car['car_carName'],
                'D02_JIKAI_SHAKEN_YM' => $car['car_jikaiSyakenYmd'],
                'D02_METER_KM' => $car['car_meterKm'],
                'D02_SYAKEN_CYCLE' => $car['car_syakenCycle'],
                'D02_RIKUUN_NAMEN' => $car['car_riunJimusyoName'],
                'D02_CAR_ID' => $car['car_syubetu'], //
                'D02_HIRA' => $car['car_hiragana'],
                'D02_CAR_NO' => $car['car_carNo'],
                'D02_INP_DATE' => new Expression("CURRENT_DATE"),
                'D02_INP_USER_ID' => 0, //
                'D02_UPD_DATE' => new Expression("CURRENT_DATE"),
                'D02_UPD_USER_ID' => 0, //
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

    public function dbCarApi($car)
    {
        return [
            'car_carSeq' => $car['D02_CAR_SEQ'],
            'car_carName' => $car['D02_CAR_NAMEN'],
            'car_jikaiSyakenYmd' => $car['D02_JIKAI_SHAKEN_YM'],
            'car_meterKm' => $car['D02_METER_KM'],
            'car_syakenCycle' => $car['D02_SYAKEN_CYCLE'],
            'car_riunJimusyoName' => $car['D02_RIKUUN_NAMEN'],
            'car_hiragana' => $car['D02_HIRA'],
            'car_carNo' => $car['D02_CAR_NO'],
            'car_syubetu' => $car['D02_CAR_ID'],
            'car_makerCd' => $car['D02_MAKER_CD'],
            'car_modelCd' => $car['D02_MODEL_CD'],
            'car_syoNendoInsYmd' => $car['D02_SHONENDO_YM'],
            'car_typeCd' => $car['D02_TYPE_CD'],
            'car_gradeCd' => $car['D02_GRADE_CD'],
        ];
    }

    public function getInforCarCusFromApi($uDenpyo, $api, $carDefault, &$cusInfo, &$totalCarOfCus, &$car, $is_new = true)
    {
        if ($is_new) {
            $cusDb = $uDenpyo->getTd01Customer(['D01_KAIIN_CD' => $cusInfo['member_kaiinCd']]);
            $cusDb = current($cusDb);
            $cusInfo = $api->getMemberInfo($cusInfo['member_kaiinCd']);
        } else {
            $cusDb = $cusInfo;
            $cusInfo = $api->getMemberInfo($cusInfo['D01_KAIIN_CD']);
        }

        $cusInfo = $uDenpyo->convertKeyApiDB($cusInfo);
        //$car = $uDenpyo->getCar($cusDb['D01_CUST_NO']); // Get Infor Car DB
        $getCarApi = $api->getInfoListCar($cusInfo['D01_KAIIN_CD']); // Api
        $car = $uDenpyo->convertKeyApiDbCar($getCarApi);
        $cusInfo['D01_NOTE'] = $cusDb['D01_NOTE'];
        $cusInfo['D01_KAKE_CARD_NO'] = $cusDb['D01_KAKE_CARD_NO'];
        $cusInfo['D01_CUST_NO'] = $cusDb['D01_CUST_NO'];
        $cusInfo['D01_UKE_JYUG_CD'] = $cusDb['D01_UKE_JYUG_CD'];
        $totalCarOfCus = count($car);
        $car = array_pad($car, 5, $this->defaultApiCar());
    }

    public function deleteDataObj($obj, $where)
    {
        return $this->$obj->deleteData($where);
    }

    public function getSeqCarUsed($custNo)
    {
        $carSeqUsed = [];
        if (!$custNo) {
            return $carSeqUsed;
        }

        $listDenpyo = $this->getDenpyo(['D03_CUST_NO' => $custNo]);
        if (count($listDenpyo)) {
            foreach ($listDenpyo as $key => $row) {
                $carSeqUsed[] = $row['D03_CAR_SEQ'];
            }
        }

        return $carSeqUsed;
    }

    public function updateCar($custNo, $data)
    {
        $transaction = $this->car->getDb()->beginTransaction();
        try {
            $this->deleteDataObj('car', 'D02_CUST_NO = ' . $custNo);
            if ($this->car->saveDataMuti($data) > 0) {
                $transaction->commit();
                return ['result' => 1];
            } else {
                $transaction->rollBack();
                return ['result' => 0];
            }

        } catch (Exception $e) {
            $transaction->rollBack();
            return ['result' => 0];
        }
    }

    public function saveDenpyo($dataDenpyo, $dataDenpyoSagyo, $dataCus, $dataDenpyoCom, $denpyoNo)
    {
        $this->denpyo->setData($dataDenpyo, $denpyoNo);
        $checkSuccess = false;
        $res = $this->denpyo->saveData();
        $transaction = $this->denpyo->getDb()->beginTransaction();
        if ($res) {
            $checkSuccess = true;
            $this->customer->setData($dataCus, $dataCus['D01_CUST_NO']);
            $resCus = $this->customer->saveData();
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

        if ($checkSuccess == false) {
            $transaction->rollBack();
        } else {
            $transaction->commit();
        }

        return $checkSuccess;
    }
}
