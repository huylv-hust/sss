<?php


namespace backend\modules\maintenance\controllers;

use backend\components\utilities;
use Yii;
use app\models\Sdptm08sagyosya;
use backend\controllers\WsController;
use yii\data\Pagination;
use yii\helpers\BaseUrl;
use yii\web\Response;

/**
 * @author Thuanth6589 <thuanth6589@seta-asia.com.vn>
 * Class StaffController
 * @package backend\modules\maintenance\controllers
 */
class StaffController extends WsController
{
    /**
     * search staff
     * @return string
     */
    public function actionIndex()
    {
        $ss = utilities::getAllBranch();
        $login_info = Yii::$app->session->get('login_info');
        $data['all_branch'] = ['' => ''] + $ss['all_branch'];
        $data['all_ss'] = $ss['all_ss'];
        $request = Yii::$app->request;
        $filters = $request->get();
        $query_string = empty($filters) ? '' : '?'.http_build_query($filters);
        $data['filters'] = (!isset($filters['M08_HAN_CD']) || !isset($filters['M08_SS_CD']))
            ? [
                'M08_HAN_CD' => isset($ss['all_ss_branch'][$login_info['M50_SS_CD']]) ? $ss['all_ss_branch'][$login_info['M50_SS_CD']] : '',
                'M08_SS_CD' => $login_info['M50_SS_CD']
            ]
            : $filters;

        Yii::$app->session->set('url_list_staff', BaseUrl::base().'/list-staff'.$query_string);
        $data['all_ss_search'] = ['' => ''] + $this->processGetss($data['filters']['M08_HAN_CD'], $ss);
        $obj = new Sdptm08sagyosya();

        $count = $obj->counData($data['filters']);
        $data['pagination'] = new Pagination([
            'totalCount' => $count,
            'defaultPageSize' => Yii::$app->params['defaultPageSize']
        ]);

        $data['filters']['limit'] = $data['pagination']->limit;
        $data['filters']['offset'] = $data['pagination']->offset;
        $data['staffs'] = $obj->getData($data['filters']);

        Yii::$app->params['titlePage'] = '設定';
        Yii::$app->view->title = '作業者一覧';
        return $this->render('index', $data);
    }

    /**
     * get ss in branch
     * @return mixed
     */
    public function actionGetss()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $ss = utilities::getAllBranch();
            $branch_id = $request->post('branch_id');
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->processGetss($branch_id, $ss);
        }
    }

    /**
     * @param $branch_id
     * @param $ss
     * @return array
     */
    public static function processGetss($branch_id, $ss)
    {
        if ($branch_id == '') {
            return $ss['all_ss'];
        }
        $branch_ss = isset($ss['all_branch_ss'][$branch_id]) ? $ss['all_branch_ss'][$branch_id] : [];
        $array_ss = [];
        foreach ($branch_ss as $k => $v) {
            $array_ss[$v] = $ss['all_ss'][$v];
        }
        return $array_ss;
    }

    /**
     * create, edit staff
     * @return string|Response
     */
    public function actionStaff()
    {
        $request = Yii::$app->request;
        $data['api'] = utilities::getAllBranch();
        $data['all_branch'] = ['' => ''] + $data['api']['all_branch'];
        $login_info = Yii::$app->session->get('login_info');
        $data['default_value'] =  [
            'M08_HAN_CD' => $data['api']['all_ss_branch'][$login_info['M50_SS_CD']],
            'M08_SS_CD' => $login_info['M50_SS_CD']];
        if ($request->get('branch') && $request->get('ss') && $request->get('cd')) {
            $primary = [
                'M08_HAN_CD' => $request->get('branch'),
                'M08_SS_CD' => $request->get('ss'),
                'M08_JYUG_CD' => $request->get('cd'),
            ];
            $data['model'] = Sdptm08sagyosya::findOne($primary);
            if (!$data['model']) {
                return $this->redirect(BaseUrl::base().'/list-staff');
            }
            $data['action'] = 'edit';
            $all_ss = $this->processGetss($data['model']->M08_HAN_CD, $data['api']);
            Yii::$app->session->set('url_edit_staff', BaseUrl::base(true)
            .'/edit-staff?branch='.$primary['M08_HAN_CD']
            .'&ss='.$primary['M08_SS_CD']).'&cd='.$primary['M08_JYUG_CD'];
            Yii::$app->view->title = '作業者編集';
        } else {
            $primary = null;
            $data['model'] = new Sdptm08sagyosya();
            $data['action'] = 'create';
            $all_ss = $this->processGetss($data['default_value']['M08_HAN_CD'], $data['api']);
            Yii::$app->view->title = '作業者登録';
        }

        $data['all_ss'] = ['' => ''] + $all_ss;
        if ($request->isPost) {
            $data['model']->setData($request->post('Sdptm08sagyosya'), $primary);
            if ($data['model']->saveData()) {
                Yii::$app->session->setFlash('success', 'success');
                $key = $data['model']->getPrimaryKeyAfterSave();
                return $this->redirect(BaseUrl::base()
                    .'/edit-staff?branch='.$key['M08_HAN_CD'].'&ss='.$key['M08_SS_CD'].'&cd='.$key['M08_JYUG_CD']);
            }
            Yii::$app->session->setFlash('error', 'error');
        }

        Yii::$app->params['titlePage'] = '設定';
        return $this->render('staff', $data);
    }

    /**
     * delete staff
     * @return Response
     */
    public function actionDelete()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $obj = new Sdptm08sagyosya();
            if ($obj->deleteData($request->post('Sdptm08sagyosya'))) {
                Yii::$app->session->setFlash('success', '削除を完了しました。');
                $url = Yii::$app->session->has('url_list_staff')
                    ? Yii::$app->session->get('url_list_staff')
                    : BaseUrl::base(true).'/list-staff';
                return $this->redirect($url);
            }

            Yii::$app->session->setFlash('error', '削除が失敗しました。');
            $url = (Yii::$app->session->has('url_edit_staff'))
                ? Yii::$app->session->get('url_edit_staff')
                : BaseUrl::base(true).'/list-staff';
            return $this->redirect($url);
        }
    }
}
