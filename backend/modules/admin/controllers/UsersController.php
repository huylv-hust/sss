<?php
namespace backend\modules\admin\controllers;
use app\models\Tm50ssuser;
use Yii;
use yii\data\Pagination;
use yii\web\Session;
use yii\helpers\BaseUrl;
/**
 * Class DefaultController
 * @package backend\modules\admin\controllers
 */
class UsersController extends \backend\controllers\AdminController
{
    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
		$userObj = new Tm50ssuser();
		$data['filters']['M50_DEL_FLG'] = '0';
		$data['filters']['M50_USER_NAME'] = Yii::$app->request->get('M50_USER_NAME');
		$data['count'] = $userObj->countData($data['filters']);
		$filters = Yii::$app->request->get();
		$query_string = empty($filters) ? '' : '?' . http_build_query($filters);
		Yii::$app->session->set('url_list_user', BaseUrl::base() . '/admin/users' . $query_string);
		$data['pagination'] = new Pagination([
			'totalCount' => $userObj->countData($data['filters']),
			'defaultPageSize' => 10,//Yii::$app->params['defaultPageSize'],
		]);
		$data['page']  = Yii::$app->request->get('page');
		$data['filters']['limit'] = $data['pagination']->limit;
		$data['filters']['offset'] = $data['pagination']->offset;
		$data['listM50'] = $userObj->getData($data['filters']);
		Yii::$app->params['titlePage'] = 'ログインアカウント一覧';
		Yii::$app->view->title = 'ログインアカウント一覧';
		$this->layout = '@backend/views/layouts/user';
		return $this->render('users', $data);
    }
}
