<?php
namespace backend\modules\registworkslip\controllers;

use app\models\Sdptd01customer;
use app\models\Sdptd02car;
use app\models\Sdptm01sagyo;
use app\models\Udenpyo;
use backend\components\utilities;
use backend\controllers\WsController;
use yii\helpers\BaseUrl;
use backend\components\api;

class PreviewController extends WsController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isGet) {
            return $this->redirect(BaseUrl::base() . '/list-workslip');
        }

        $data['post'] = \Yii::$app->request->post();

        $data['count'] = 0;
        for($i=1; $i <= 10 ; $i++) {
            if($data['post']['D05_COM_CD'.$i]) {
                $data['count']++;
            }
        }

        if (isset($data['post']['D01_SS_CD'])) {
            $branch = utilities::getAllBranch();
            $ss = $branch['all_ss'];
            $address = $branch['ss_address'];
            $tel = $branch['ss_tel'];
            $api = new api();
            $cus = new Sdptd01customer();
            $car = new Sdptd02car();

            $obj_job = new Sdptm01sagyo();

            $job[''] = '';
            $all_job = $obj_job->getData();
            foreach ($all_job as $k => $v) {
                $job[$v['M01_SAGYO_NO']] = $v['M01_SAGYO_NAMEN'];
            }

            $data['ss'] = isset($ss[$data['post']['D01_SS_CD']]) ? $ss[$data['post']['D01_SS_CD']] : '';
            $data['address'] = isset($address[$data['post']['D01_SS_CD']]) ? $address[$data['post']['D01_SS_CD']] : '';
            $data['tel'] = isset($tel[$data['post']['D01_SS_CD']]) ? $tel[$data['post']['D01_SS_CD']] : '';

            foreach ($data['post']['LIST_NAME'] as $k => $v) {
                $data['post']['M05_COM_NAMEN' . $k] = $v;
            }

            $data['job'] = $job;
            $data['status'] = \Yii::$app->params['status'];


            $tanto = explode('[]', $data['post']['D03_TANTO_MEI_D03_TANTO_SEI']);
            if (!empty($tanto[0]) && !empty($tanto[1])) {
                $data['post']['tanto'] = $tanto[0] . $tanto[1];
            }

            $kakunin = explode('[]', $data['post']['D03_KAKUNIN_MEI_D03_KAKUNIN_SEI']);
            if (!empty($kakunin[0]) && !empty($kakunin[1])) {
                $data['post']['kakunin'] = $kakunin[0] . $kakunin[1];
            }

            $data['post']['ss_user'] = $this->getssUser($data['post']['D01_SS_CD']);
            $data['ss_user'] = $data['post']['M08_NAME_MEI_M08_NAME_SEI'] ? $data['post']['ss_user'][$data['post']['M08_NAME_MEI_M08_NAME_SEI']] : '';

//get info car
            if (isset($data['post']['D02_CAR_SEQ_SELECT']) && isset($data['post']['D01_CUST_NO'])) {
                $data['car'] = $car->getData([
                    'D02_CAR_SEQ' => $data['post']['D02_CAR_SEQ_SELECT'],
                    'D02_CUST_NO' => $data['post']['D01_CUST_NO'],
                ]);
            }

            $data['car']['D02_MAKER_CD'] = '';
            $data['car']['D02_SHONENDO_YM'] = '';
            $data['car']['D02_TYPE_CD'] = '';
            $data['car']['D02_GRADE_CD'] = '';

            if (isset($data['car'][0])){
                $data['post']['D01_CARD_NO'] = '';
                $data['car'] = $data['car'][0];
                $data['post']['D02_SYAKEN_CYCLE'] =  $data['car']['D02_SYAKEN_CYCLE'];

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
                    $data['car']['D02_MAKER_CD'] = 'その他';
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
//get Car from api
            if ($data['post']['D01_CUST_NO']) {
                $cus_info = $cus->findOne($data['post']['D01_CUST_NO']);
                if ($cus_info['D01_KAIIN_CD'] != '') {
                    $car_api = $api->getInfoListCar($cus_info['D01_KAIIN_CD']);

                    foreach ($car_api['car_carSeq'] as $k => $v) {
                        if ($data['post']['D02_CAR_SEQ_SELECT'] && $v == $data['post']['D02_CAR_SEQ_SELECT']) {
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
                            if (!$data['car']['D02_MAKER_CD']) {
                                if ($maker_code) {
                                    $data['car']['D02_MAKER_CD'] = 'その他';
                                }else{
                                    $data['car']['D02_MAKER_CD'] = '';
                                }
                            }
                        }
                    }
                }
            }

            $this->layout = '@app/views/layouts/preview';
            \Yii::$app->view->title = '作業指示書';
            \Yii::$app->params['titlePage'] = '作業指示書';

            return $this->render('index', $data);
        }
        return $this->redirect(BaseUrl::base(true) . '/regist-workslip');
    }

    public function getssUser($sscode)
    {
        $uDenpyo = new Udenpyo();
        $tm08Sagyosya = $uDenpyo->getTm08Sagyosya(['M08_SS_CD' => $sscode]);
        $ssUser = [];
        if (count($tm08Sagyosya)) {
            foreach ($tm08Sagyosya as $tmp) {
                $ssUser[$tmp['M08_JYUG_CD']] = $tmp['M08_NAME_SEI'] . $tmp['M08_NAME_MEI'];
            }
        }
        return $ssUser;
    }
}
