<?php

namespace backend\modules\listworkslip\controllers;

use app\models\DenpyoInspection;
use app\models\Sdptd04denpyosagyo;
use app\models\Sdptd01customer;
use app\models\Sdptm01sagyo;
use backend\components\utilities;
use backend\controllers\WsController;
use Yii;
use app\models\Sdptd03denpyo;
use yii\data\Pagination;
use yii\helpers\BaseUrl;
use yii\web\Session;
use backend\components\api;

/**
 * Class DefaultController
 * @package backend\modules\listworkslip\controllers
 */
class DefaultController extends WsController
{
    /**
     * list order
     * @return string
     */
    public function actionIndex()
    {
        $all = new utilities();
        $branch = $all->getAllBranch();
        $data['all_ss'] = $branch['all_ss'];

        $obj = new Sdptd03denpyo();
        $obj_job = new Sdptm01sagyo();
        $filters = Yii::$app->request->get();

        $query_string = empty($filters) ? '' : '?' . http_build_query($filters);
        Yii::$app->session->set('url_list_workslip', BaseUrl::base() . '/list-workslip' . $query_string);

        if (empty($filters)) {
            $filters['start_time'] = date('Ymd');
            $filters['end_time'] = date('Ymd');
            $filters['D01_KAKE_CARD_NO'] = '';
        }

        if (isset($filters['D01_KAKE_CARD_NO'])) {
            $cusNo = $this->getCustNo($filters['D01_KAKE_CARD_NO']);
            if (count($cusNo)) {
                $filters['D03_CUST_NO'] = $cusNo;
            }
        }

        $data['filters'] = $filters;
        /*
        * Get login info
        * */
        $login_info = Yii::$app->session->get('login_info');
        if (isset($login_info['M50_SS_CD']) && $login_info['M50_SS_CD'] != '') {
            $data['filters']['m50_ss_cd'] = $login_info['M50_SS_CD'];
        }

        $count = $obj->countDataSearch($data['filters']);
        $data['pagination'] = new Pagination([
            'totalCount' => $count,
            'defaultPageSize' => Yii::$app->params['defaultPageSize'],
        ]);
        $data['page'] = $filters = Yii::$app->request->get('page');
        $data['filters']['limit'] = $data['pagination']->limit;
        $data['filters']['offset'] = $data['pagination']->offset;

        if (isset($data['filters']['D01_KAKE_CARD_NO']) && $data['filters']['D01_KAKE_CARD_NO']) {
            $data['filters']['m50_ss_cd'] = '';
        }

        if (isset($data['filters']['car']) && $data['filters']['car']) {
            $data['filters']['m50_ss_cd'] = '';
        }

        //get data inspection
        $inspection_obj = new DenpyoInspection();
        $inspection_data = $inspection_obj->getData([], 'DENPYO_NO, DENPYO_SUGGEST_NO');
        $denpyo_inspection_no = [];
        $i = 0;
        foreach ($inspection_data as $k => $v) {
            $denpyo_inspection_no[$i][] = $v['DENPYO_NO'];
            $denpyo_inspection_no[$i][] = $v['DENPYO_SUGGEST_NO'];
            if (count($denpyo_inspection_no[$i]) == 1000) {
                $i++;
            }
        }
        $data['filters']['DENPYO_NO_NOT_IN'] = $denpyo_inspection_no;

        $data['list'] = $obj->getDataSearch($data['filters']);
        if (empty($data['list'])) {
            Yii::$app->session->setFlash('empty', '入力条件に該当する作業伝票が存在しません');
        } else {
            foreach ($data['list'] as &$value) {
                $value['sign'] = \backend\components\sign::read($value['D03_DEN_NO']);
            }
        }
        $data['job'] = [];
        $all_job = $obj_job->getData();
        foreach ($all_job as $k => $v) {
            $data['job'][''] = '';
            $data['job'][$v['M01_SAGYO_NO']] = $v['M01_SAGYO_NAMEN'];
        }

        $data['status'] = Yii::$app->params['status'];
        Yii::$app->params['titlePage'] = '作業履歴';
        Yii::$app->view->title = '情報検索';
        return $this->render('index', $data);
    }

    /**
     * get job of order
     * @param $order_id
     * @return array
     */
    public static function getJob($order_id)
    {
        $result = [];
        $obj = new Sdptd04denpyosagyo();
        $job = $obj->getData(['D04_DEN_NO' => $order_id]);
        if (!empty($job)) {
            foreach ($job as $k => $v) {
                $result[] = $v['D04_SAGYO_NO'];
            }
        }
        return $result;
    }

    private function getCustNo($cardNo)
    {
        $cusObj = new Sdptd01customer;
        $cusNo = [];
        if ($cardNo) {
            $listData = $cusObj->getData(['D01_KAKE_CARD_NO' => $cardNo]);
            if (count($listData)) {
                foreach ($listData as $row) {
                    $cusNo[] = $row['D01_CUST_NO'];
                }

            } else {
                $cusInfo = api::getInfoCard($cardNo);
                if ($cusInfo['result'] == 1) {
                    $listData = $cusObj->getData(['D01_KAIIN_CD' => $cusInfo['member_kaiinCd']]);
                    if (count($listData)) {
                        $row = current($listData);
                        $cusNo[] = $row['D01_CUST_NO'];
                    } else {
                        $cusNo[] = '-1';
                    }
                } else {
                    $cusNo[] = '-1';
                }

            }
        }

        return $cusNo;
    }
}
