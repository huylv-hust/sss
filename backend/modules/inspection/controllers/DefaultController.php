<?php

namespace backend\modules\inspection\controllers;

use Yii;
use backend\controllers\WsController;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->params['titlePage'] = '車検';
        Yii::$app->view->title = '車検';
        return $this->render('index');
    }
}
