<?php
namespace backend\controllers;

use backend\components\utilities;
use yii\helpers\BaseUrl;
use yii\web\Controller;
use Yii;

class WsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction()
    {
        $current_route = $this->getModules()[0]->requestedRoute;
        if (! Yii::$app->request->isAjax && ! in_array($current_route, Yii::$app->params['route_keep_cookie'])) {
            utilities::deleteCookie('cus_info');//Delete coolkie cus_info
        }


        $session = \Yii::$app->session;
        if (! $session->get('login_info')) {
            $this->redirect(BaseUrl::base(true).'/login');
            return false;
        }

        if ($login_info = $session->get('login_info') and $login_info['expired'] < time()) {
            $session->remove('login_info');
            unset($session['login_info']);
        }

        if (! $session->get('login_info')) {
            $this->redirect(BaseUrl::base(true).'/timeout');
            return false;
        }

        if ($loginInfo = $session->get('login_info')) {
            $login_info['expired'] = time() + Yii::$app->params['timeOutLogin'];
            $session->set('login_info', $login_info);
        }
        return true;
    }
}
