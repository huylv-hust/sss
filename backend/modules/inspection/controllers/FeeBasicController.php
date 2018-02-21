<?php
namespace backend\modules\inspection\controllers;

use app\models\DenpyoInspection;
use app\models\FeeBasic;
use backend\controllers\WsController;
use Yii;
use yii\data\Pagination;
use yii\helpers\BaseUrl;

class FeeBasicController extends WsController
{
    public function actionIndex()
    {
        $obj = new FeeBasic();
        $request = Yii::$app->request;

        //get data by id
        $id = $request->get('id');
        if ($id) {
            if (!$fee_basic = $obj = FeeBasic::findOne($id)) {
                Yii::$app->session->setFlash('error', '基本料金は存在しません。');
                $this->redirect(BaseUrl::base(true) . '/fee-basic/list');
            }
        }

        //add
        if ($request->isPost) {
            //set column (SS_CD)
            $login_info = Yii::$app->session->get('login_info');
            $obj->SS_CD = $login_info['M50_SS_CD'];
            //set column (Other)
            $obj->setAttributes($request->post(), false);
            if ($obj->save()) {
                Yii::$app->session->setFlash('success', '保存を完了しました。');
                $url = Yii::$app->session->get('url_list_fee_basic') && $id ? Yii::$app->session->get('url_list_fee_basic') : BaseUrl::base(true) . '/fee-basic/list';
                return $this->redirect($url);
            }

            Yii::$app->session->setFlash('error', '保存を完了しません。');
        }

        Yii::$app->params['titlePage'] = '基本料金登録・編集';
        Yii::$app->view->title = '基本料金登録・編集';

        return $this->render('index', compact('fee_basic'));
    }

    public function actionList()
    {
        $obj = new FeeBasic();

        //pagination
        $pagination = new Pagination([
            'totalCount' => $obj->countData(),
            'defaultPageSize' => Yii::$app->params['defaultPageSize']
        ]);
        $filters['limit'] = $pagination->limit;
        $filters['offset'] = $pagination->offset;

        //get data
        $fee_basic = $obj->getData($filters);

        //store url
        $filter = Yii::$app->request->get();
        $query_string = empty($filter) ? '' : '?' . http_build_query($filter);
        Yii::$app->session->set('url_list_fee_basic', BaseUrl::base() . '/fee-basic/list' . $query_string);

        Yii::$app->params['titlePage'] = '基本料金設定一覧';
        Yii::$app->view->title = '基本料金設定一覧';

        return $this->render('list', compact('pagination', 'fee_basic'));
    }

    public function actionRemove()
    {
        $id = Yii::$app->request->post('fee_basic_id');
        $url = Yii::$app->session->get('url_list_fee_basic') ? Yii::$app->session->get('url_list_fee_basic') : BaseUrl::base(true) . '/fee-basic/list';
        if (!$obj = FeeBasic::findOne($id)) {
            return $this->redirect($url);
        }

        if (DenpyoInspection::findAll(['FEE_BASIC_ID' => $id])) {
            Yii::$app->session->setFlash('error', '基本料金を使用しました。');
            return $this->redirect($url);
        }

        if ($obj->delete()) {
            Yii::$app->session->setFlash('success', '削除を完了しました。');
        } else {
            Yii::$app->session->setFlash('error', '削除をできません。');
        }

        return $this->redirect($url);
    }
}
