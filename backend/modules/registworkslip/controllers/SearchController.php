<?php

namespace backend\modules\registworkslip\controllers;

use app\models\Sdptm05com;
use backend\components\api;
use Yii;
use backend\controllers\WsController;
use yii\data\Pagination;

class SearchController extends WsController
{
    public function actionIndex()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $obj = new Sdptm05com();
            $condition = 'M05_COM_CD';
            $condition_1 = trim($request->post('condition_1'), ',');
            if ($condition_1) {
                $condition_1 = ',' . $condition_1 . ',';
            }

            $a_search = [
                '1' => '7,8',
                '2' => '1,2,3,4,5,6',
                '3' => '10',
                '4' => '24,25,26',
                '5' => '27',
                '6' => '21',
                '7' => '0',
            ];

            $kind_com_search_in = '';
			$kind_com_search_not_in = '';

			if($condition_1 != ',7,') {

				if($condition_1 == ',8,')
				{
					$data['filters']['in_concat'] = Yii::$app->params['search_cate'];
				}
				else
				{
					foreach ($a_search as $key => $val) {
						if (substr_count($condition_1, ',' . $key . ',')) {
							$kind_com_search_in .= $val . ',';
						}
					}
				}
			}
			else {
				foreach ($a_search as $key => $val) {
					$kind_com_search_not_in .= $val . ',';
				}
			}


			if ($kind_com_search_in) {
                $data['filters']['in'] = explode(',', trim($kind_com_search_in, ','));
            }

			if($kind_com_search_not_in) {
				$data['filters']['not_in'] = explode(',', trim($kind_com_search_not_in, ','));
			}

            $data['filters']['code'] = $request->post('value');
            $data['count'] = $obj->coutData($data['filters']);
            $data['pagination'] = new Pagination([
                'totalCount' => $data['count'],
                'defaultPageSize' => 10,
                'page' => $request->post('page')
            ]);

            $data['filters']['offset'] = $data['pagination']->offset;
            $data['filters']['limit'] = $data['pagination']->limit;

            $data['product'] = $obj->getData($data['filters']);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $data;
        }
    }

    public function actionAddress()
    {
        $request = Yii::$app->request;
        $api = new api();
        if ($request->isAjax) {
            $info = $api->getAddrFromZipcode($request->post('zipcode'));
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (count($info) > 1) {
                $info = false;
            }
            return $info;
        }
    }
}
