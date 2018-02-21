<?php
/**
 * Created by PhpStorm.
 * User: HUY-LV
 * Date: 5/13/2016
 * Time: 8:55 AM
 */
namespace backend\controllers;
use backend\components\utilities;
use yii\helpers\BaseUrl;
use yii\web\Controller;
use Yii;

class AdminController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function beforeAction()
	{
		$session = \Yii::$app->session;
		if (! $session->get('admin_login_info')) {
			$this->redirect(BaseUrl::base(true).'/admin/login');
			return false;
		}
		
		if ($login_info = $session->get('admin_login_info') and $login_info['expired'] < time()) {
			$session->remove('admin_login_info');
			unset($session['admin_login_info']);
		}
		
		if (! $session->get('admin_login_info')) {
			$this->redirect(BaseUrl::base(true).'/admin/login');
			return false;
		}

		if ($loginInfo = $session->get('admin_login_info')) {
			$login_info['expired'] = time() + Yii::$app->params['timeOutLogin'];
			$session->set('admin_login_info', $login_info);
		}
		return true;
	}
}
