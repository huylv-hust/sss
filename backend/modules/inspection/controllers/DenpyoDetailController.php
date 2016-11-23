<?php

namespace backend\modules\inspection\controllers;

use app\models\Udenpyo;
use app\models\Sdptd01customer;
use app\models\Sdptd02car;
use app\models\Sdptd03denpyo;
use app\models\Sdptd04denpyosagyo;
use app\models\Sdptd05denpyocom;
use app\models\Sdptm01sagyo;
use app\models\FeeBasic;
use app\models\DiscountPackages;
use app\models\FeeRegistration;
use app\models\DenpyoInspection;
use app\models\Comment;
use yii\data\Pagination;
use app\models\ParentDiscount;
use backend\components\api;
use backend\components\confirm;
use backend\components\csv;
use backend\components\utilities;
use backend\controllers\WsController;
use Yii;
use yii\helpers\BaseUrl;

class DenpyoDetailController extends WsController
{
    /**
     * @return string
     */
    public function actionList()
    {
        $utilities = new utilities();
        $branch = $utilities->getAllBranch();
        $data['all_ss'] = $branch['all_ss'];
        $denpyoInspection = new DenpyoInspection();
        $denpyoSDP03 = new Sdptd03denpyo();
        $filters = Yii::$app->request->get();
        $query_string = empty($filters) ? '' : '?' . http_build_query($filters);
        Yii::$app->session->set('url_list_inspection', BaseUrl::base() . '/shaken/denpyo/list' . $query_string);

        if (empty($filters)) {
            $filters['start_time'] = date('Ymd');
            $filters['end_time'] = date('Ymd');
        }

        $data['filters'] = $filters;
        $count = $denpyoSDP03->countDataInspection($filters);

        $data['pagination'] = new Pagination([
            'totalCount' => $count,
            'defaultPageSize' => Yii::$app->params['defaultPageSize'],
        ]);

        $data['page'] = Yii::$app->request->get('page');
        $data['filters']['limit'] = $data['pagination']->limit;
        $data['filters']['offset'] = $data['pagination']->offset;
        $data['list'] = $denpyoSDP03->getDataInspection($data['filters']);
        $arr_denpyo_no = [];
        if (count($data['list'])) {
            $denpyo_inspection_no = '';
            foreach ($data['list'] as $row) {
                $denpyo_inspection_no .= $row['DENPYO_SUGGEST_NO'] . ',';
            }
            $denpyo_inspection_no = trim($denpyo_inspection_no, ',');
            $list_denpyo_suggest = $denpyoSDP03->getDataSearch(['DENPYO_NO_IN' => $denpyo_inspection_no]);
            $arr_denpyo_no = array_column($list_denpyo_suggest, 'D03_DEN_NO');
        }
        $data['arr_denpyo_no'] = $arr_denpyo_no;
        if (!empty($data['list'])) {
            foreach ($data['list'] as &$row) {
                $row['sign'] = \backend\components\sign::read($row['D03_DEN_NO']);
            }
        }

        Yii::$app->params['titlePage'] = '車検履歴';
        Yii::$app->view->title = '車検履歴';
        return $this->render('list', $data);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionDetail($id)
    {

        $api = new api();
        $data = [];
        $filter['detail_no'] = $id;
        $obj = new Sdptd03denpyo();
        $obj_job = new Sdptm01sagyo();
        $cus = new Sdptd01customer();
        $denpyo_inspection_obj = new DenpyoInspection();

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
            return $this->redirect(BaseUrl::base(true) . '/shaken/denpyo/list');
        }

        $denpyo_inspection = $denpyo_inspection_obj->getData(['DENPYO_NO' => $id]);
        if (!count($denpyo_inspection)) {
            return $this->redirect(\yii\helpers\BaseUrl::base(true) . '/shaken/denpyo/list');
        }

        $data['detail'] = $detail[0];
        $data['detail_suggest'] = current($obj->getDataSearch(['detail_no' => $denpyo_inspection['0']['DENPYO_SUGGEST_NO']]));
        $data['denpyo_inspection'] = current($denpyo_inspection);
        $data['detail']['D02_SYAKEN_CYCLE'] = $this->getCar([
            'D02_CUST_NO' => $data['detail']['D03_CUST_NO'],
            'D02_CAR_SEQ' => $data['detail']['D03_CAR_SEQ']
        ]);

        $cus_info = $cus->findOne($data['detail']['D03_CUST_NO']);
        $data['detail']['D01_UKE_TAN_NAMEN'] = $cus_info['D01_UKE_TAN_NAMEN'];

        if ($cus_info['D01_KAIIN_CD'] != '') {
            $car_api = $api->getInfoListCar($cus_info['D01_KAIIN_CD']);
            /*
            $info = $api->getMemberInfo($cus_info['D01_KAIIN_CD']);
            $data['detail']['D01_CUST_NAMEN'] = $info['member_kaiinName'];
            $data['detail']['D01_CUST_NAMEK'] = $info['member_kaiinKana'];
             *
             */
            foreach ($car_api['car_carSeq'] as $k => $v) {
                if ($v == $data['detail']['D03_CAR_SEQ']) {
                    $data['detail']['D02_SYAKEN_CYCLE'] = $car_api['car_syakenCycle'][$k];
                }
            }
        }

        $data['detail']['sagyo'] = $this->getSagyo($data['detail']['D03_DEN_NO']);
        $data['detail']['product'] = $this->getProduct($data['detail']['D03_DEN_NO']);
        $data['detail']['product_suggest'] = $this->getProduct($denpyo_inspection['0']['DENPYO_SUGGEST_NO']);
        $data['detail']['fee_basic'] = [];

        if ($denpyo_inspection['0']['FEE_BASIC_ID']) {
            $data['detail']['fee_basic'] = $this->getFeeBasic($denpyo_inspection['0']);
        }
        $data['detail']['fee_registion'] = [];
        if ($denpyo_inspection['0']['FEE_REGISTRATION_ID']) {
            $data['detail']['fee_registion'] = $this->getFeeRegistraion($denpyo_inspection['0']);
        }

        $data['detail']['parent_discount'] = [];
        if ($denpyo_inspection['0']['PARENT_DISCOUNT_ID']) {
            $data['detail']['parent_discount'] = $this->getParentDiscount($denpyo_inspection['0']);
        }

        $data['detail']['discount_packages'] = [];
        if ($denpyo_inspection['0']['PARENT_DISCOUNT_ID']) {
            $data['detail']['discount_packages'] = $this->getDiscountPackages($denpyo_inspection['0']);
        }

        $data['detail']['discount'] = [];
        if (trim($denpyo_inspection['0']['DISCOUNTS'], ',')) {
            $data['detail']['discount'] = $this->getDiscount($denpyo_inspection['0']);
        }
        $data['detail']['comments'] = [];
        if (trim($denpyo_inspection['0']['COMMENTS'], ',')) {
            $data['detail']['comments'] = $this->getComment($denpyo_inspection['0']);
        }

        $data['job'] = $job;
        $data['status'] = Yii::$app->params['status'];
        $data['check_file'] = $this->checkFile($filter['detail_no']);
        $data['check_csv'] = file_exists(getcwd() . '/data/csv/' . $filter['detail_no'] . '.csv') ? 1 : 0;

        $data['csv'] = csv::readcsv(['D03_DEN_NO' => $filter['detail_no']]);
        $data['confirm'] = confirm::readconfirm(['D03_DEN_NO' => $filter['detail_no']]);
        $data['sign'] = \backend\components\sign::read($id);

        $data['ss'] = '';
        $login_info = Yii::$app->session->get('login_info');
        if (isset($login_info['M50_SS_CD']) && $login_info['M50_SS_CD'] != '') {
            $data['ss'] = $login_info['M50_SS_CD'];
        }

        //Case removed Osusume from SS
        if (!$obj->getData(['D03_DEN_NO' => $denpyo_inspection['0']['DENPYO_SUGGEST_NO']])) {
            Yii::$app->session->setFlash('check_remove', 'おすすめ整備情報が削除されたため、正常に表示できていません。');
        }
        //If there is no car info, raise the error message
        $check_car = Sdptd02car::findOne([
            'D02_CUST_NO' => $data['detail']['D03_CUST_NO'],
            'D02_CAR_SEQ' => $data['detail']['D03_CAR_SEQ'],
            'D02_CAR_NO' => $data['detail']['D03_CAR_NO'],
        ]);
        if (!$check_car && !$cus_info['D01_KAIIN_CD']) {
            Yii::$app->session->setFlash('check_remove_car', '車両情報が削除されため、正常に表示できていません。');
        }

        Yii::$app->params['titlePage'] = '作業伝票詳細';
        Yii::$app->view->title = '作業伝票詳細';
        return $this->render('detail', $data);
    }

    /**
     * @return string
     */
    public function actionUpdatestatus()
    {
        $den_no = Yii::$app->request->post('den_no');
        $post = confirm::readconfirm(['D03_DEN_NO' => $den_no]);
        $post['D03_DEN_NO'] = $den_no;
        $post['status'] = Yii::$app->request->post('status');
        confirm::writeconfirm($post);
        $link = BaseUrl::base(true) . '/data/pdf/' . $den_no . '.html';
        return $link;
    }

    /**
     * @return \yii\web\Response
     */
    public function actionRemove()
    {
        $request = Yii::$app->request;
        $obj = new Sdptd03denpyo();
        $obj_inspection = new DenpyoInspection();
        if ($request->isPost) {
            $den_no = Yii::$app->request->post('den_no');
            $data_inspection = $obj_inspection->findOne($den_no);
            $data = $obj->findOne($den_no);
            $data_suggest = $obj->findOne($data_inspection['DENPYO_SUGGEST_NO']);

            $transaction = $obj->getDb()->beginTransaction();
            try {
                $res1 = $this->deleteInspection($data, $den_no, $obj);
                if ($res1) {
                    $res2 = $this->deleteInspection($data_suggest, $data_inspection['DENPYO_SUGGEST_NO'], $obj, 'suggest');
                    if ($res2) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', '作業コード' . $den_no . 'を削除しました。');
                        $url = Yii::$app->session->has('url_list_inspection') ? Yii::$app
                            ->session->get('url_list_inspection') : \yii\helpers\BaseUrl::base(true) . '/shaken/denpyo/list';
                        return $this->redirect($url);
                    }
                }
                $transaction->rollBack();
            } catch (Exception $e) {
                $transaction->rollBack();
            }


            Yii::$app->session->setFlash('error', '削除をできません。');
            return $this->redirect(BaseUrl::base(true) . '/shaken/denpyo/detail/' . $den_no);
        }
    }


    /**
     * @param $data
     * @param $den_no
     * @param $obj
     * @return bool
     */
    private function deleteInspection($data, $den_no, $obj, $type = 'denpyo')
    {
        $cus_no = $data['D03_CUST_NO'];
        $car_no = $data['D03_CAR_NO'];
        if ($obj->deleteDataInspection(['den_no' => $den_no, 'cus_no' => $cus_no, 'car_no' => $car_no], $type)) {
            if (file_exists(getcwd() . '/data/csv/' . $den_no . '.csv')) {
                unlink(getcwd() . '/data/csv/' . $den_no . '.csv');
            }
            if (file_exists(getcwd() . '/data/confirm/' . $den_no . '.csv')) {
                unlink(getcwd() . '/data/confirm/' . $den_no . '.csv');
            }
            if (file_exists(getcwd() . '/data/pdf/' . $den_no . '.pdf')) {
                unlink(getcwd() . '/data/pdf/' . $den_no . '.pdf');
            }

            return true;
        }
        return false;
    }

    /**
     * sign
     */
    public function actionSign()
    {
        if (Yii::$app->request->isPost) {
            \backend\components\sign::save(Yii::$app->request->post('no'), Yii::$app->request->post('sign'));
        }
    }

    /**
     * @return \yii\web\Response
     */
    public function actionStatus()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $den_no = Yii::$app->request->post('den_no');
            $obj = Sdptd03denpyo::findOne($den_no);
            if (!isset($obj)) {
                return $this->redirect(BaseUrl::base(true) . '/shaken/denpyo/list');
            }
            $status = $obj['D03_STATUS'];
            $status_update = $status == 0 ? 1 : 0;
            $obj->setData(['D03_STATUS' => $status_update], $den_no);
            if ($obj->saveData()) {
                Yii::$app->session->setFlash('success', '変更しました。');
                $url = $url = Yii::$app->session->has('url_list_inspection') ? Yii::$app->session->get('url_list_inspection') : \yii\helpers\BaseUrl::base(true) . '/shaken/denpyo/list';
                return $this->redirect($url);
            }
            Yii::$app->session->setFlash('error', '変更するできません。');
            return $this->redirect(BaseUrl::base(true) . '/shaken/denpyo/detail/' . $den_no);
        }
    }

    /**
     * @param array $filters
     * @return string
     */
    private function getCar($filters = [])
    {
        $obj = new Sdptd02car();
        $car_info = $obj->getData($filters);
        if (empty($car_info)) {
            return '';
        } else {
            return $car_info[0]['D02_SYAKEN_CYCLE'];
        }
    }

    /**
     * @param $den_no
     * @return array
     */
    private function getSagyo($den_no)
    {
        $obj = new Sdptd04denpyosagyo();
        $job_info = $obj->getData(['D04_DEN_NO' => $den_no]);
        $job = [];
        foreach ($job_info as $k => $v) {
            $job[$k]['D04_SAGYO_NO'] = $v['D04_SAGYO_NO'];
        }
        return $job;
    }

    /**
     * @param $den_no
     * @return array
     */
    private function getProduct($den_no)
    {
        $obj = new Sdptd05denpyocom();
        $uDenpyo = new Udenpyo();
        $product_info = $obj->getData(['D05_DEN_NO' => $den_no]);
        $product = [];
        foreach ($product_info as $k => $v) {
            $product[$k]['D05_SURYO'] = $v['D05_SURYO'];
            $product[$k]['D05_TANKA'] = $v['D05_TANKA'];
            $product[$k]['D05_KINGAKU'] = $v['D05_KINGAKU'];
            $product[$k]['D05_COM_CD'] = $v['D05_COM_CD'];
            $product[$k]['D05_NST_CD'] = $v['D05_NST_CD'];
            $obj_sdptm05com = $uDenpyo->getTm05Com(['M05_COM_CD' => $v['D05_COM_CD'], 'M05_NST_CD' => $v['D05_NST_CD']]);
            if (isset($obj_sdptm05com[0]['M05_COM_NAMEN'])) {
                $product[$k]['M05_COM_NAMEN'] = $obj_sdptm05com[0]['M05_COM_NAMEN'];
                $product[$k]['M05_LIST_PRICE'] = $obj_sdptm05com[0]['M05_LIST_PRICE'];
            }
        }

        return $product;
    }

    /**
     * @param $den_no
     * @return int
     */
    private function checkFile($den_no)
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

    /**
     * @param $data
     * @return mixed
     */
    private function getFeeBasic($data)
    {
        $feeBasic = new FeeBasic();
        $row = $feeBasic->getData(['ID' => $data['FEE_BASIC_ID']]);
        return current($row);
    }

    /**
     * @param $data
     * @return mixed
     */
    private function getFeeRegistraion($data)
    {
        $feeRegistion = new FeeRegistration();
        $row = $feeRegistion->getData(['ID' => $data['FEE_REGISTRATION_ID']]);
        return current($row);
    }

    /**
     * @param $data
     * @return mixed
     */
    private function getParentDiscount($data)
    {
        $parentDiscount = new ParentDiscount();
        $row = $parentDiscount->getData(['ID' => $data['PARENT_DISCOUNT_ID']]);
        return current($row);
    }

    /**
     * @param $data
     * @return array
     */
    private function getDiscountPackages($data)
    {
        $discountPackages = new DiscountPackages();
        $row = $discountPackages->getData(['PARENT_DISCOUNT_ID' => $data['PARENT_DISCOUNT_ID']]);
        return $row;
    }

    /**
     * @param $data
     * @return array
     */
    private function getDiscount($data)
    {
        $discount = new \app\models\Discounts();
        $row = $discount->getData(['ID_IN' => trim($data['DISCOUNTS'], ',')]);
        $_arr = [];
        foreach ($row as $temp) {
            $_arr[$temp['DISCOUNT_PACKAGES_ID']] = $temp;
        }

        return $_arr;
    }

    /**
     * @param $data
     * @return array
     */
    private function getComment($data)
    {
        $comments = new Comment();
        $row = $comments->getData(['ID_IN' => trim($data['COMMENTS'], ',')]);
        return $row;
    }
}
