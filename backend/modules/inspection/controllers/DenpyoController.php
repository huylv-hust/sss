<?php
namespace backend\modules\inspection\controllers;

use app\models\Comment;
use app\models\DenpyoInspection;
use app\models\Discounts;
use app\models\Sdptd01customer;
use app\models\Sdptd02car;
use app\models\Sdptd04denpyosagyo;
use app\models\Sdptd05denpyocom;
use app\models\Sdptm05com;
use app\models\Sdptd03denpyo;
use app\models\Udenpyo;
use app\models\UdenpyoInspection;
use backend\components\api;
use backend\components\confirm;
use backend\components\csv;
use backend\controllers\WsController;
use backend\modules\pdf\controllers\PdfController;
use Yii;
use yii\data\Pagination;
use yii\db\Expression;
use yii\helpers\BaseUrl;
use yii\web\Cookie;

class DenpyoController extends WsController
{
    /**
     * submit modal customer
     * @return array
     */
    public function actionCustomer()
    {
        $api = new api();
        $customer_obj = new Sdptd01customer();
        $request = Yii::$app->request;
        $login_info = Yii::$app->session->get('login_info');
        $cus_info['type_redirect'] = $request->post('type_redirect');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = $request->post();

        $data = [];
        if ($post['D01_KAKE_CARD_NO']) {
            $data = $customer_obj->getData(['D01_KAKE_CARD_NO' => $post['D01_KAKE_CARD_NO']]);
        }
        $check = count($data);

        //check kake card no existed
        $result = ['card_number_exist' => '1'];
        if ($check > 1) {
            return $result;
        }
        if ($check == 1) {
            if ($data[0]['D01_KAIIN_CD'] != $post['D01_KAIIN_CD']) {
                return $result;
            }
            if ($cus_info['type_redirect'] == Yii::$app->params['TYPE_GUEST'] && !$post['D01_CUST_NO']) {
                return $result;
            }
            if ($post['D01_CUST_NO'] && $data[0]['D01_CUST_NO'] != $post['D01_CUST_NO']) {
                return $result;
            }
        }

        //update api, db
        if ($cus_info['type_redirect'] == Yii::$app->params['TYPE_MEMBER']) {
            $dataCsApi = [
                'member_kaiinName' => $request->post('D01_CUST_NAMEN'),
                'member_kaiinKana' => $request->post('D01_CUST_NAMEK'),
                'member_telNo1' => $request->post('D01_TEL_NO'),
                'member_telNo2' => $request->post('D01_MOBTEL_NO'),
                'member_address' => $request->post('D01_ADDR'),
                'member_yuubinBangou' => $request->post('D01_YUBIN_BANGO'),
            ];

            $result_api = $api->updateMemberBasic($post['D01_KAIIN_CD'], $dataCsApi);
            $result_db = 1;
            $cusDb = $customer_obj->getData(['D01_KAIIN_CD' => $post['D01_KAIIN_CD']]);
            if (!empty($cusDb)) {
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
                $customer_obj->setData($dataDb, $cusDb['0']['D01_CUST_NO']);
                $result_db = $customer_obj->saveData();
            }
            $member_info = $api->getMemberInfo($post['D01_KAIIN_CD']);
            $member_info['type_redirect'] = Yii::$app->params['TYPE_MEMBER'];
            $member_info['D01_CUST_NO'] = $cusDb['0']['D01_CUST_NO'];
            $member_info['D01_NOTE'] = $request->post('D01_NOTE');
            $member_info['D01_KAKE_CARD_NO'] = $request->post('D01_KAKE_CARD_NO');
            $result = ['card_number_exist' => 0, 'result_api' => $result_api, 'result_db' => $result_db, 'member_info' => $member_info];
        } else {
            $post['D01_SS_CD'] = $login_info['M50_SS_CD'];
            $customer_obj->setData($post, $post['D01_CUST_NO']);
            $result_db = $customer_obj->saveData();
            $cust_no = $customer_obj->getPrimaryKeyAfterSave();
            $member_info = $customer_obj->getData(['D01_CUST_NO' => $cust_no]);
            $member_info[0]['type_redirect'] = $cus_info['type_redirect'];
            $result = ['card_number_exist' => 0, 'result_api' => 1, 'result_db' => $result_db, 'member_info' => $member_info[0]];
            $member_info = $member_info[0];
        }

        $cookie = new Cookie([
            'name' => 'cus_info',
            'value' => $member_info
        ]);
        \Yii::$app->response->cookies->add($cookie);
        return $result;
    }

    /**
     * submit modal car
     * @return array
     */
    public function actionCar()
    {
        //new object
        $udenpyo_inspection_obj = new UdenpyoInspection();
        $api = new api();

        $login_info = Yii::$app->session->get('login_info');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;
        $data = json_decode($request->post('dataPost'), true);
        $type_redirect = $request->post('type_redirect');
        $kaiinCd = $request->post('D01_KAIIN_CD');
        $cust_no = $request->post('D02_CUST_NO');

        if ($type_redirect == Yii::$app->params['TYPE_MEMBER']) {
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
                $carApi[$i] = $udenpyo_inspection_obj->convertDataCarToApi($tmp);
                $carApi[$i] = array_merge($carApi[$i], $dataApi[$i]);
                $carApi[$i]['car_carSeq'] = (string)$i;
                $carApi[$i]['carLength'] = (string)$carLength;
                ++$i;
            }

            $rs = $api->updateCar($kaiinCd, $carLength, $carApi);
            $result = ['result' => 0];
            if (count($rs)) {
                $result['result'] = 1;
                $result['data'] = $data;
            }
            return $result;
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
            $result = $udenpyo_inspection_obj->updateCar($cust_no, $dataInsert);
            $result['data'] = $data;
            return $result;

        }
    }

    /**
     * ajax change car size
     */
    public function actionCarsize()
    {
        $value = Yii::$app->request->post('value');
        echo json_encode(Yii::$app->params['car_weight_' . $value]);
    }

    /**
     * ajax change parent discount
     * @return array
     */
    public function actionDiscount()
    {
        $udenpyo_inspection_obj = new UdenpyoInspection();
        $parent_discount_id = Yii::$app->request->post('parent_discount_id');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $udenpyo_inspection_obj->getDataDiscount($parent_discount_id);
    }

    /**
     * ajax change parent comment
     * @return mixed
     */
    public function actionComment()
    {
        $udenpyo_inspection_obj = new UdenpyoInspection();
        $parent_comment_id = Yii::$app->request->post('parent_comment_id');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $udenpyo_inspection_obj->getListComment($parent_comment_id);
    }

    /**
     * create shaken
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $login_info = Yii::$app->session->get('login_info');
        $cookie = \Yii::$app->request->cookies;
        //get customer info from cookie
        $customer_cookie = $cookie->getValue('cus_info', ['type_redirect' => Yii::$app->params['TYPE_GUEST']]);

        //new object
        $customer_obj = new Sdptd01customer();
        $udenpyo_inspection_obj = new UdenpyoInspection();
        $api = new api();
        $car_obj = new Sdptd02car();
        $product_obj = new Sdptm05com();
        $denpyo_com_obj = new Sdptd05denpyocom();
        $csv = new csv();

        $data['tm08_sagyosya'] = $udenpyo_inspection_obj->getSagyosyaBySSCD($login_info['M50_SS_CD']);
        $data['type_redirect'] = $customer_cookie['type_redirect'];

        //customer and car
        $data['cus_info'] = $udenpyo_inspection_obj->convertDataCustomerApi($customer_cookie);
        if ($data['type_redirect'] == Yii::$app->params['TYPE_MEMBER']) {
            //insert customer to database by api data if type is member and database has no data
            $cus = $customer_obj->getData(['D01_KAIIN_CD' => $customer_cookie['member_kaiinCd']]);
            if (empty($cus)) {
                $customer_obj->setData($data['cus_info']);
                $customer_obj->saveData();
                $data['cus_info']['D01_CUST_NO'] = $customer_obj->getPrimaryKeyAfterSave();
            } else {
                $data['cus_info'] = $cus[0];
            }
            //car
            $car_api = $api->getInfoListCar($customer_cookie['member_kaiinCd']);
            $cars = $udenpyo_inspection_obj->convertDataCarApi($car_api);
            $data['cars'] = array_pad($cars, Yii::$app->params['DEFAULT_NUMBER_CAR'], $car_obj->setDataDefaultApi());
        } else {
            $cars = $data['cus_info']['D01_CUST_NO'] ? $car_obj->getData(['D02_CUST_NO' => $data['cus_info']['D01_CUST_NO']]) : [];
            $data['cars'] = array_pad($cars, Yii::$app->params['DEFAULT_NUMBER_CAR'], $car_obj->setDataDefault());
        }
        $data['total_car'] = count($cars);
        $data['car_places'] = $this->getCarPlaces();

        //fee
        $data['fee_basic'] = $udenpyo_inspection_obj->getDataFeeBasic();
        $data['fee_registration'] = $udenpyo_inspection_obj->getDataFeeRegistration();
        $data['parent_discount'] = $udenpyo_inspection_obj->getDataParentDiscount();

        //comment
        $data['parent_comment'] = $udenpyo_inspection_obj->getDataParentComment();

        //work
        $data['all_works'] = $udenpyo_inspection_obj->getListWork();

        //product
        $data['product_pagination'] = new Pagination([
            'totalCount' => $product_obj->coutData([]),
            'defaultPageSize' => 10,
        ]);
        $data['filters']['limit'] = $data['product_pagination']->limit;
        $data['filters']['offset'] = $data['product_pagination']->offset;
        $data['products'] = $product_obj->getData($data['filters']);
        $data['listDenpyoCom'] = array_pad([], Yii::$app->params['DEFAULT_NUMBER_PRODUCT'], $denpyo_com_obj->setDataDefault());
        $data['totalDenpyoCom'] = 10;
        $data['product_name'] = [];
        $data['csvExists'] = false;
        $data['csv'] = $csv->defaultcsv();

        //confirm
        $data['confirm'] = [];

        //suggest
        $data['suggest']['listDenpyoCom'] = $data['listDenpyoCom'];
        $data['suggest']['totalDenpyoCom'] = 10;
        $data['suggest']['product_name'] = [];

        if (Yii::$app->request->isPost) {
            $denpyoDataPost = [];
            $rs = $this->saveDataDenpyo($denpyoDataPost, 0, 0);
            if ($rs) {
                $puncon = Yii::$app->request->post('puncon');
                $this->storeWarranty($rs, $puncon, $denpyoDataPost);
                Yii::$app->session->setFlash('success', '作業伝票の登録が完了しました。');
                return $this->redirect(\yii\helpers\BaseUrl::base(true) . '/shaken/denpyo/detail/' . $rs);
            }
        }

        Yii::$app->params['titlePage'] = '車検見積';
        Yii::$app->view->title = '車検見積';
        return $this->render('index', $data);
    }

    /**
     * @param $denpyoDataPost
     * @param int $denpyoNo
     * @param int $denpyoNoSuggest
     * @return bool|int
     */
    private function saveDataDenpyo(&$denpyoDataPost, $denpyoNo = 0, $denpyoNoSuggest = 0)
    {
        $login_info = Yii::$app->session->get('login_info');
        $uDenpyo = new UdenpyoInspection();
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
        $denpyoNoInsert = $denpyoNo;
        if ($denpyoNo) {
            $dataDenpyo['D03_CUST_NO'] = $dataTemp['D03_CUST_NO'];
        } else {
            $dataDenpyo['D03_DEN_NO'] = $denpyoNoInsert = $denpyo->getSeq();
            $denpyoNoSuggest = $denpyo->getSeq();
        }

        $dataDenpyo['D03_KAKUNIN'] = (int)Yii::$app->request->post('D03_KAKUNIN');


        $dataDenpyo['D03_CAR_SEQ'] = $dataTemp['D03_CAR_SEQ'];
        $dataDenpyo['D03_CAR_NO'] = $dataTemp['D03_CAR_NO'];
        $dataDenpyo['D03_CAR_ID'] = $dataTemp['D03_CAR_ID'];
        $dataDenpyo['D03_METER_KM'] = $dataTemp['D03_METER_KM'];
        $dataDenpyo['D03_CAR_NAMEN'] = $dataTemp['D03_CAR_NAMEN'];
        $dataDenpyo['D03_HIRA'] = $dataTemp['D03_HIRA'];
        $dataDenpyo['D03_RIKUUN_NAMEN'] = $dataTemp['D03_RIKUUN_NAMEN'];
        $dataDenpyo['D03_JIKAI_SHAKEN_YM'] = $dataTemp['D03_JIKAI_SHAKEN_YM'];


        $dataDenpyoCom = [];
        $k = 1;
        for ($i = 1; $i < 11; ++$i) {
            if (isset($dataTemp['code_search' . $i]) && $dataTemp['code_search' . $i] != '') {
                $dataDenpyoCom[$k]['D05_DEN_NO'] = $dataDenpyo['D03_DEN_NO'];
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

        $dataDenpyoComSuggest = [];
        $k = 1;
        for ($i = 1; $i < 11; ++$i) {
            if (isset($dataTemp['code_search_suggest' . $i]) && $dataTemp['code_search_suggest' . $i] != '') {
                $dataDenpyoComSuggest[$k]['D05_DEN_NO'] = $denpyoNoSuggest;
                $dataDenpyoComSuggest[$k]['D05_COM_CD'] = str_pad(substr($dataTemp['code_search_suggest' . $i], 0, 6), 6, '0', STR_PAD_LEFT);
                $dataDenpyoComSuggest[$k]['D05_NST_CD'] = str_pad(substr($dataTemp['code_search_suggest' . $i], 6, 3), 3, '0', STR_PAD_LEFT);
                $dataDenpyoComSuggest[$k]['D05_COM_SEQ'] = $k;
                $dataDenpyoComSuggest[$k]['D05_SURYO'] = $dataTemp['denpyo_suggest']['D05_SURYO' . $i];
                $dataDenpyoComSuggest[$k]['D05_TANKA'] = $dataTemp['denpyo_suggest']['D05_TANKA' . $i];
                $dataDenpyoComSuggest[$k]['D05_KINGAKU'] = $dataTemp['denpyo_suggest']['D05_KINGAKU' . $i];
                $dataDenpyoComSuggest[$k]['D05_INP_DATE'] = new Expression("CURRENT_DATE");
                $dataDenpyoComSuggest[$k]['D05_INP_USER_ID'] = $login_info['M50_USER_ID'];
                $dataDenpyoComSuggest[$k]['D05_UPD_DATE'] = new Expression("CURRENT_DATE");
                $dataDenpyoComSuggest[$k]['D05_UPD_USER_ID'] = $login_info['M50_USER_ID'];
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

        if ((int)$dataTemp['D03_CAR_SEQ'] == 0 || $dataDenpyo['D03_CUST_NO'] == 0) {
            \Yii::info('Error: ' . $dataTemp['D03_CAR_SEQ'] . $dataDenpyo['D03_CUST_NO']);
            return 0;
        }
        $dataCus['D01_SS_CD'] = $dataTemp['D01_SS_CD'];
        $dataCus['D01_UKE_JYUG_CD'] = $dataTemp['M08_NAME_MEI_M08_NAME_SEI'];
        $tm08Sagyosya = current($uDenpyo->getTm08Sagyosya(['M08_JYUG_CD' => $dataTemp['M08_NAME_MEI_M08_NAME_SEI']]));
        $dataCus['D01_UKE_TAN_NAMEN'] = $tm08Sagyosya['M08_NAME_SEI'] . $tm08Sagyosya['M08_NAME_MEI'];
        $dataCus['D01_CUST_NO'] = $dataDenpyo['D03_CUST_NO'];
        $dataCus['D01_CUST_NAMEN'] = $dataTemp['D01_CUST_NAMEN'];
        $dataCus['D01_CUST_NAMEK'] = $dataTemp['D01_CUST_NAMEK'];
        $dataTemp['WARRANTY_CUST_NAMEN'] = $dataTemp['D01_CUST_NAMEN'];

        if (isset($dataTemp['denpyo_inspection']['COMMENTS'])) {
            $dataTemp['denpyo_inspection']['COMMENTS'] = array_filter($dataTemp['denpyo_inspection']['COMMENTS']);
            if (!empty($dataTemp['denpyo_inspection']['COMMENTS'])) {
                $dataTemp['denpyo_inspection']['COMMENTS'] = ',' . implode(',', $dataTemp['denpyo_inspection']['COMMENTS']) . ',';
            } else {
                $dataTemp['denpyo_inspection']['COMMENTS'] = '';
            }
        } else {
            $dataTemp['denpyo_inspection']['COMMENTS'] = '';
        }

        if (isset($dataTemp['denpyo_inspection']['DISCOUNT'])) {
            $dataTemp['denpyo_inspection']['DISCOUNT'] = array_filter($dataTemp['denpyo_inspection']['DISCOUNT']);
            if (!empty($dataTemp['denpyo_inspection']['DISCOUNT'])) {
                $dataTemp['denpyo_inspection']['DISCOUNT'] = ',' . implode(',', $dataTemp['denpyo_inspection']['DISCOUNT']) . ',';
            } else {
                $dataTemp['denpyo_inspection']['DISCOUNT'] = '';
            }
        } else {
            $dataTemp['denpyo_inspection']['DISCOUNT'] = '';
        }

        $dataInspection = [
            'DENPYO_NO' => $dataDenpyo['D03_DEN_NO'],
            'DENPYO_SUGGEST_NO' => $denpyoNoSuggest,
            'CAR_SIZE' => isset($dataTemp['denpyo_inspection']['CAR_SIZE']) ? $dataTemp['denpyo_inspection']['CAR_SIZE'] : '',
            'CAR_WEIGHT' => isset($dataTemp['denpyo_inspection']['CAR_WEIGHT']) ? $dataTemp['denpyo_inspection']['CAR_WEIGHT'] : '',
            'EARNEST_MONEY' => isset($dataTemp['denpyo_inspection']['EARNEST_MONEY']) ? $dataTemp['denpyo_inspection']['EARNEST_MONEY'] : '',
            'WEIGHT_TAX' => isset($dataTemp['denpyo_inspection']['WEIGHT_TAX']) ? $dataTemp['denpyo_inspection']['WEIGHT_TAX'] : '',
            'FEE_BASIC_ID' => $dataTemp['denpyo_inspection']['FEE_BASIC_ID'],
            'FEE_REGISTRATION_ID' => isset($dataTemp['denpyo_inspection']['FEE_REGISTRATION_ID']) ? $dataTemp['denpyo_inspection']['FEE_REGISTRATION_ID'] : '',
            'PARENT_DISCOUNT_ID' => isset($dataTemp['denpyo_inspection']['PARENT_DISCOUNT_ID']) ? $dataTemp['denpyo_inspection']['PARENT_DISCOUNT_ID'] : '',
            'DISCOUNTS' => $dataTemp['denpyo_inspection']['DISCOUNT'],
            'COMMENTS' => $dataTemp['denpyo_inspection']['COMMENTS'],
        ];
        $dataDenpyoSuggest = $dataDenpyo;
        $dataDenpyoSuggest['D03_DEN_NO'] = $denpyoNoSuggest;
        $dataDenpyoSuggest['D03_POS_DEN_NO'] = $dataTemp['denpyo_suggest']['D03_POS_DEN_NO'];
        $dataDenpyoSuggest['D03_SUM_KINGAKU'] = $dataTemp['denpyo_suggest']['D03_SUM_KINGAKU'];
        $res = $uDenpyo->saveDenpyoInspection($dataDenpyo, $dataDenpyoSuggest, $dataInspection, $dataDenpySagyo, $dataCus, $dataDenpyoCom, $dataDenpyoComSuggest, $denpyoNo, $denpyoNoSuggest);
        if ($res) {
            $denpyoDataPost = array_merge($dataTemp, $dataDenpyo);
            return $denpyoNoInsert;
        }

        return false;
    }

    /**
     * edit shaken
     * @param $id
     * @return string
     */
    public function actionEdit($id)
    {
        $login_info = Yii::$app->session->get('login_info');
        //new object
        $denpyo_obj = new Sdptd03denpyo();
        $udenpyo_inspection_obj = new UdenpyoInspection();
        $customer_obj = new Sdptd01customer();
        $car_obj = new Sdptd02car();
        $api = new api();
        $denpyo_inspection_obj = new DenpyoInspection();
        $sagyo_obj = new Sdptd04denpyosagyo();
        $product_obj = new Sdptm05com();
        $comment_obj = new Comment();
        $discount_obj = new Discounts();

        $denpyo = $denpyo_obj->getData(['D03_DEN_NO' => $id]);
        if (empty($denpyo)) {
            return $this->redirect(BaseUrl::base(true) . '/shaken/denpyo/list');
        }

        $data['den_no'] = $id;
        $data['denpyo'] = current($denpyo);
        //fee
        $data['fee_basic'] = $udenpyo_inspection_obj->getDataFeeBasic();
        $data['fee_registration'] = $udenpyo_inspection_obj->getDataFeeRegistration();
        $data['parent_discount'] = $udenpyo_inspection_obj->getDataParentDiscount();

        $data['cus_info'] = current($customer_obj->getData(['D01_CUST_NO' => $data['denpyo']['D03_CUST_NO']]));
        $data['tm08_sagyosya'] = $udenpyo_inspection_obj->getSagyosyaBySSCD($login_info['M50_SS_CD']);

        //car
        if ($data['cus_info']['D01_KAIIN_CD']) {
            $car_api = $api->getInfoListCar($data['cus_info']['D01_KAIIN_CD']);
            $cars = $udenpyo_inspection_obj->convertDataCarApi($car_api);
            $data['cars'] = array_pad($cars, Yii::$app->params['DEFAULT_NUMBER_CAR'], $car_obj->setDataDefaultApi());
        } else {
            $cars = $car_obj->getData(['D02_CUST_NO' => $data['denpyo']['D03_CUST_NO']]);
            $data['cars'] = array_pad($cars, Yii::$app->params['DEFAULT_NUMBER_CAR'], $car_obj->setDataDefault());
        }
        $data['denpyo']['D02_SYAKEN_CYCLE'] = '';
        foreach ($data['cars'] as $k => $v) {
            if ($v['D02_CAR_SEQ'] == $data['denpyo']['D03_CAR_SEQ']) {
                $data['denpyo']['D02_SYAKEN_CYCLE'] = $v['D02_SYAKEN_CYCLE'];
            }
        }

        //If there is no car info, raise the error message
        $check_car = $car_obj->getData([
            'D02_CUST_NO' => $data['denpyo']['D03_CUST_NO'],
            'D02_CAR_SEQ' => $data['denpyo']['D03_CAR_SEQ'],
            'D02_CAR_NO' => $data['denpyo']['D03_CAR_NO'],
        ]);
        if (!$check_car && !$data['cus_info']['D01_KAIIN_CD']) {
            Yii::$app->session->setFlash('check_remove_car', '車両情報が削除されため、正常に表示できていません。');
        }

        $data['total_car'] = count($cars);

        //denpyo inspection
        $data['denpyo_inspection'] = current($denpyo_inspection_obj->getData(['DENPYO_NO' => $id]));
        $data['suggest_den_no'] = $data['denpyo_inspection']['DENPYO_SUGGEST_NO'];
        $data['packages_discount'] = $udenpyo_inspection_obj->getDataDiscount($data['denpyo_inspection']['PARENT_DISCOUNT_ID']);
        $data['discount'] = [];
        if ($data['denpyo_inspection']['DISCOUNTS']) {
            $tmp = trim($data['denpyo_inspection']['DISCOUNTS'], ',') ? trim($data['denpyo_inspection']['DISCOUNTS'], ',') : 0;
            $arr_tmp = $discount_obj->getData(['ID_IN' => $tmp], 'ID,DISCOUNT_PACKAGES_ID');
            foreach ($arr_tmp as $k => $v) {
                $data['discount'][$v['ID']] = $v['DISCOUNT_PACKAGES_ID'];
            }
        }

        //comment
        $data['parent_comment'] = $udenpyo_inspection_obj->getDataParentComment();
        $data['comments'] = [];
        if ($data['denpyo_inspection']['COMMENTS']) {
            $arr_tmp = explode(',', trim($data['denpyo_inspection']['COMMENTS'], ','));
            $data['comments'] = $comment_obj->getData(['array_comment' => $arr_tmp]);
        }

        //work
        $data['all_works'] = $udenpyo_inspection_obj->getListWork();
        $tm01SagyoCheck = $sagyo_obj->getData(['D04_DEN_NO' => $id]);
        $data['sagyo_used'] = [];
        if (!empty($tm01SagyoCheck)) {
            foreach ($tm01SagyoCheck as $k => $v) {
                $data['sagyo_used'][] = $v['D04_SAGYO_NO'];
            }
        }

        //product
        $product = $this->getListProductEdit($id);
        $data['product_name'] = $product['product_name'];
        $data['totalDenpyoCom'] = $product['totalDenpyoCom'];
        $data['listDenpyoCom'] = $product['listDenpyoCom'];
        //suggest
        $data['suggest'] = current($denpyo_obj->getData(['D03_DEN_NO' => $data['suggest_den_no']]));
        if ($data['suggest_den_no']) {
            $product_suggest = $this->getListProductEdit($data['suggest_den_no']);
            $data['suggest']['product_name'] = $product_suggest['product_name'];
            $data['suggest']['totalDenpyoCom'] = $product_suggest['totalDenpyoCom'];
            $data['suggest']['listDenpyoCom'] = $product_suggest['listDenpyoCom'];
        }
        //product paging
        $data['product_pagination'] = new Pagination([
            'totalCount' => $product_obj->coutData([]),
            'defaultPageSize' => 10,
        ]);
        $data['filters']['limit'] = $data['product_pagination']->limit;
        $data['filters']['offset'] = $data['product_pagination']->offset;
        $data['products'] = $product_obj->getData($data['filters']);
        $data['car_places'] = $this->getCarPlaces();

        $data['csv'] = csv::readcsv(['D03_DEN_NO' => $id]);
        $data['confirm'] = confirm::readconfirm(['D03_DEN_NO' => $id]);

        if (Yii::$app->request->isPost) {
            $denpyoDataPost = [];
            $rs = $this->saveDataDenpyo($denpyoDataPost, $id, $data['suggest_den_no']);
            if ($rs) {
                $puncon = Yii::$app->request->post('puncon');
                $this->storeWarranty($rs, $puncon, $denpyoDataPost);
                Yii::$app->session->setFlash('success', '作業伝票の登録が完了しました。');
                return $this->redirect(\yii\helpers\BaseUrl::base(true) . '/shaken/denpyo/detail/' . $rs);
            }
        }

        $data['csvExists'] = $this->checkCsv($id);
        Yii::$app->params['titlePage'] = '車検見積';
        Yii::$app->view->title = '車検見積';
        return $this->render('index', $data);
    }

    /**
     * @param $rs
     * @param $puncon
     * @param $denpyoDataPost
     */
    private function storeWarranty($rs, $puncon, $denpyoDataPost)
    {
        if ($puncon) {
            $this->saveCsv($denpyoDataPost);
        } else {
            $this->deleteCsv($denpyoDataPost);
        }

        confirm::writeconfirm($denpyoDataPost);
        if (isset($denpyoDataPost['warranty_check']) && $puncon) {
            if ($denpyoDataPost['check_pdf'] != 'disabled') {
                $this->savePdf($rs, $denpyoDataPost, false);
            }
        }
    }

    /**
     * check csv existed
     * @param $d03DenNo
     * @return bool
     */
    private function checkCsv($d03DenNo)
    {
        return file_exists(getcwd() . '/data/csv/' . $d03DenNo . '.csv');
    }

    /**
     * get denpyo edit
     * @param $den_no
     * @return array
     */
    private function getListProductEdit($den_no)
    {
        $denpyo_com_obj = new Sdptd05denpyocom();
        $product_obj = new Sdptm05com();
        $result = [];
        $listDenpyoCom = $denpyo_com_obj->getData(['D05_DEN_NO' => $den_no]);
        $result['product_name'] = [];
        if (!empty($listDenpyoCom)) {
            $arrCdCom = [];
            $arrTemp = [];
            foreach ($listDenpyoCom as $tmp) {
                $arrCdCom[] = $tmp['D05_COM_CD'];
                $filters = ['M05_COM_CD' => $tmp['D05_COM_CD'], 'M05_NST_CD' => $tmp['D05_NST_CD']];
                $obj_sdptm05com = $product_obj->getData($filters);
                if (isset($obj_sdptm05com[0]['M05_COM_NAMEN'])) {
                    $arrTemp[$tmp['D05_COM_CD'] . "_" . $tmp['D05_NST_CD']]['M05_COM_NAMEN'] = $obj_sdptm05com[0]['M05_COM_NAMEN'];
                    $arrTemp[$tmp['D05_COM_CD'] . "_" . $tmp['D05_NST_CD']]['M05_LIST_PRICE'] = $obj_sdptm05com[0]['M05_LIST_PRICE'];
                }
            }
            $result['product_name'] = $arrTemp;
        }
        $result['totalDenpyoCom'] = count($listDenpyoCom);
        $result['listDenpyoCom'] = array_pad($listDenpyoCom, Yii::$app->params['DEFAULT_NUMBER_PRODUCT'], $denpyo_com_obj->setDataDefault());
        return $result;
    }

    /**
     * get car place
     * @return mixed
     */
    private function getCarPlaces()
    {
        $data = [];
        foreach (Yii::$app->params['car_regions'] as $region => $prefectures) {
            foreach ($prefectures as $prefecture => $_places) {
                $data['car_places'][$prefecture] = $_places;
            }
        }
        return $data['car_places'];
    }

    /**
     * @param $denpyoNo
     * @param $postData
     * @param bool $isView
     * @return bool|string
     */
    public function savePdf($denpyoNo, $postData, $isView = false)
    {
        $api = new api();
        $uDenpyo = new Udenpyo();
        if ($isView == false) {
            $create_warranty = false;
            for ($i = 1; $i < 11; ++$i) {
                if (isset($postData['warranty_check']) && in_array((int)$postData['D05_COM_CD' . $i], range(42000, 42999))) {
                    $create_warranty = true;
                    break;
                }
            }

            if (!$create_warranty) {
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
                'product_name' => isset($postData['right_front_product']) ? $postData['right_front_product'] : '',
                'size' => $postData['right_front_size'],
                'serial' => $postData['right_front_serial'],
            ],
            'left_front' => [
                'info_market' => $postData['left_front_manu'],
                'product_name' => isset($postData['left_front_product']) ? $postData['left_front_product'] : '',
                'size' => $postData['left_front_size'],
                'serial' => $postData['left_front_serial'],
            ],
            'right_behind' => [
                'info_market' => $postData['right_behind_manu'],
                'product_name' => isset($postData['right_behind_product']) ? $postData['right_behind_product'] : '',
                'size' => $postData['right_behind_size'],
                'serial' => $postData['right_behind_serial'],
            ],
            'left_behind' => [
                'info_market' => $postData['left_behind_manu'],
                'product_name' => isset($postData['left_behind_product']) ? $postData['left_behind_product'] : '',
                'size' => $postData['left_behind_size'],
                'serial' => $postData['left_behind_serial'],
            ],
            'otherB' => [
                'info_market' => $postData['other_b_manu'],
                'product_name' => isset($postData['other_b_product']) ? $postData['other_b_product'] : '',
                'size' => $postData['other_b_size'],
                'serial' => $postData['other_b_serial'],
            ],
            'otherA' => [
                'info_market' => $postData['other_a_manu'],
                'product_name' => isset($postData['other_a_product']) ? $postData['other_a_product'] : '',
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

    /**
     * @param $postData
     * @return bool|void
     */
    public function saveCsv($postData)
    {
        if (file_exists(getcwd() . '/data/pdf/' . $postData['D03_DEN_NO'] . '.pdf')) {
            return true;
        }
        $totalTaisa = 0;
        $totalSuryo = 0;
        for ($i = 1; $i < 11; ++$i) {
            if ((int)$postData['D05_COM_CD' . $i] && in_array((int)$postData['D05_COM_CD' . $i], range(42000, 42999))) {
                $totalSuryo += $postData['D05_SURYO' . $i];
                $totalTaisa = $totalTaisa + 1;
            }
        }

        if ($totalTaisa) {
            $postData['D05_SURYO'] = $totalSuryo;
            return csv::writecsv($postData);
        }

        return csv::deletecsv($postData);
    }

    /**
     * @param $postData
     * @return bool
     */
    public function deleteCsv($postData)
    {
        return csv::deletecsv($postData);
    }
}
