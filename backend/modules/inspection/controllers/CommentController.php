<?php
namespace backend\modules\inspection\controllers;

use app\models\Comment;
use app\models\DenpyoInspection;
use app\models\ParentComment;
use backend\controllers\WsController;
use Yii;
use yii\data\Pagination;
use yii\helpers\BaseUrl;

class CommentController extends WsController
{
    public function actionIndex()
    {
        $obj = new ParentComment();
        $inspection_obj = new DenpyoInspection();
        $request = Yii::$app->request;
        $id = $request->get('id');
        $comments_used = [];
        //get data
        if ($id) {
            if (!$parent = ParentComment::findOne($id)) {
                Yii::$app->session->setFlash('error', 'コメントは存在しません。');
                $this->redirect(BaseUrl::base(true) . '/comment/list');
            }
            if ($comments = Comment::findAll(['PARENT_COMMENT_ID' => $id])) {
                $obj = clone $parent;
                $comments_ids = [];
                foreach ($comments as $row) {
                    $comments_ids[] = $row['ID'];
                }
                $data = $inspection_obj->getData(['COMMENTS' => $comments_ids]);
                foreach ($data as $row) {
                    $comments_used[] = trim($row['COMMENTS'], ',');
                }
            }
        }
        //create & update
        if ($request->isPost) {
            //check remove comment
            if ($remove = $request->post('comment_remove')) {
                if (!Comment::deleteAll('ID IN(' . $remove . ')')) {
                    Yii::$app->session->setFlash('error', 'コメントは使用中、削除できません。');
                    Yii::$app->params['titlePage'] = '車検コメント登録・編集';
                    Yii::$app->view->title = '車検コメント登録・編集';
                    return $this->render('index', compact('parent', 'comments', 'comments_used'));
                }

            }
            //set column (SS_CD)
            $login_info = Yii::$app->session->get('login_info');
            $obj->SS_CD = $login_info['M50_SS_CD'];
            //set column (Other)
            $obj->setAttributes($request->post(), false);
            $comment = $request->post('comment', []);
            //save data
            if ($obj->saveData($comment)) {
                Yii::$app->session->setFlash('success', '保存を完了しました。');
                $url = Yii::$app->session->get('url_list_comment') && $id ? Yii::$app->session->get('url_list_comment') : BaseUrl::base() . '/comment/list';

                return $this->redirect($url);
            }
            Yii::$app->session->setFlash('error', '保存を完了しません。');
        }

        Yii::$app->params['titlePage'] = '車検コメント登録・編集';
        Yii::$app->view->title = '車検コメント登録・編集';

        return $this->render('index', compact('parent', 'comments', 'comments_used'));
    }


    public function actionList()
    {
        $obj = new ParentComment();
        $request = Yii::$app->request;
        $filters = $request->get();
        $query_string = empty($filters) ? '' : '?' . http_build_query($filters);
        Yii::$app->session->set('url_list_comment', BaseUrl::base() . '/comment/list' . $query_string);
        Yii::$app->params['titlePage'] = '車検コメント分類一覧';
        Yii::$app->view->title = '車検コメント分類一覧';
        $pagination = new Pagination([
            'totalCount' => $obj->counData(),
            'defaultPageSize' => Yii::$app->params['defaultPageSize']
        ]);

        $filters['limit'] = $pagination->limit;
        $filters['offset'] = $pagination->offset;
        $parent_comments = $obj->getData($filters);

        return $this->render('list', compact('pagination', 'parent_comments'));
    }

    public function actionDelete()
    {
        $request = Yii::$app->request;
        $id = $request->post('ID');
        $url = Yii::$app->session->get('url_list_comment') ? Yii::$app->session->get('url_list_comment') : BaseUrl::base(true) . '/comment/list';
        if (!$obj = ParentComment::findOne($id)) {
            return $this->redirect($url);
        }
        //find comment used by parent_comment
        if ($obj_comment = Comment::findAll(['PARENT_COMMENT_ID' => $id])) {
            foreach ($obj_comment as $k => $v) {
                $id_comment = ',' . $v['ID'] . ',';
                if (DenpyoInspection::find()->where(['like', 'COMMENTS', $id_comment])->all()) {
                    Yii::$app->session->setFlash('error', 'コメントは使用中、削除できません。');

                    return $this->redirect($url);
                }
            }
        }

        if ($obj->deleteData($id)) {
            Yii::$app->session->setFlash('success', 'コメントを削除しました。');
        } else {
            Yii::$app->session->setFlash('error', '削除をできません。');
        }
        return $this->redirect($url);
    }
}
