<?php
namespace backend\modules\inspection\controllers;

use backend\controllers\WsController;
use Yii;

class FeeController extends WsController
{
    public function actionIndex()
    {
        Yii::$app->params['titlePage'] = '車検料金設定 ';
        Yii::$app->view->title = '車検料金設定 ';
        return $this->render('index');
    }
}