<?php

namespace backend\modules\maintenance\controllers;

use Yii;
use backend\controllers\WsController;

class DefaultController extends WsController
{
    public function actionIndex()
    {
        Yii::$app->params['titlePage'] = '設定';
        Yii::$app->view->title = '設定';
        return $this->render('index');
    }
}
