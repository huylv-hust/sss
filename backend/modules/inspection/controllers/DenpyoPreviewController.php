<?php
namespace backend\modules\inspection\controllers;

use app\models\Sdptd01customer;
use app\models\Sdptm08sagyosya;
use app\models\UdenpyoInspection;
use backend\components\utilities;
use backend\controllers\WsController;
use Yii;

class DenpyoPreviewController extends WsController
{
    public function actionIndex()
    {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            return false;
        }
        $UdenpyoInspection = new UdenpyoInspection();
        $data['post'] = $request->post();
        $ss_cd = $data['post']['D01_SS_CD'];
        $branch = utilities::getAllBranch();
        $data['ss'] = isset($branch['all_ss'][$ss_cd]) ? $branch['all_ss'][$ss_cd] : '';
        $data['tel'] = isset($branch['ss_tel'][$ss_cd]) ? $branch['ss_tel'][$ss_cd] : '';
        $data['address'] = isset($branch['ss_address'][$ss_cd]) ? $branch['ss_address'][$ss_cd] : '';
        if ($ss_user = Sdptm08sagyosya::findOne(['M08_JYUG_CD' => $data['post']['M08_NAME_MEI_M08_NAME_SEI']])) {
            $data['ss_user'] = $ss_user['M08_NAME_SEI'] . $ss_user['M08_NAME_MEI'];
        }
        //Customer
        $data['customer'] = Sdptd01customer::findOne($data['post']['D01_CUST_NO']);
        //Product
        $data['count'] = 0;
        for ($i = 1; $i <= 10; $i++) {
            if ($data['post']['D05_COM_CD' . $i]) {
                $data['count']++;
            }
        }
        foreach ($data['post']['LIST_NAME'] as $k => $v) {
            $data['post']['M05_COM_NAMEN' . $k] = $v;
        }
        if ($data['post']['D01_CUST_NO']) {
            $data['car'] = $UdenpyoInspection->getCar($data['post']['D01_CUST_NO'], $data['post']['D03_CAR_SEQ'], $data['customer']['D01_KAIIN_CD']);
        }
        $tanto = explode('[]', $data['post']['D03_TANTO_MEI_D03_TANTO_SEI']);
        if (!empty($tanto[0]) && !empty($tanto[1])) {
            $data['post']['tanto'] = $tanto[0] . $tanto[1];
        }
        $kakunin = explode('[]', $data['post']['D03_KAKUNIN_MEI_D03_KAKUNIN_SEI']);
        if (!empty($kakunin[0]) && !empty($kakunin[1])) {
            $data['post']['kakunin'] = $kakunin[0] . $kakunin[1];
        }

        $this->layout = '@app/views/layouts/preview';
        Yii::$app->params['titlePage'] = '作業伝票作成';
        Yii::$app->view->title = '作業伝票作成';
        return $this->render('preview', $data);
    }
}