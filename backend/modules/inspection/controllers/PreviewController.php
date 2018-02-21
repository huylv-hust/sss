<?php
namespace backend\modules\inspection\controllers;

use app\models\DenpyoInspection;
use app\models\Discounts;
use app\models\FeeBasic;
use app\models\FeeRegistration;
use app\models\Sdptd01customer;
use app\models\Sdptd03denpyo;
use app\models\UdenpyoInspection;
use backend\components\api;
use backend\components\confirm;
use backend\components\csv;
use backend\components\sign;
use backend\components\utilities;
use backend\controllers\WsController;
use Yii;
use yii\helpers\BaseUrl;

class PreviewController extends WsController
{
    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionIndex($id)
    {
        if (Sdptd03denpyo::findOne(['D03_DEN_NO' => $id])) {
            if (DenpyoInspection::findOne(['DENPYO_SUGGEST_NO' => $id])) {
                Yii::$app->session->setFlash('error', '伝票は見つかりません。');
                $url = Yii::$app->session->get('url_list_denpyo') ? Yii::$app->session->get('url_list_denpyo') :
                    BaseUrl::base(true) . '/shaken/denpyo/list';

                return $this->redirect($url);
            }
            $data['denpyo'] = $denpyo = Sdptd03denpyo::findOne(['D03_DEN_NO' => $id]);
            $ss_cd = $denpyo['D03_SS_CD'];
            $branch = utilities::getAllBranch();
            $UdenpyoInspection = new UdenpyoInspection();
            $data['ss'] = isset($branch['all_ss'][$ss_cd]) ? $branch['all_ss'][$ss_cd] : '';
            $data['tel'] = isset($branch['ss_tel'][$ss_cd]) ? $branch['ss_tel'][$ss_cd] : '';
            $data['address'] = isset($branch['ss_address'][$ss_cd]) ? $branch['ss_address'][$ss_cd] : '';
            $data['customer'] = $this->getUser($denpyo['D03_CUST_NO']);
            $data['car'] = $UdenpyoInspection->getCar($denpyo['D03_CUST_NO'], $denpyo['D03_CAR_SEQ'], $denpyo['D03_CAR_NO'], $data['customer']['D01_KAIIN_CD']);
            if (!$data['car']) {
                Yii::$app->session->setFlash('error', '車両情報が削除されため、正常に表示できていません。');
            }
            $data['product'] = $UdenpyoInspection->getProduct($id);
            $data['status'] = Yii::$app->params['status'];
            $data['csv'] = csv::readcsv(['D03_DEN_NO' => $id]);
            $data['confirm'] = confirm::readconfirm(['D03_DEN_NO' => $id]);
            $data['sign'] = sign::read($id);

            $this->layout = '@app/views/layouts/preview';
            Yii::$app->params['titlePage'] = '作業確認書';
            Yii::$app->view->title = '作業確認書';

            return $this->render('preview', $data);
        }
        Yii::$app->session->setFlash('error', '伝票は見つかりません。');
        $url = Yii::$app->session->get('url_list_denpyo') ? Yii::$app->session->get('url_list_denpyo') :
            BaseUrl::base(true) . '/shaken/denpyo/list';

        return $this->redirect($url);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionOsusume($id)
    {
        if ($data = $this->getPreview($id)) {
            $this->layout = '@app/views/layouts/inspection/preview';
            Yii::$app->params['titlePage'] = '整備おすすめ';
            Yii::$app->view->title = '整備おすすめ';

            return $this->render('osusume', $data);
        }
        Yii::$app->session->setFlash('error', '伝票は見つかりません。');
        $url = Yii::$app->session->get('url_list_denpyo') ? Yii::$app->session->get('url_list_denpyo') :
            BaseUrl::base(true) . '/shaken/denpyo/list';
        return $this->redirect($url);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionSeisan($id)
    {
        if ($data = $this->getPreview($id)) {
            $this->layout = '@app/views/layouts/inspection/preview';
            Yii::$app->params['titlePage'] = 'ご精算書';
            Yii::$app->view->title = 'ご精算書';

            return $this->render('seisan', $data);
        }
        Yii::$app->session->setFlash('error', '伝票は見つかりません。');
        $url = Yii::$app->session->get('url_list_denpyo') ? Yii::$app->session->get('url_list_denpyo') :
            BaseUrl::base(true) . '/shaken/denpyo/list';
        return $this->redirect($url);
    }

    /**
     * @param $id
     * @return bool
     */
    public function getPreview($id)
    {
        if ($inspection = DenpyoInspection::findOne(['DENPYO_SUGGEST_NO' => $id])) {
            $branch = utilities::getAllBranch();
            $UdenpyoInspection = new UdenpyoInspection();
            $den_no = $inspection['DENPYO_NO'];
            $data['denpyo'] = $denpyo = Sdptd03denpyo::findOne($den_no);
            $data['suggest'] = Sdptd03denpyo::findOne($id);
            $ss_cd = $denpyo['D03_SS_CD'];
            $data['customer'] = $this->getUser($denpyo['D03_CUST_NO']);
            $data['car'] = $UdenpyoInspection->getCar($denpyo['D03_CUST_NO'], $denpyo['D03_CAR_SEQ'], $denpyo['D03_CAR_NO'], $data['customer']['D01_KAIIN_CD']);
            if (!$data['car']) {
                Yii::$app->session->setFlash('error', '車両情報が削除されため、正常に表示できていません。');
            }
            $data['product'] = $UdenpyoInspection->getProduct($id);
            $data['products'] = $UdenpyoInspection->getProduct($den_no);
            $data['ss'] = isset($branch['all_ss'][$ss_cd]) ? $branch['all_ss'][$ss_cd] : '';
            $data['tel'] = isset($branch['ss_tel'][$ss_cd]) ? $branch['ss_tel'][$ss_cd] : '';
            $data['address'] = isset($branch['ss_address'][$ss_cd]) ? $branch['ss_address'][$ss_cd] : '';
            $data['zipcode'] = $this->getZipcode($ss_cd);
            $data['money'] = $this->getMoney($den_no);
            $data['fee_basic'] = isset(FeeBasic::findOne($inspection['FEE_BASIC_ID'])['VALUE']) ? FeeBasic::findOne($inspection['FEE_BASIC_ID'])['VALUE'] : '';
            $data['stamp_fee'] = isset(FeeRegistration::findOne($inspection['FEE_REGISTRATION_ID'])['STAMP_FEE']) ? FeeRegistration::findOne($inspection['FEE_REGISTRATION_ID'])['STAMP_FEE'] : '';
            $data['mandatory_insurance'] = isset(FeeRegistration::findOne($inspection['FEE_REGISTRATION_ID'])['MANDATORY_INSURANCE']) ? FeeRegistration::findOne($inspection['FEE_REGISTRATION_ID'])['MANDATORY_INSURANCE'] : '';
            $data['weight_tax'] = $inspection['WEIGHT_TAX'];
            $data['earnest_money'] = $inspection['EARNEST_MONEY'];
            $data['denpyo']['D03_DEN_NO'] = $id;

            return $data;
        }

        return false;
    }

    /**
     * @param $cus_no
     * @return mixed
     */
    public function getUser($cus_no)
    {
        $obj = new Sdptd01customer();
        $data = $obj->getData(['D01_CUST_NO' => $cus_no])[0];
        /*
        if ($data['D01_KAIIN_CD'] != '') {
            $api = new api();
            $info = $api->getMemberInfo($data['D01_KAIIN_CD']);
            $data['D01_CUST_NAMEN'] = $info['member_kaiinName'];
            $data['D01_CUST_NAMEK'] = $info['member_kaiinKana'];
            $data['D01_YUBIN_BANGO'] = $info['member_yuubinBangou'];
            $data['D01_ADDR'] = $info['member_address'];
            $data['D01_TEL_NO'] = $info['member_telNo1'];
            $data['D01_MOBTEL_NO'] = $info['member_telNo2'];
            $data['D01_BIRTHDAY'] = $info['member_birthday'];

            $card_info = $api->getInfoListCard($data['D01_KAIIN_CD']);
            if (isset($card_info['card_cardKbn'])) {
                foreach ($card_info['card_cardKbn'] as $k => $v) {
                    if ((int)$v == 1) {
                        $data['D01_CARD_NO'] = $card_info['card_cardBangou'][$k];
                    }
                }
            }
        }
        */
        return $data;
    }

    /**
     * @param $den_no
     * @return mixed
     */
    public function getMoney($den_no)
    {
        $obj = new DenpyoInspection();
        $data = $obj->getData(['DENPYO_NO' => $den_no])[0];
        if ($data['DISCOUNTS']) {
            $discount = null;
            $data['DISCOUNTS'] = trim($data['DISCOUNTS'], ',');
            $data['DISCOUNTS'] = explode(',', $data['DISCOUNTS']);
            $data['DISCOUNTS'] = Discounts::findAll(['ID' => $data['DISCOUNTS']]);
            foreach ($data['DISCOUNTS'] as $k => $v) {
                $discount += $v->VALUE;
            }
            $data['DISCOUNTS'] = $discount;
        }

        return $data;
    }

    /**
     * @param $sscode
     * @return mixed
     */
    public function getZipcode($sscode)
    {
        $api = new api();
        $data = $api->getZipcodeFromSscode($sscode);

        return current($data)['zipcode'];
    }
}