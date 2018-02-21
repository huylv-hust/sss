<?php

namespace backend\modules\usappynumberchange\controllers;

use backend\components\api;
use backend\components\utilities;
use backend\controllers\WsController;
use backend\modules\pdf\controllers\PdfController;
use yii\helpers\BaseUrl;

class DefaultController extends WsController
{
    /**
     * @return string|\yii\web\Response
     * check car number in card_cardBangou
     */
    private function equalCardNumber($oldcardnumber, $newcardnumber, $arrcardnumber)
    {
        foreach ($arrcardnumber as $k => $v) {
            if ($oldcardnumber == $arrcardnumber[$k]) {
                $arrcardnumber[$k] = $newcardnumber;
                return $arrcardnumber;
            }
        }
        return false;
    }

    /*
     *index change member car
     * @author: Dang Bui
     * */
    public function actionIndex()
    {
        $cookie = \Yii::$app->request->cookies;
        $cus_info = $cookie->getValue('cus_info', '0');

        if ($cus_info == 0) {
            return $this->goHome();
        }
        $data['cus_info'] = $cus_info;

        \Yii::$app->view->title = 'Usappyカード変更';
        \Yii::$app->params['titlePage'] = 'Usappyカード変更';
        return $this->render('index', $data);
    }

    /*
     *confirm change member car
     * @author: Dang Bui
     * */
    public function actionConfirm()
    {
        $cookie = \Yii::$app->request->cookies;
        $cus_info = $cookie->getValue('cus_info', '0');
        if ($cus_info == 0) {
            return $this->goHome();
        }
        $api = new api();
        if (! $data = \Yii::$app->request->post()) {
            return $this->redirect(BaseUrl::base(true).'/usappy-number-change');
        }
        $info_card = $api->getInfoListCard($cus_info['member_kaiinCd']);
        //card_cardBangou
        if (! $card_cardBangou = $this->equalCardNumber($data['oldCardNumber'], $data['newCardNumber'], $info_card['card_cardBangou'])) {
            \Yii::$app->session->setFlash('error', '旧Usappyカード番号が登録されていません');
            $data['cus_info'] = $cus_info;
            \Yii::$app->view->title = 'Usappyカード変更';
            \Yii::$app->params['titlePage'] = 'Usappyカード変更';
            return $this->render('index', $data);
        }
        $info_card['card_cardBangou'] = $card_cardBangou;
        $info_card = json_encode($info_card);

        $form['data'] = [
            'infoCard'   => $info_card,
            'kaiinCd' => $cus_info['member_kaiinCd'],
            'memberKaiinName' => $cus_info['member_kaiinName'],
            'oldCardNumber' => $data['oldCardNumber'],
            'newCardNumber' => $data['newCardNumber']
        ];
        \Yii::$app->view->title = 'Usappyカード変更';
        \Yii::$app->params['titlePage'] = 'Usappyカード変更';
        return $this->render('confirm', $form);
    }
    /**
     *complete change member car
     * @author: Dang Bui
     */
    public function actionComplete()
    {
        if (! $data = \Yii::$app->request->post()) {
            return $this->redirect(BaseUrl::base(true).'/usappy-number-change');
        }

        $memberKaiinName = $data['memberKaiinName'];
        $infoCard = json_decode($data['infoCard'], true);
        $oldCardNumber = $data['oldCardNumber'];
        $newCardNumber = $data['newCardNumber'];
        $kaiinCd = $data['kaiinCd'];
        $api = new api();

        $status = $api->updateCardNumber($kaiinCd, $infoCard);
        if ($status) {
            \Yii::$app->session->setFlash('info', '下記の内容でUsappyカード番号を変更しました。');
        } else {
            \Yii::$app->session->setFlash('info', 'Error Update Card Number');
        }
        utilities::deleteCookie('cus_info');//Delete coolkie cus_info
        $form['data'] = [
            'memberKaiinName' => $memberKaiinName,
            'oldCardNumber' => $oldCardNumber,
            'newCardNumber' => $newCardNumber
        ];
        \Yii::$app->view->title = 'Usappyカード変更';
        \Yii::$app->params['titlePage'] = 'Usappyカード変更';
        return $this->render('complete', $form);
    }

    /**
     * test api
     * @author: Dang Bui
     */
    public function actionTestapi()
    {
        $api = new api();
        $memem = $api->getInfoCardTop('4035607000500000');

        $member_info = $api->getMemberInfo($memem['member_kaiinCd']);
        $infocard = $api->getInfoListCard($memem['member_kaiinCd']);

        echo '<pre>';
        var_dump($memem);
        var_dump($member_info);
        var_dump($infocard);
        echo '</pre>';
        die;
    }

    /**
     *export pdf
     * @author: Dang Bui
     */
    public function actionPdf()
    {
        $info_warranty = [
            'number' => 'test19022016',
            'date' => 'info_warranty_date',
            'expired' => 'info_warranty_expired'
        ];
        $info_car = [
            'customer_name' => 'info_car_customer',
            'car_name' => 'info_car_name',
            'car_license' => 'info_car_license'
        ];
        $info_bill = [
            'front_wheel_right' => [
                'info_market' => 'Thông tin market1',
                'product_name' => '321',
                'size' => '12',
                'serial' => '1223'
            ],
            'front_wheel_left' => [
                'info_market' => 'Thông tin market2',
                'product_name' => '123',
                'size' => '123',
                'serial' => '123'
            ],
            'back_wheel_right' => [
                'info_market' => 'Thông tin market3',
                'product_name' => '',
                'size' => '',
                'serial' => ''
            ],
            'back_wheel_left' => [
                'info_market' => 'Thông tin market4',
                'product_name' => '',
                'size' => '',
                'serial' => ''
            ],
            'otherA' => [
                'info_market' => 'Thông tin market5',
                'product_name' => '',
                'size' => '',
                'serial' => ''
            ],
            'otherB' => [
                'info_market' => 'Thông tin market6',
                'product_name' => '',
                'size' => '',
                'serial' => ''
            ]
        ];
        $info_ss = [
            'name' => 'SS name',
            'address' => 'Address',
            'mobile' => 'Mobile'
        ];
        $data = [
            'info_warranty' => $info_warranty,
            'info_car' => $info_car,
            'info_bill' => $info_bill,
            'info_ss' => $info_ss
        ];

        $pdf_export = new PdfController();
        $pdf_export->exportBill($info_warranty, $info_car, $info_bill, $info_ss, 'save', 1);
        $file = BaseUrl::base(true).'/data/pdf/'.$info_warranty['number'].'.pdf';
        var_dump($file);
    }
}
