<?php

namespace backend\modules\listworkslip\controllers;

use app\models\Udenpyo;
use app\models\Sdptd01customer;
use app\models\Sdptd02car;
use app\models\Sdptd03denpyo;
use app\models\Sdptd04denpyosagyo;
use app\models\Sdptd05denpyocom;
use app\models\Sdptm01sagyo;
use app\models\Sdptm05com;
use backend\components\api;
use backend\components\confirm;
use backend\components\csv;
use backend\components\utilities;
use backend\controllers\WsController;
use Yii;
use yii\helpers\BaseUrl;

class DetailController extends WsController
{
    public function actionIndex()
    {
        $api = new api();
        $data = [];
        $filter['detail_no'] = Yii::$app->request->get('den_no');
        $obj = new Sdptd03denpyo();
        $obj_job = new Sdptm01sagyo();
        $cus = new Sdptd01customer();

        $job[''] = '';
        $all_job = $obj_job->getData();
        foreach ($all_job as $k => $v) {
            $job[$v['M01_SAGYO_NO']] = $v['M01_SAGYO_NAMEN'];
        }

        $type = '';
        $card = '';

        if (Yii::$app->request->get('add') == 'true') {
            $cookie = Yii::$app->request->cookies;
            $type_redirect = $cookie->getValue('cus_info');
            if (isset($type_redirect['type_redirect'])) {
                $type = $type_redirect['type_redirect'];
            }

            if (isset($type_redirect['member_carNo'])) {
                $card = $type_redirect['member_carNo'];
            }

            if (isset($type_redirect['D01_KAKE_CARD_NO'])) {
                $card = $type_redirect['D01_KAKE_CARD_NO'];
            }
        }

        $data['type'] = $type;
        $data['card'] = $card;

        $detail = $obj->getDataSearch($filter);
        if (empty($detail)) {
            return $this->redirect(BaseUrl::base(true) . '/list-workslip');
        }

        $data['detail'] = $detail[0];
        $data['detail']['D02_SYAKEN_CYCLE'] = $this->getCar([
            'D02_CUST_NO' => $data['detail']['D03_CUST_NO'],
            'D02_CAR_SEQ' => $data['detail']['D03_CAR_SEQ']
        ]);

        $cus_info = $cus->findOne($data['detail']['D03_CUST_NO']);
        $data['detail']['D01_UKE_TAN_NAMEN'] = $cus_info['D01_UKE_TAN_NAMEN'];
        if (isset($cus_info['D01_KAIIN_CD'])) {
            $info = $api->getMemberInfo($cus_info['D01_KAIIN_CD']);
            $data['detail']['D01_CUST_NAMEN'] = $info['member_kaiinName'];
            $data['detail']['D01_CUST_NAMEK'] = $info['member_kaiinKana'];
        }

        if ($cus_info['D01_KAIIN_CD'] != '') {
            $car_api = $api->getInfoListCar($cus_info['D01_KAIIN_CD']);

            foreach ($car_api['car_carSeq'] as $k => $v) {
                if ($v == $data['detail']['D03_CAR_SEQ']) {
                    $data['detail']['D02_SYAKEN_CYCLE'] = $car_api['car_syakenCycle'][$k];
                }
            }
        }

        $data['detail']['sagyo'] = $this->getSagyo($data['detail']['D03_DEN_NO']);
        $data['detail']['product'] = $this->getProduct($data['detail']['D03_DEN_NO']);

        $data['job'] = $job;
        $data['status'] = Yii::$app->params['status'];
        $data['check_file'] = $this->checkFile($filter['detail_no']);
        $data['check_csv'] = file_exists(getcwd() . '/data/csv/' . $filter['detail_no'] . '.csv') ? 1 : 0;

        $data['csv'] = csv::readcsv(['D03_DEN_NO' => $filter['detail_no']]);
        $data['confirm'] = confirm::readconfirm(['D03_DEN_NO' => $filter['detail_no']]);
        $data['sign'] = \backend\components\sign::read(Yii::$app->request->get('den_no'));

        $data['ss'] = '';
        $login_info = Yii::$app->session->get('login_info');
        if (isset($login_info['M50_SS_CD']) && $login_info['M50_SS_CD'] != '') {
            $data['ss'] = $login_info['M50_SS_CD'];
        }

        Yii::$app->params['titlePage'] = '作業伝票詳細';
        Yii::$app->view->title = '作業伝票詳細';
        return $this->render('index', $data);
    }

    public function getCar($filters = [])
    {
        $obj = new Sdptd02car();
        $car_info = $obj->getData($filters);
        if (empty($car_info)) {
            return '';
        } else {
            return $car_info[0]['D02_SYAKEN_CYCLE'];
        }
    }

    public function getSagyo($den_no)
    {
        $obj = new Sdptd04denpyosagyo();
        $job_info = $obj->getData(['D04_DEN_NO' => $den_no]);
        $job = [];
        foreach ($job_info as $k => $v) {
            $job[$k]['D04_SAGYO_NO'] = $v['D04_SAGYO_NO'];
        }
        return $job;
    }

    public function getProduct($den_no)
    {
        $obj = new Sdptd05denpyocom();
        $product_info = $obj->getData(['D05_DEN_NO' => $den_no]);
        $product = [];
        foreach ($product_info as $k => $v) {
            $product[$k]['D05_SURYO'] = $v['D05_SURYO'];
            $product[$k]['D05_TANKA'] = $v['D05_TANKA'];
            $product[$k]['D05_KINGAKU'] = $v['D05_KINGAKU'];
            $product[$k]['D05_COM_CD'] = $v['D05_COM_CD'];
            $product[$k]['D05_NST_CD'] = $v['D05_NST_CD'];

            //$obj_sdptm05com = Sdptm05com::findOne([$v['D05_COM_CD'], $v['D05_NST_CD']]);
            $uDenpyo = new Udenpyo();
			$obj_sdptm05com =  $uDenpyo->getTm05Com(['M05_COM_CD' => $v['D05_COM_CD'],'M05_NST_CD' => $v['D05_NST_CD']]);
			if(isset($obj_sdptm05com[0]['M05_COM_NAMEN'])){
				$product[$k]['M05_COM_NAMEN'] = $obj_sdptm05com[0]['M05_COM_NAMEN'];
				$product[$k]['M05_LIST_PRICE'] = $obj_sdptm05com[0]['M05_LIST_PRICE'];
			}
        }
        return $product;
    }

    public function actionStatus()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $den_no = Yii::$app->request->post('den_no');
            $obj = Sdptd03denpyo::findOne($den_no);
            if (!isset($obj)) {
                return $this->redirect(BaseUrl::base(true) . '/list-workslip');
            }
            $status = $obj['D03_STATUS'];
            $status_update = $status == 0 ? 1 : 0;
            $obj->setData(['D03_STATUS' => $status_update], $den_no);
            if ($obj->saveData()) {
                Yii::$app->session->setFlash('success', '変更しました。');
                $url = $url = Yii::$app->session->has('url_list_workslip') ? Yii::$app->session->get('url_list_workslip') : \yii\helpers\BaseUrl::base(true) . '/list-workslip';
                return $this->redirect($url);
            }
            Yii::$app->session->setFlash('error', '変更するできません。');
            return $this->redirect(BaseUrl::base(true) . '/detail-workslip?den_no=' . $den_no);
        }
    }

    public function actionSign()
    {
        if (Yii::$app->request->isPost) {
            \backend\components\sign::save(
                Yii::$app->request->post('no'),
                Yii::$app->request->post('sign')
            );
        }
    }

    public function actionRemove()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $den_no = Yii::$app->request->post('den_no');
            if ($data = Sdptd03denpyo::findOne($den_no)) {
                $cus_no = $data['D03_CUST_NO'];
                $car_no = $data['D03_CAR_NO'];

                $obj = new Sdptd03denpyo();
                if ($obj->deleteData(['den_no' => $den_no, 'cus_no' => $cus_no, 'car_no' => $car_no])) {
                    Yii::$app->session->setFlash('success', '作業コード' . $den_no . 'を削除しました。');
                    if (file_exists(getcwd() . '/data/csv/' . $den_no . '.csv')) {
                        unlink(getcwd() . '/data/csv/' . $den_no . '.csv');
                    }
                    if (file_exists(getcwd() . '/data/confirm/' . $den_no . '.csv')) {
                        unlink(getcwd() . '/data/confirm/' . $den_no . '.csv');
                    }
                    if (file_exists(getcwd() . '/data/pdf/' . $den_no . '.pdf')) {
                        unlink(getcwd() . '/data/pdf/' . $den_no . '.pdf');
                    }
                    $url = Yii::$app->session->has('url_list_workslip') ? Yii::$app->session->get('url_list_workslip') : \yii\helpers\BaseUrl::base(true) . '/list-workslip';
                    return $this->redirect($url);
                }
            }
            Yii::$app->session->setFlash('error', '削除をできません。');
            return $this->redirect(BaseUrl::base(true) . '/detail-workslip?den_no=' . $den_no);
        }
    }


    public function actionPreview()
    {
        $branch = utilities::getAllBranch();
        $ss = $branch['all_ss'];
        $address = $branch['ss_address'];
        $tel = $branch['ss_tel'];

        $api = new api();
        $data = [];
        $filter['detail_no'] = Yii::$app->request->get('den_no');
        $cus = new Sdptd01customer();
        $obj = new Sdptd03denpyo();
        $obj_job = new Sdptm01sagyo();
        $car = new Sdptd02car();

        $job[''] = '';
        $all_job = $obj_job->getData();
        foreach ($all_job as $k => $v) {
            $job[$v['M01_SAGYO_NO']] = $v['M01_SAGYO_NAMEN'];
        }

        $detail = $obj->getDataSearch($filter);
        if (empty($detail)) {
            return $this->redirect(BaseUrl::base(true) . '/list-workslip');
        }

        $data['detail'] = $detail[0];
//get info car
        $data['car'] = $car->getData([
            'D02_CAR_SEQ' => $data['detail']['D03_CAR_SEQ'],
            'D02_CUST_NO' => $data['detail']['D03_CUST_NO'],
        ]);
        $data['car']['D02_MAKER_CD'] = '';
        $data['car']['D02_SHONENDO_YM'] = '';
        $data['car']['D02_TYPE_CD'] = '';
        $data['car']['D02_GRADE_CD'] = '';

        $data['detail']['D02_SYAKEN_CYCLE'] = '';

        if (isset($data['car'][0])) {
            $data['detail']['D02_SYAKEN_CYCLE'] = $this->getCar([
                'D02_CUST_NO' => $data['detail']['D03_CUST_NO'],
                'D02_CAR_NO' => $data['detail']['D03_CAR_NO']
            ]);
            $data['detail']['D01_CARD_NO'] = '';
            $data['car'] = $data['car'][0];

            $maker_code = $data['car']['D02_MAKER_CD'];
            $model_code = $data['car']['D02_MODEL_CD'];
            $year = substr($data['car']['D02_SHONENDO_YM'], 0, 4) ;
            $type_code = $data['car']['D02_TYPE_CD'];
            $grade_code = $data['car']['D02_GRADE_CD'];

            $maker = $api->getListMaker();
            foreach ($maker as $key => $value) {
                if (isset($value['maker_code']) && $value['maker_code'] == $maker_code) {
                    $data['car']['D02_MAKER_CD'] = $value['maker'];
                }
            }
            if ($data['car']['D02_MAKER_CD'] == '-111'){
                $data['car']['D02_MAKER_CD'] = '';
            }

            $type = $api->getListTypeCode($maker_code, $model_code,$year);
            foreach ($type as $key => $value) {
                if (isset($value['type_code']) && $value['type_code'] == $type_code) {
                    $data['car']['D02_TYPE_CD'] = $value['type'];
                }
            }

            $grade = $api->getListGradeCode($maker_code, $model_code,$year,$type_code);
            foreach ($grade as $key => $value) {
                if (isset($value['grade_code']) && $value['grade_code'] == $grade_code) {
                    $data['car']['D02_GRADE_CD'] = $value['grade'];
                }
            }
        }

//getCustomer_API
        $cus_info = $cus->findOne($data['detail']['D03_CUST_NO']);
        $data['detail']['D01_UKE_TAN_NAMEN'] = $cus_info['D01_UKE_TAN_NAMEN'];
        if (isset($cus_info['D01_KAIIN_CD'])) {
            $info = $api->getMemberInfo($cus_info['D01_KAIIN_CD']);
            $card_info = $api->getInfoListCard($cus_info['D01_KAIIN_CD']);
            if (isset($card_info['card_cardKbn'])) {
                foreach ($card_info['card_cardKbn'] as $k => $v)
                {
                    if ((int)$v == 1) {
                        $data['detail']['D01_CARD_NO'] = $card_info['card_cardBangou'][$k];
                    }
                }
            }

            $data['detail']['D01_CUST_NAMEN'] = $info['member_kaiinName'];
            $data['detail']['D01_CUST_NAMEK'] = $info['member_kaiinKana'];
            $data['detail']['D01_TEL_NO'] = $info['member_telNo2'];
            $data['detail']['D01_MOBTEL_NO'] = $info['member_telNo1'];
            $data['detail']['D01_BIRTHDAY'] = $info['member_birthday'];
            $data['detail']['D01_ADDR'] = $info['member_address'];
            $data['detail']['D01_YUBIN_BANGO'] = $info['member_yuubinBangou'];
        }
//getCar_API

        if ($cus_info['D01_KAIIN_CD'] != '') {
            $car_api = $api->getInfoListCar($cus_info['D01_KAIIN_CD']);

            foreach ($car_api['car_carSeq'] as $k => $v) {
                if ($v == $data['detail']['D03_CAR_SEQ']) {
                    $data['detail']['D03_CAR_NAMEN'] = $car_api['car_modelNamen'][$k] ? $car_api['car_modelNamen'][$k] : $car_api['car_carName'][$k];
                    $data['detail']['D02_SYAKEN_CYCLE'] = $car_api['car_syakenCycle'][$k];

                    $maker_code = $car_api['car_makerCd'][$k];
                    $model_code = $car_api['car_modelCd'][$k];
                    $year = substr($car_api['car_syoNendoInsYmd'][$k], 0, 4) ;
                    $type_code = $car_api['car_typeCd'][$k];
                    $grade_code = $car_api['car_gradeCd'][$k];

                    $type = $api->getListTypeCode($maker_code, $model_code,$year);

                    foreach ($type as $key => $value) {
                        if (isset($value['type_code']) && $value['type_code'] == $type_code) {
                            $data['car']['D02_TYPE_CD'] = $value['type'];
                        }
                    }

                    $grade = $api->getListGradeCode($maker_code, $model_code,$year,$type_code);
                    foreach ($grade as $key => $value) {
                        if (isset($value['grade_code']) && $value['grade_code'] == $grade_code) {
                            $data['car']['D02_GRADE_CD'] = $value['grade'];
                        }
                    }

                    $data['car']['D02_SHONENDO_YM'] = $car_api['car_syoNendoInsYmd'][$k];
                    $data['car']['D02_MAKER_CD'] = $car_api['car_makerNamen'][$k];
                }
            }
        }

        $data['ss'] = isset($ss[$data['detail']['D03_SS_CD']]) ? $ss[$data['detail']['D03_SS_CD']] : '';
        $data['address'] = isset($address[$data['detail']['D03_SS_CD']]) ? $address[$data['detail']['D03_SS_CD']] : '';
        $data['tel'] = isset($tel[$data['detail']['D03_SS_CD']]) ? $tel[$data['detail']['D03_SS_CD']] : '';
        $data['detail']['sagyo'] = $this->getSagyo($data['detail']['D03_DEN_NO']);
        $data['detail']['product'] = $this->getProduct($data['detail']['D03_DEN_NO']);

        $data['job'] = $job;
        $data['status'] = Yii::$app->params['status'];

        $data['csv'] = csv::readcsv(['D03_DEN_NO' => $filter['detail_no']]);
        $data['confirm'] = confirm::readconfirm(['D03_DEN_NO' => $filter['detail_no']]);
        $data['sign'] = \backend\components\sign::read($filter['detail_no']);

        $this->layout = '@app/views/layouts/preview';
        Yii::$app->view->title = '作業確認書';
        Yii::$app->params['titlePage'] = '作業確認書';
        return $this->render('preview', $data);
    }

    public function checkFile($den_no)
    {
        /*
         * 0: no file
         * 1: file exist and not print
         * 2: printed
         */
        if (file_exists(getcwd() . '/data/pdf/' . $den_no . '.pdf')) {
            if (isset(confirm::readconfirm(['D03_DEN_NO' => $den_no])['status'])) {
                return confirm::readconfirm(['D03_DEN_NO' => $den_no])['status'] == 0 ? 1 : 2;
            }
        } else {
            return 0;
        }
    }

    public function actionUpdatestatus()
    {
        $den_no = Yii::$app->request->post('den_no');
        $post = confirm::readconfirm(['D03_DEN_NO' => $den_no]);
        $post['D03_DEN_NO'] = $den_no;
        $post['status'] = Yii::$app->request->post('status');
        confirm::writeconfirm($post);
        $link = BaseUrl::base(true).'/data/pdf/'.$den_no.'.html';
        return $link;
    }
}
