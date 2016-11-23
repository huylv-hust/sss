<?php
namespace backend\modules\inspection\controllers;

use app\models\DenpyoInspection;
use app\models\FeeRegistration;
use backend\controllers\WsController;
use Yii;
use yii\data\Pagination;
use yii\helpers\BaseUrl;

class FeeRegistrationController extends WsController
{
    public function actionIndex()
    {
        $obj = new FeeRegistration();
        $request = Yii::$app->request;

        //get data by id
        $id = $request->get('id');
        if ($id) {
            if (!$fee_registration = $obj = FeeRegistration::findOne($id)) {
                Yii::$app->session->setFlash('error', '法定料金は存在しません。');
                $this->redirect(BaseUrl::base(true) . '/fee-registration/list');
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
                $url = Yii::$app->session->get('url_list_fee_registration') && $id ? Yii::$app->session->get('url_list_fee_registration') : BaseUrl::base(true) . '/fee-registration/list';
                return $this->redirect($url);
            }

            Yii::$app->session->setFlash('error', '保存を完了しません。');
        }

        Yii::$app->params['titlePage'] = '法定料金登録・編集';
        Yii::$app->view->title = '法定料金登録・編集';

        return $this->render('index', compact('fee_registration'));
    }

    public function actionList()
    {
        $obj = new FeeRegistration();

        //pagination
        $pagination = new Pagination([
            'totalCount' => $obj->countData(),
            'defaultPageSize' => Yii::$app->params['defaultPageSize']
        ]);
        $filters['limit'] = $pagination->limit;
        $filters['offset'] = $pagination->offset;

        //get data
        $fee_registration = $obj->getData($filters);

        //store url
        $filter = Yii::$app->request->get();
        $query_string = empty($filter) ? '' : '?' . http_build_query($filter);
        Yii::$app->session->set('url_list_fee_registration', BaseUrl::base() . '/fee-registration/list' . $query_string);

        Yii::$app->params['titlePage'] = '法定料金設定一覧';
        Yii::$app->view->title = '法定料金設定一覧';

        return $this->render('list', compact('fee_registration', 'pagination'));
    }

    public function actionRemove()
    {
        $id = Yii::$app->request->post('fee_registration_id');
        $url = Yii::$app->session->get('url_list_fee_registration') ? Yii::$app->session->get('url_list_fee_registration') : BaseUrl::base(true) . '/fee-registration/list';
        if (!$obj = FeeRegistration::findOne($id)) {
            return $this->redirect($url);
        }

        if (DenpyoInspection::findAll(['FEE_REGISTRATION_ID' => $id])) {
            Yii::$app->session->setFlash('error', '法定料を使用しました。');
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
