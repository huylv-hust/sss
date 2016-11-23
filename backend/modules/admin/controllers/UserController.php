<?php

namespace backend\modules\admin\controllers;

use app\models\Tm50ssuser;
use backend\controllers\AdminController;
use Yii;
use yii\helpers\BaseUrl;

/**
 * Class DefaultController
 * @package backend\modules\admin\controllers
 */
class UserController extends AdminController
{
    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $data['action'] = 'create';
        $data['model'] = new Tm50ssuser();
        if ($id = $request->get('M50_USER_ID')) {
            $data['action'] = 'edit';
            $data['model'] = Tm50ssuser::findOne($id);
        }
        if ($request->isPost) {
            $post = $request->post('Tm50ssuser');
            if (!$this->checkUniqueUserId($data['model'], $post['M50_USER_ID'], $request->get('M50_USER_ID'))) {
                Yii::$app->session->setFlash('error', '入力されたログインIDは既存に存在しています。');
            } else {
                $data['model']->setData($post, $data['model'], $request->get('M50_USER_ID'));
                if ($data['model']->saveData()) {
                    $id = $data['model']->getPrimaryKeyAfterSave();
                    Yii::$app->session->setFlash('success', '更新を完了しました。');
                    return $this->redirect(BaseUrl::base() . '/admin/user?M50_USER_ID=' . $id);
                }
                Yii::$app->session->setFlash('error', Yii::$app->params['message_save_error']);
            }
        }
        Yii::$app->params['titlePage'] = 'ログインアカウント';
        Yii::$app->view->title = 'ログインアカウント';
        $this->layout = '@backend/views/layouts/user';
        return $this->render('user', $data);
    }

    /**
     * delete user
     */
    public function actionDelete()
    {
        $request = Yii::$app->request;
        $post = $request->post('Tm50ssuser');
        $id = $post['M50_USER_ID'];
        $url = Yii::$app->session->has('url_list_user') ? Yii::$app->session->get('url_list_user') : \yii\helpers\BaseUrl::base().'/admin/users';
        if(!$id || !$obj = Tm50ssuser::findOne($id)) {
            return $this->redirect($url);
        }

        if (Tm50ssuser::deleteData($obj)) {
            Yii::$app->session->setFlash('success', '削除を完了しました。');
            return $this->redirect($url);
        }

        Yii::$app->session->setFlash('error', 'error');
        return $this->redirect(BaseUrl::base() . '/admin/user?M50_USER_ID=' . $id);
    }

    private function checkUniqueUserId($obj, $postId, $id = null)
    {
        if ((!$id && $obj->countData(['M50_USER_ID' => $postId])) || ($id && $id != $postId && $obj->countData(['M50_USER_ID' => $postId])))
        {
            return false;
        }
        return true;
    }
}
