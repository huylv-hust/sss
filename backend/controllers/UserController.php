<?php
namespace backend\controllers;

use backend\components\utilities;
use Yii;

use yii\helpers\BaseUrl;
use yii\web\Controller;
use app\models\Tm50ssuser;

/**
 * Created by PhpStorm.
 * User: HP400
 * Date: 1/12/2016
 * Time: 8:44 AM
 */
class UserController extends Controller
{
    /**
     *Login admin
     * @author: Dang Bui
     */
    public function actionLoginadmin()
    {
        if ($user_info = Yii::$app->request->post()) {
            $id = $user_info['ssid'];
            $pass = $user_info['password'];

            if (!file_exists(getcwd() . '/data/adminuser.json')) {
                $user = Yii::$app->params['admin_user'];
                utilities::createFolder('data/');
                $fh = fopen(getcwd() . '/data/adminuser.json', 'w+');
                $content = json_encode($user);
                fwrite($fh, $content);
            } else {
                $user = json_decode(file_get_contents(getcwd() . '/data/adminuser.json'), true);
            }

            foreach ($user as $k => $v) {
                if ($id == $v['ssid'] && $pass == $v['password']) {
                    Yii::$app->session->set('login_admin_info', ['status' => 'login_success', 'id' => $id, 'pass' => $pass, 'expired' => time() + Yii::$app->params['timeOutLogin']]);
                    $this->redirect(BaseUrl::base(true) . '/operator/punc');
                }
            }
            Yii::$app->session->setFlash('error', 'ログインIDが正しくありません');
        }
        $this->layout = '@backend/views/layouts/login';
        return $this->render('login_admin');
    }

    public function actionChangepass()
    {
        if ($info = Yii::$app->request->post()) {
            $pass = $info['pass'];
            $login_info_session = \Yii::$app->session->get('login_admin_info');
            $content = file_get_contents(getcwd() . '/data/adminuser.json');
            $login_info = json_decode($content, true);
            foreach ($login_info as $index => $login) {
                if ($login['ssid'] == $login_info_session['id']) {
                    $login_info[$index]['password'] = $pass;
                }
            }

            $fh = fopen(getcwd() . '/data/adminuser.json', 'w+');
            $wf = fwrite($fh, json_encode($login_info));
            if ($wf) {
                Yii::$app->session->setFlash('success', 'パスワードを変更しました。');
                Yii::$app->response->redirect(BaseUrl::base(true) . '/operator/punc');
            } else {
                Yii::$app->session->setFlash('error', 'パスワードの変更に失敗しました。');
                Yii::$app->response->redirect(BaseUrl::base(true) . '/operator/punc');
            }
        }
    }

    /**
     *Login
     * @author: Dang Bui
     */
    public function actionLogin()
    {
        $session = \Yii::$app->session;
        if ($session->get('login_info')) {
            $this->goHome();
        }
        $user = new Tm50ssuser();
        if (Yii::$app->request->post()) {
            $isLogin = $user->checkLogin(Yii::$app->request->post());
            if ($isLogin['flag']) {
                $login_info = $isLogin['sql']['0'];
                $login_info['expired'] = time() + Yii::$app->params['timeOutLogin'];
                Yii::$app->session->set('login_info', $login_info);
                $this->redirect(BaseUrl::base(true) . '/menu');
            } else {
                Yii::$app->session->setFlash('error', '入力されたSSＩＤまたはパスワードが正しくありません');
            }
        }
        \Yii::$app->params['titlePage'] = 'ログイン';
        \Yii::$app->view->title = 'ログイン';
        $this->layout = '@backend/views/layouts/login';
        return $this->render('login');
    }

    /**
     *Logout
     * @author: Dang Bui
     */
    public function actionLogout()
    {
        $session = Yii::$app->session;
        $session->remove('login_info');
        unset($session['login_info']);
        Yii::$app->session->setFlash('success_logout', '<span class="noti">ログアウトしました。</span>');
        $this->redirect(BaseUrl::base(true) . '/login');
    }

    /**
     *time out
     * @author: Dang Bui
     */
    public function actionTimeout()
    {
        \Yii::$app->params['titlePage'] = 'エラー';
        \Yii::$app->view->title = 'エラー';
        $this->layout = '@backend/views/layouts/error';
        return $this->render('timeout');
    }

    /**
     *error page
     * @author: Dang Bui
     */
    public function actionError()
    {
        \Yii::$app->params['titlePage'] = 'エラー';
        \Yii::$app->view->title = 'エラー';
        $this->layout = '@backend/views/layouts/error';
        return $this->render('error');
    }

    /**
     *admin login
     * @author: Huy LV
     */
    public function actionAdminlogin() {
        if ($user_info = Yii::$app->request->post()) {
            $id = $user_info['id'];
            $pass = $user_info['password'];

            if (!file_exists(getcwd() . '/data/sysuser.json')) {
                $user = Yii::$app->params['admin_login'];
                utilities::createFolder('data/');
                $fh = fopen(getcwd() . '/data/sysuser.json', 'w+');
                $content = json_encode($user);
                fwrite($fh, $content);
            } else {
                $user = json_decode(file_get_contents(getcwd() . '/data/sysuser.json'), true);
            }

            foreach ($user as $k => $v) {
                if ($id == $v['ssid'] && $pass == $v['password']) {
                    Yii::$app->session->set('admin_login_info', ['status' => 'login_success', 'id' => $id, 'expired' => time() + Yii::$app->params['timeOutLogin']]);
                    $this->redirect(BaseUrl::base(true) . '/admin/users');
                }
            }
            Yii::$app->session->setFlash('error', '入力されたIDまたはパスワードが正しくありません');
        }
        Yii::$app->view->title = 'SSサポートサイト管理ログイン';
        $this->layout = '@backend/views/layouts/user';
        return $this->render('admin_login');
    }
}
