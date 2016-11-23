<?php

namespace backend\modules\registworkslip\controllers;

use app\models\Sdptd01customer;
use app\models\Sdptm05com;
use Yii;
use backend\controllers\WsController;
use backend\components\api;
use app\models\Udenpyo;
use app\models\Sdptd03denpyo;
use app\models\Sdptd01custommerseq;
use backend\modules\pdf\controllers\PdfController;
use yii\data\Pagination;
use yii\db\Expression;
use yii\helpers\BaseUrl;
use yii\web\Cookie;
use backend\components\confirm;

class DefaultController extends WsController {

    public function actionIndex() {
        $api = new api();
        $uDenpyo = new Udenpyo();
        $cusObj = new Sdptd01customer();
        $login_info = Yii::$app->session->get('login_info');
        $cookie = \Yii::$app->request->cookies; // get info cus from cookie
        $cusInfo = $cookie->getValue('cus_info', ['type_redirect' => 3]);
        if ($cusInfo['type_redirect'] == 1) {
            if (count($cusObj->getData(['D01_KAIIN_CD' => $cusInfo['member_kaiinCd']])) == 0) {
                $dataInsert = $uDenpyo->convertKeyApiDB($cusInfo);
                $cusObj->setData($dataInsert);
                $cusObj->saveData();
            }
        }
        $custNo = (int) Yii::$app->request->get('custNo');
        $d03DenNo = Yii::$app->request->get('denpyo_no');
        $addCus = Yii::$app->request->get('addCust');
        $data['d03DenNo'] = $d03DenNo;
        if (Yii::$app->request->isPost && !$addCus) {
            $denpyoDataPost = [];
            $rs = $this->saveDataDenpyo($d03DenNo, $denpyoDataPost);
            if ($rs) {
                $puncon = Yii::$app->request->post('puncon_check');
                if ($puncon) {
                    $this->saveCsv($denpyoDataPost);
                } else {
                    $this->deleteCsv($denpyoDataPost);
                }

                confirm::writeconfirm($denpyoDataPost);
                if (isset($denpyoDataPost['checkClickWarranty']) && $denpyoDataPost['checkClickWarranty'] == 1 && $puncon) {
                    $pdf = $this->savePdf($rs, $denpyoDataPost, false);
                }

                Yii::$app->session->setFlash('success', '作業伝票の登録が完了しました。');
                if ($d03DenNo)
                    $this->redirect(\yii\helpers\BaseUrl::base(true) . '/detail-workslip?add=false&den_no=' . $rs);
                else
                    $this->redirect(\yii\helpers\BaseUrl::base(true) . '/detail-workslip?add=true&den_no=' . $rs);
            } elseif ($rs === 0) {
                if (!Yii::$app->request->get('addCust')) {
                    $data['notExitCus'] = true;
                }
            } else {
                $data['errorEditInsert'] = true;
            }
        }

        $carDefault = $uDenpyo->setDefaultDataObj('car');
        $denpyoComDefault = $uDenpyo->setDefaultDataObj('denpyoCom');
        $denpyo = $uDenpyo->setDefaultDataObj('denpyo'); // set default data
        $tm01Sagyo = $uDenpyo->getTm01Sagyo([]); // Work job
        $data['totalDenpyoCom'] = 0;
        $listDenpyoCom = array_pad([], 10, $denpyoComDefault);
        $sagyoCheck = [];
        $data['listTm05Edit'] = [];
        $totalCarOfCus = 0;
        $data['csv'] = \backend\components\csv::readcsv(['D03_DEN_NO' => $d03DenNo]);
        $data['confirm'] = \backend\components\confirm::readconfirm(['D03_DEN_NO' => $d03DenNo]);
        if ($d03DenNo) {
            $denpyo = current($uDenpyo->getDenpyo(['D03_DEN_NO' => $d03DenNo]));
            $cusDb = $uDenpyo->getTd01Customer(['D01_CUST_NO' => $denpyo['D03_CUST_NO']]);
            if (count($cusDb)) {
                $cusInfo = current($cusDb);
                if (!$cusInfo['D01_KAIIN_CD']) {
                    $car = $uDenpyo->getCar($cusInfo['D01_CUST_NO']);
                    $totalCarOfCus = count($car);
                    $car = array_pad($car, 5, $carDefault);
                } else {
                    $uDenpyo->getInforCarCusFromApi($uDenpyo, $api, $carDefault, $cusInfo, $totalCarOfCus, $car, false);
                }
            } else {
                return false;
            }

            $tm01SagyoCheck = $uDenpyo->getDenpyoSagyo(['D04_DEN_NO' => $d03DenNo]);
            if (count($tm01SagyoCheck)) {
                foreach ($tm01SagyoCheck as $tmp) {
                    $sagyoCheck[] = $tmp['D04_SAGYO_NO'];
                }
            }

            $listDenpyoCom = $uDenpyo->getDenpyoCom(['D05_DEN_NO' => $d03DenNo]);

            if (count($listDenpyoCom)) {
                $arrCdCom = [];
                $arrTemp = [];
                foreach ($listDenpyoCom as $tmp) {
                    $arrCdCom[] = $tmp['D05_COM_CD'];
                    $obj_sdptm05com = $uDenpyo->getTm05Com(['M05_COM_CD' => $tmp['D05_COM_CD'], 'M05_NST_CD' => $tmp['D05_NST_CD']]);
                    if (isset($obj_sdptm05com[0]['M05_COM_NAMEN'])) {
                        $arrTemp[$tmp['D05_COM_CD'] . "_" . $tmp['D05_NST_CD']]['M05_COM_NAMEN'] = $obj_sdptm05com[0]['M05_COM_NAMEN'];
                        $arrTemp[$tmp['D05_COM_CD'] . "_" . $tmp['D05_NST_CD']]['M05_LIST_PRICE'] = $obj_sdptm05com[0]['M05_LIST_PRICE'];
                    }
                }

                /* $listTm05Edit = $uDenpyo->getTm05Com(['M05_COM_CD_IN' => $arrCdCom]);
                  $arrTemp = [];
                  foreach ($listTm05Edit as $tmp) {
                  $arrTemp[$tmp['M05_COM_CD']] = $tmp;
                  }
                 */

                $data['listTm05Edit'] = $arrTemp;
            }

            $data['totalDenpyoCom'] = count($listDenpyoCom);
            $listDenpyoCom = array_pad($listDenpyoCom, 10, $denpyoComDefault);
        } else {
            if ($cusInfo['type_redirect'] == 1) {
                $uDenpyo->getInforCarCusFromApi($uDenpyo, $api, $carDefault, $cusInfo, $totalCarOfCus, $car);
                $cusInfo['type_redirect'] = 1;
            } else {
                if ($cusInfo['type_redirect'] == 3) {
                    $cusInfo = $uDenpyo->setDefaultDataObj('customer');
                    if ($custNo) {
                        $cusDb = $uDenpyo->getTd01Customer(['D01_CUST_NO' => $custNo]);
                        $cusInfo = current($cusDb);
                    }
                    $cusInfo['type_redirect'] = 3;
                }

                $car = $uDenpyo->getCar($custNo ? $custNo : (isset($cusInfo['D01_CUST_NO']) ? $cusInfo['D01_CUST_NO'] : 0));
                $totalCarOfCus = count($car);
                $car = array_pad($car, 5, $carDefault);
            }
        }

        $cusInfo['D01_SS_CD'] = $login_info['M50_SS_CD'];
        $data['sagyoCheck'] = $sagyoCheck;
        $ssUser = ['' => ''];
        $ssUserDenpyo = ['' => ''];
        $tm08Sagyosya = $uDenpyo->getTm08Sagyosya(['M08_SS_CD' => $d03DenNo ? $denpyo['D03_SS_CD'] : $login_info['M50_SS_CD']]); // 277426 Is login
        if (count($tm08Sagyosya)) {
            foreach ($tm08Sagyosya as $tmp) {
                $ssUser[$tmp['M08_JYUG_CD']] = $tmp['M08_NAME_SEI'] . $tmp['M08_NAME_MEI'];
                $ssUserDenpyo[$tmp['M08_NAME_SEI'] . '[]' . $tmp['M08_NAME_MEI']] = $tmp['M08_NAME_SEI'] . $tmp['M08_NAME_MEI'];
            }
        }
        $data['pagination'] = new Pagination([
            'totalCount' => $uDenpyo->countDataRecord('tm05Com', []),
            'defaultPageSize' => 10, //Yii::$app->params['defaultPageSize'],
        ]);

        $data['filters']['limit'] = $data['pagination']->limit;
        $data['filters']['offset'] = $data['pagination']->offset;
        $data['tm05Com'] = $uDenpyo->getTm05Com($data['filters']);
        $data['cus'] = $cusInfo;
        $data['totalCarOfCus'] = $totalCarOfCus;
        $data['car'] = $car;
        $data['denpyo'] = $denpyo;
        $data['ssUer'] = $ssUser;
        $data['ssUerDenpyo'] = $ssUserDenpyo;
        $data['tm01Sagyo'] = $tm01Sagyo;
        $data['listDenpyoCom'] = $listDenpyoCom;
        if ($d03DenNo) {
            $data['csvExists'] = $this->checkCsv($d03DenNo);
        } else {
            $data['csvExists'] = false;
        }
        $data['item'] = Yii::$app->params['items'];
        $data['car_places'] = [];
        $data['car_regions'] = Yii::$app->params['car_regions'];
        $data['url_car_api'] = Yii::$app->params['api']['car']['url_car'];
        foreach (Yii::$app->params['car_regions'] as $region => $prefectures) {
            foreach ($prefectures as $prefecture => $_places) {
                $data['car_places'][$prefecture] = $_places;
            }
        }
        \Yii::$app->view->title = '作業伝票作成';
        \Yii::$app->params['titlePage'] = '作業伝票作成';
        return $this->render('index', $data);
    }

    public function saveDataDenpyo($denpyoNo, &$denpyoDataPost) {
        $login_info = Yii::$app->session->get('login_info');
        $uDenpyo = new Udenpyo();
        $denpyo = new Sdptd03denpyo();
        $denpyoSagyo = new \app\models\Sdptd04denpyosagyo();
        $denpyoCom = new \app\models\Sdptd05denpyocom();
        $dataTemp = Yii::$app->request->post();
        foreach ($dataTemp as $key => $val) {
            if (substr($key, 0, 3) == 'D03') {
                $dataDenpyo[$key] = $val;
            }
        }

        $dataDenpyo['D03_SS_CD'] = $dataTemp['D01_SS_CD'];
        if ($dataTemp['D03_TANTO_MEI_D03_TANTO_SEI'] != '') {
            $temTantoMeiSei = explode('[]', $dataTemp['D03_TANTO_MEI_D03_TANTO_SEI']);
            $dataDenpyo['D03_TANTO_SEI'] = $temTantoMeiSei['0'];
            $dataDenpyo['D03_TANTO_MEI'] = $temTantoMeiSei['1'];
        } else {
            $dataDenpyo['D03_TANTO_MEI'] = '';
            $dataDenpyo['D03_TANTO_SEI'] = '';
        }

        if ($dataTemp['D03_KAKUNIN_MEI_D03_KAKUNIN_SEI'] != '') {
            $temKakuninMeiSei = explode('[]', $dataTemp['D03_KAKUNIN_MEI_D03_KAKUNIN_SEI']);
            $dataDenpyo['D03_KAKUNIN_SEI'] = $temKakuninMeiSei['0'];
            $dataDenpyo['D03_KAKUNIN_MEI'] = $temKakuninMeiSei['1'];
        } else {
            $dataDenpyo['D03_KAKUNIN_MEI'] = '';
            $dataDenpyo['D03_KAKUNIN_SEI'] = '';
        }

        unset($dataDenpyo['D03_TANTO_MEI_D03_TANTO_SEI']);
        unset($dataDenpyo['D03_KAKUNIN_MEI_D03_KAKUNIN_SEI']);
        $dataDenpyo['D03_CUST_NO'] = isset($dataTemp['D01_CUST_NO']) ? $dataTemp['D01_CUST_NO'] : 0;
        if ($denpyoNo) {
            $dataDenpyo['D03_CUST_NO'] = $dataTemp['D03_CUST_NO'];
        } else {
            $dataDenpyo['D03_DEN_NO'] = $denpyo->getSeq();
        }

        $dataDenpyo['D03_KAKUNIN'] = (int) Yii::$app->request->post('D03_KAKUNIN');
        $seqCar = Yii::$app->request->post('D02_CAR_SEQ_SELECT');
        if ($seqCar) {
            $dataDenpyo['D03_CAR_SEQ'] = $seqCar;
            $dataDenpyo['D03_CAR_NO'] = $dataTemp['D02_CAR_NO_' . $seqCar];
            $dataDenpyo['D03_CAR_ID'] = $dataTemp['D02_CAR_ID_' . $seqCar];
            $dataDenpyo['D03_METER_KM'] = $dataTemp['D02_METER_KM_' . $seqCar];
            $dataDenpyo['D03_CAR_NAMEN'] = $dataTemp['D02_CAR_NAMEN_' . $seqCar];
            $dataDenpyo['D03_HIRA'] = $dataTemp['D02_HIRA_' . $seqCar];
            $dataDenpyo['D03_RIKUUN_NAMEN'] = $dataTemp['D02_RIKUUN_NAMEN_' . $seqCar];
            $dataDenpyo['D03_JIKAI_SHAKEN_YM'] = $dataTemp['D02_JIKAI_SHAKEN_YM_' . $seqCar];
        }

        $dataDenpyoCom = [];
        $k = 1;
        for ($i = 1; $i < 11; ++$i) {
            if (isset($dataTemp['code_search' . $i]) && $dataTemp['code_search' . $i] != '') {
                $dataDenpyoCom[$k]['D05_DEN_NO'] = $dataDenpyo['D03_DEN_NO'];
                //$dataDenpyoCom[$k]['D05_COM_CD'] = $dataTemp['D05_COM_CD' . $i];
                //$dataDenpyoCom[$k]['D05_NST_CD'] = $dataTemp['D05_NST_CD' . $i];
                $dataDenpyoCom[$k]['D05_COM_CD'] = str_pad(substr($dataTemp['code_search' . $i], 0, 6), 6, '0', STR_PAD_LEFT);
                $dataDenpyoCom[$k]['D05_NST_CD'] = str_pad(substr($dataTemp['code_search' . $i], 6, 3), 3, '0', STR_PAD_LEFT);
                $dataDenpyoCom[$k]['D05_COM_SEQ'] = $k;
                $dataDenpyoCom[$k]['D05_SURYO'] = $dataTemp['D05_SURYO' . $i];
                $dataDenpyoCom[$k]['D05_TANKA'] = $dataTemp['D05_TANKA' . $i];
                $dataDenpyoCom[$k]['D05_KINGAKU'] = $dataTemp['D05_KINGAKU' . $i];
                $dataDenpyoCom[$k]['D05_INP_DATE'] = new Expression("CURRENT_DATE");
                $dataDenpyoCom[$k]['D05_INP_USER_ID'] = $login_info['M50_USER_ID'];
                $dataDenpyoCom[$k]['D05_UPD_DATE'] = new Expression("CURRENT_DATE");
                $dataDenpyoCom[$k]['D05_UPD_USER_ID'] = $login_info['M50_USER_ID'];
                ++$k;
            }
        }

        $m01SagyoNo = Yii::$app->request->post('M01_SAGYO_NO');
        $dataDenpySagyo = [];
        if (count($m01SagyoNo)) {
            for ($i = 0; $i < count($m01SagyoNo); ++$i) {
                $dataDenpySagyo[] = [
                    'D04_DEN_NO' => $dataDenpyo['D03_DEN_NO'],
                    'D04_SAGYO_NO' => $m01SagyoNo[$i],
                    'D04_UPD_DATE' => new Expression("CURRENT_DATE"),
                    'D04_UPD_USER_ID' => $login_info['M50_USER_ID'],
                    'D04_INP_DATE' => new Expression("CURRENT_DATE"),
                    'D04_INP_USER_ID' => $login_info['M50_USER_ID'],
                ];
            }
            if ($denpyoNo) {
                $listDenpyoSagyo = $denpyoSagyo->getData(['D04_DEN_NO' => $denpyoNo]);
                /* Get input date,input user id of denpyosagyo */
                if (count($listDenpyoSagyo)) {
                    foreach ($dataDenpySagyo as $index => $temp) {
                        foreach ($listDenpyoSagyo as $index1 => $temp1) {
                            if ($temp['D04_SAGYO_NO'] == $temp1['D04_SAGYO_NO']) {
                                $dataDenpySagyo[$index]['D04_INP_DATE'] = $temp1['D04_INP_DATE'];
                                $dataDenpySagyo[$index]['D04_INP_USER_ID'] = $temp1['D04_INP_USER_ID'];
                            }
                        }
                    }
                }

                /* Get input date, input user id of denpyo com */
                $listDenpyoCom = $denpyoCom->getData(['D05_DEN_NO' => $denpyoNo]);
                if (count($listDenpyoCom) && count($dataDenpyoCom)) {
                    foreach ($dataDenpyoCom as $index => $temp) {
                        foreach ($listDenpyoCom as $index1 => $temp1) {
                            if ($temp['D05_COM_CD'] == $temp1['D05_COM_CD'] && $temp['D05_NST_CD'] == $temp1['D05_NST_CD'] && $temp['D05_COM_SEQ'] == $temp1['D05_COM_SEQ']) {
                                $dataDenpyoCom[$index]['D05_INP_DATE'] = $temp1['D05_INP_DATE'];
                                $dataDenpyoCom[$index]['D05_INP_USER_ID'] = $temp1['D05_INP_USER_ID'];
                            }
                        }
                    }
                }
            }
        }

        if ($seqCar == 0 || $dataDenpyo['D03_CUST_NO'] == 0) {
            \Yii::info('Error: ' . $seqCar . $dataDenpyo['D03_CUST_NO']);
            return 0;
        }
        $dataCus['D01_SS_CD'] = $dataTemp['D01_SS_CD'];
        $dataCus['D01_UKE_JYUG_CD'] = $dataTemp['M08_NAME_MEI_M08_NAME_SEI'];
        $tm08Sagyosya = current($uDenpyo->getTm08Sagyosya(['M08_JYUG_CD' => $dataTemp['M08_NAME_MEI_M08_NAME_SEI']]));
        $dataCus['D01_UKE_TAN_NAMEN'] = $tm08Sagyosya['M08_NAME_SEI'] . $tm08Sagyosya['M08_NAME_MEI'];
        $dataCus['D01_CUST_NO'] = $dataDenpyo['D03_CUST_NO'];
        $res = $uDenpyo->saveDenpyo($dataDenpyo, $dataDenpySagyo, $dataCus, $dataDenpyoCom, $denpyoNo);

        if ($res) {
            $denpyoDataPost = array_merge($dataTemp, $dataDenpyo);
            return $dataDenpyo['D03_DEN_NO'];
        }
        return false;
    }

    public function actionCar() {
        $uDenpyo = new Udenpyo();
        $carObj = new \app\models\Sdptd02car();
        $login_info = Yii::$app->session->get('login_info');
        $api = new api();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->request;
        $data = \Yii::$app->request->post('dataPost');
        $data = json_decode($data, true);
        //$is_car_api = \Yii::$app->request->post('is_car_api');
        $cookie = \Yii::$app->request->cookies;
        //$cusInfo = $cookie->getValue('cus_info', ['type_redirect' => 3]);
        $cusInfo['type_redirect'] = $request->post('type_redirect');
        $kaiinCd = \Yii::$app->request->post('D01_KAIIN_CD');
        if ($cusInfo['type_redirect'] == 1) {
            $carLength = count($data);
            $i = 1;
            $carApi = [];
            foreach ($data as $tmp) {
                $dataApi[$i] = json_decode(base64_decode($tmp['dataCarApiField']), true);
                $dataApi[$i]['car_gradeNamen'] = isset($tmp['car_gradeNamen']) ? $tmp['car_gradeNamen'] : null;
                $dataApi[$i]['car_typeNamen'] = isset($tmp['car_typeNamen']) ? $tmp['car_typeNamen'] : null;
                $dataApi[$i]['car_typeCd'] = isset($tmp['D02_TYPE_CD']) ? $tmp['D02_TYPE_CD'] : '';
                $dataApi[$i]['car_gradeCd'] = isset($tmp['D02_GRADE_CD']) ? $tmp['D02_GRADE_CD'] : '';
                $dataApi[$i]['car_makerNamen'] = $tmp['car_makerNamen'];
                $dataApi[$i]['car_modelNamen'] = $tmp['car_modelNamen'];
                if (isset($tmp['MAKER_CD_OTHER'])) {
                    $dataApi[$i]['car_carName'] = $tmp['MAKER_CD_OTHER'];
                }

                $dataApi[$i]['car_syoNendoInsYmd'] = (isset($tmp['D02_SHONENDO_YM']) && $tmp['D02_SHONENDO_YM'] != '') ? $tmp['D02_SHONENDO_YM'] : '000000';
                $carApi[$i] = $uDenpyo->dbCarApi($tmp);
                $carApi[$i] = array_merge($carApi[$i], $dataApi[$i]);
                $carApi[$i]['car_carSeq'] = (string) $i;
                $carApi[$i]['carLength'] = (string) $carLength;
                ++$i;
            }

            $rs = $api->updateCar($kaiinCd, $carLength, $carApi);
            if (count($rs)) {
                return ['result' => 1];
            }
            return ['result' => 0];
        } else {
            $denpyoNo = $request->post('D03_DEN_NO');
            $custNo = $request->post('D02_CUST_NO');
            if (!$request->post('D02_CUST_NO')) {
                return ['result' => -1];
            } else {
                $dataInsert = [];
                foreach ($data as $index => $tmp) {
                    $dataInsert[$index]['D02_CUST_NO'] = $tmp['D02_CUST_NO'];
                    $dataInsert[$index]['D02_CAR_SEQ'] = $index + 1;
                    $dataInsert[$index]['D02_CAR_NAMEN'] = $tmp['D02_CAR_NAMEN'];
                    $dataInsert[$index]['D02_MODEL_CD'] = $tmp['D02_MODEL_CD'];
                    if ($tmp['D02_MAKER_CD'] == '-111' && isset($tmp['MAKER_CD_OTHER'])) {
                        $dataInsert[$index]['D02_CAR_NAMEN'] = $tmp['MAKER_CD_OTHER'];
                        $dataInsert[$index]['D02_MODEL_CD'] = '00000000';
                    }
                    $dataInsert[$index]['D02_JIKAI_SHAKEN_YM'] = $tmp['D02_JIKAI_SHAKEN_YM'];
                    $dataInsert[$index]['D02_METER_KM'] = $tmp['D02_METER_KM'];
                    $dataInsert[$index]['D02_SYAKEN_CYCLE'] = $tmp['D02_SYAKEN_CYCLE'];
                    $dataInsert[$index]['D02_RIKUUN_NAMEN'] = $tmp['D02_RIKUUN_NAMEN'];
                    $dataInsert[$index]['D02_CAR_ID'] = $tmp['D02_CAR_ID'];
                    $dataInsert[$index]['D02_HIRA'] = $tmp['D02_HIRA'];
                    $dataInsert[$index]['D02_CAR_NO'] = $tmp['D02_CAR_NO'];
                    $dataInsert[$index]['D02_MAKER_CD'] = $tmp['D02_MAKER_CD'];
                    $dataInsert[$index]['D02_SHONENDO_YM'] = $tmp['D02_SHONENDO_YM'];
                    $dataInsert[$index]['D02_TYPE_CD'] = $tmp['D02_TYPE_CD'];
                    $dataInsert[$index]['D02_GRADE_CD'] = $tmp['D02_GRADE_CD'];
                    $dataInsert[$index]['D02_INP_DATE'] = new Expression('CURRENT_DATE');
                    $dataInsert[$index]['D02_UPD_DATE'] = new Expression('CURRENT_DATE');
                    $dataInsert[$index]['D02_INP_USER_ID'] = $login_info['M50_USER_ID'];
                    $dataInsert[$index]['D02_UPD_USER_ID'] = $login_info['M50_USER_ID'];
                }
                return ['result' => (int) $uDenpyo->updateCar($custNo, $dataInsert)];
            }
        }
    }

    /**
     * @return array
     */
    public function actionCus() {

        $api = new api();
        $cusObj = new \app\models\Sdptd01customer();
        $request = Yii::$app->request;
        $login_info = Yii::$app->session->get('login_info');
        $cusInfo['type_redirect'] = $request->post('type_redirect');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $cusDd = [];
        if ($data['D01_KAKE_CARD_NO']) {
            $cusDd = $cusObj->getData(['D01_KAKE_CARD_NO' => $data['D01_KAKE_CARD_NO']]);
        }
        $usappyId = $request->post('D01_KAIIN_CD');
        $check = count($cusDd);
        if ($data['D01_KAKE_CARD_NO'] != '' && $check > 0) {
            $cusDb = current($cusDd);
            if ($cusDb['D01_KAIIN_CD'] != $usappyId) {
                $result = ['kake_card_no_exist' => '1'];
                return $result;
            }
            if ($cusInfo['type_redirect'] == 2 && $check > 1) {
                $result = ['kake_card_no_exist' => '1'];
                return $result;
            }
            if ($cusInfo['type_redirect'] == 3 && $check > 0) {
                if (!$data['D01_CUST_NO']) {
                    $result = ['kake_card_no_exist' => '1'];
                    return $result;
                }
            }
            if ($check > 1) {
                $result = ['kake_card_no_exist' => '1'];
                return $result;
            }
        }

        if ($check == 1 && isset($data['D01_CUST_NO']) && $data['D01_CUST_NO']) {
            if ($cusDd[0]['D01_CUST_NO'] != $data['D01_CUST_NO']) {
                $result = ['kake_card_no_exist' => '1'];
                return $result;
            }
        }

        if ($cusInfo['type_redirect'] == 1) { // Is member
            $dataCsApi = [
                'member_kaiinName' => $request->post('D01_CUST_NAMEN'),
                'member_kaiinKana' => $request->post('D01_CUST_NAMEK'),
                'member_telNo1' => $request->post('D01_TEL_NO'),
                'member_telNo2' => $request->post('D01_MOBTEL_NO'),
                'member_address' => $request->post('D01_ADDR'),
                'member_yuubinBangou' => $request->post('D01_YUBIN_BANGO'),
            ];

            $res = $api->updateMemberBasic($usappyId, $dataCsApi);
            $resDb = 1;
            $cusDb = $cusObj->getData(['D01_KAIIN_CD' => $usappyId]);
            if (count($cusDb)) {
                $dataDb = [
                    'D01_NOTE' => $request->post('D01_NOTE'),
                    'D01_KAKE_CARD_NO' => $request->post('D01_KAKE_CARD_NO'),
                    'D01_CUST_NAMEN' => $request->post('D01_CUST_NAMEN'),
                    'D01_CUST_NAMEK' => $request->post('D01_CUST_NAMEK'),
                    'D01_YUBIN_BANGO' => $request->post('D01_YUBIN_BANGO'),
                    'D01_ADDR' => $request->post('D01_ADDR'),
                    'D01_TEL_NO' => $request->post('D01_TEL_NO'),
                    'D01_MOBTEL_NO' => $request->post('D01_MOBTEL_NO'),
                    'D01_SS_CD' => $login_info['M50_SS_CD'],
                ];
                $cusObj->setData($dataDb, $cusDb['0']['D01_CUST_NO']);
                $resDb = (int) $cusObj->saveData();
            }
            $memberInfo = $api->getMemberInfo($usappyId);
            $memberInfo['type_redirect'] = 1;
            $result = ['result_api' => (int) $res, 'result_db' => $resDb, 'custNo' => 0];
        } else {
            $cusObj->setData($data, $data['D01_CUST_NO']);
            $res = $cusObj->saveData();
            $memberInfo = $cusObj->getData(['D01_CUST_NO' => $data['D01_CUST_NO']]);
            $memberInfo['type_redirect'] = 3;
            $result = ['result_db' => $res, 'result_api' => 1, 'custNo' => $data['D01_CUST_NO']];
        }

        $cookie = new Cookie([
            'name' => 'cus_info',
            'value' => $memberInfo
        ]);
        \Yii::$app->response->cookies->add($cookie);
        return $result;
    }

    public function actionSearch() {
        $code = \Yii::$app->request->post('code');
        $uDenpyo = new Udenpyo();
        $tm05Com = $uDenpyo->getTm05Com(['M05_COM_CD' => substr($code, 0, 6), 'M05_NST_CD' => substr($code, 6, 3)]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return current($tm05Com);
    }

    public function actionSs() {
        $ssCode = Yii::$app->request->post('ssCode');
        $uDenpyo = new Udenpyo();
        $tm08Sagyosya = $uDenpyo->getTm08Sagyosya(['M08_SS_CD' => $ssCode]);
        $ssUser['0'] = [];
        $ssUser['1'] = [];
        if (count($tm08Sagyosya)) {
            foreach ($tm08Sagyosya as $tmp) {
                $ssUser['0'][$tmp['M08_JYUG_CD']] = $tmp['M08_NAME_SEI'] . $tmp['M08_NAME_MEI'];
                $ssUser['1'][$tmp['M08_NAME_SEI'] . '[]' . $tmp['M08_NAME_MEI']] = $tmp['M08_NAME_SEI'] . $tmp['M08_NAME_MEI'];
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $ssUser;
    }

    public function actionWarranty() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $uDenpyo = new Udenpyo();
        $tm09Warranty = new \app\models\Sdptm09warrantyno();
        $ssCd = Yii::$app->request->post('ss_cd');
        $tm09WarrantyNo = $uDenpyo->getTm09WarrantyNo(['M09_SS_CD' => $ssCd]);
        if (count($tm09WarrantyNo) == 0) {
            $dataWarranty = [
                'M09_SS_CD' => $ssCd,
                'M09_WARRANTY_NO' => 1,
                'M09_INP_DATE' => new Expression("CURRENT_DATE"),
                'M09_INP_USER_ID' => 'SCRADMIN',
                'M09_UPD_DATE' => new Expression("CURRENT_DATE"),
                'M09_UPD_USER_ID' => 'SCRADMIN',
            ];
            $tm09Warranty->setData($dataWarranty);
            $tm09Warranty->saveData();
            return ['numberWarrantyNo' => $ssCd . str_pad(1, 4, '0', STR_PAD_LEFT)];
        } else {
            $dataWarranty = current($tm09WarrantyNo);
            $dataWarranty['M09_WARRANTY_NO'] = $dataWarranty['M09_WARRANTY_NO'] + 1;
            $dataWarranty['M09_UPD_DATE'] = new Expression("CURRENT_DATE");
            $dataWarranty['M09_UPD_USER_ID'] = 'SCRADMIN';
            if ($dataWarranty['M09_WARRANTY_NO'] == 10000) {
                $dataWarranty['M09_WARRANTY_NO'] = 1;
            }

            $tm09Warranty->setData($dataWarranty, $ssCd);
            $res = $tm09Warranty->saveData();
            return ['numberWarrantyNo' => $ssCd . str_pad($dataWarranty['M09_WARRANTY_NO'], 4, '0', STR_PAD_LEFT)];
        }
    }

    public function actionPdfview() {
        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $postData = \Yii::$app->request->post();
        return BaseUrl::base(true) . '/' . $this->savePdf('0', $postData, true);
    }

    public function savePdf($denpyoNo, $postData, $isView = false) {
        $api = new api();
        $uDenpyo = new Udenpyo();
        if ($isView == false) {
            $creat_warranty = false;
            for ($i = 1; $i < 11; ++$i) {
                if (isset($postData['checkClickWarranty']) && $postData['checkClickWarranty'] == 1 && in_array((int) $postData['D05_COM_CD' . $i], range(42000, 42999))) {
                    $creat_warranty = true;
                    break;
                }
            }

            if (!$creat_warranty) {
                return false;
            }
        }

        $denpyo = $uDenpyo->setDefaultDataObj('denpyo');
        if ($denpyoNo) {
            $denpyo = current($uDenpyo->getDenpyo(['D03_DEN_NO' => $denpyoNo]));
        }

        $listSS = $api->getSsName();
        $ssInfo = [];
        foreach ($listSS as $ss) {
            if ($ss['sscode'] == $denpyo['D03_SS_CD'] || $ss['sscode'] == $postData['D03_SS_CD']) {
                $ssInfo = $ss;
                break;
            }
        }

        $info_warranty = [
            'number' => $postData['M09_WARRANTY_NO'],
            'date' => date('Y年m月d日'),
            'expired' => date('Y年m月d日', mktime(0, 0, 0, date('m', time()) + 6, date('d', time()), date('Y', time()))),
        ];

        $info_car = [
            'customer_name' => isset($postData['WARRANTY_CUST_NAMEN']) ? $postData['WARRANTY_CUST_NAMEN'] : '',
            'car_name' => isset($postData['D03_CAR_NAMEN']) ? $postData['D03_CAR_NAMEN'] : '',
            'car_license' => isset($postData['D03_CAR_NO']) ? $postData['D03_CAR_NO'] : '',
            'car_riku' => isset($postData['D03_RIKUUN_NAMEN']) ? $postData['D03_RIKUUN_NAMEN'] : '',
            'car_type_code' => isset($postData['D03_CAR_ID']) ? $postData['D03_CAR_ID'] : '',
            'car_hira' => isset($postData['D03_HIRA']) ? $postData['D03_HIRA'] : '',
        ];
        $info_bill = [
            'right_front' => [
                'info_market' => $postData['right_front_manu'],
                'product_name' => $postData['right_front_product'],
                'size' => $postData['right_front_size'],
                'serial' => $postData['right_front_serial'],
            ],
            'left_front' => [
                'info_market' => $postData['left_front_manu'],
                'product_name' => $postData['left_front_product'],
                'size' => $postData['left_front_size'],
                'serial' => $postData['left_front_serial'],
            ],
            'right_behind' => [
                'info_market' => $postData['right_behind_manu'],
                'product_name' => $postData['right_behind_product'],
                'size' => $postData['right_behind_size'],
                'serial' => $postData['right_behind_serial'],
            ],
            'left_behind' => [
                'info_market' => $postData['left_behind_manu'],
                'product_name' => $postData['left_behind_product'],
                'size' => $postData['left_behind_size'],
                'serial' => $postData['left_behind_serial'],
            ],
            'otherB' => [
                'info_market' => $postData['other_b_manu'],
                'product_name' => $postData['other_b_product'],
                'size' => $postData['other_b_size'],
                'serial' => $postData['other_b_serial'],
            ],
            'otherA' => [
                'info_market' => $postData['other_a_manu'],
                'product_name' => $postData['other_a_product'],
                'size' => $postData['other_a_size'],
                'serial' => $postData['other_a_serial'],
            ]
        ];
        $info_ss = [
            'name' => isset($ssInfo['ss_name']) ? $ssInfo['ss_name'] : 'N/A',
            'address' => isset($ssInfo['address']) ? $ssInfo['address'] : 'N/A',
            'mobile' => isset($ssInfo['tel']) ? $ssInfo['tel'] : 'N/A',
        ];
        $data = [
            'info_warranty' => $info_warranty,
            'info_car' => $info_car,
            'info_bill' => $info_bill,
            'info_ss' => $info_ss
        ];

        $pdf_export = new PdfController();
        if ($isView == true) {
            $res = $pdf_export->exportBill($info_warranty, $info_car, $info_bill, $info_ss, $denpyo['D03_DEN_NO'], null, 1);
        } else {
            $res = $pdf_export->exportBill($info_warranty, $info_car, $info_bill, $info_ss, $denpyo['D03_DEN_NO'], 'save', 0);
        }

        return $res;
    }

    public function saveCsv($postData) {
        if (file_exists(getcwd() . '/data/pdf/' . $postData['D03_DEN_NO'] . '.pdf')) {
            return true;
        }
        $totalTaisa = 0;
        $totalSuryo = 0;
        for ($i = 1; $i < 11; ++$i) {
            if ((int) $postData['D05_COM_CD' . $i] && in_array((int) $postData['D05_COM_CD' . $i], range(42000, 42999))) {
                $totalSuryo += $postData['D05_SURYO' . $i];
                $totalTaisa = $totalTaisa + 1;
            }
        }

        if ($totalTaisa) {
            $postData['D05_SURYO'] = $totalSuryo;
            return \backend\components\csv::writecsv($postData);
        }

        return \backend\components\csv::deletecsv($postData);
    }

    public function deleteCsv($postData) {
        return \backend\components\csv::deletecsv($postData);
    }

    public function checkCsv($d03DenNo) {
        return file_exists(getcwd() . '/data/csv/' . $d03DenNo . '.csv');
    }

}
