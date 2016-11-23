<?php
namespace backend\modules\maintenance\controllers;

use app\models\Sdptm05com;
use backend\controllers\WsController;
use yii\helpers\BaseUrl;
use Yii;
use backend\components\utilities;

/**
 * @author Thuanth6589 <thuanth6589@seta-asia.com.vn>
 * Class CommodityController
 * @package backend\modules\maintenance\controllers
 */
class CommodityController extends WsController
{
    public function actionIndex()
    {
        Yii::$app->params['titlePage'] = '設定';
        Yii::$app->view->title = '設定';
        return $this->render('index');
    }

    public function createDataExport()
    {
        $obj = new Sdptm05com();
        $product = $obj->getData([], 'M05_COM_CD, M05_NST_CD, M05_KIND_COM_NO, M05_LARGE_COM_NO, M05_MIDDLE_COM_NO, M05_KIND_DM_NO, M05_COM_NAMEN, M05_LIST_PRICE, M05_ORDER, M05_MEMO');
        return $product;
    }

    /**
     * action export
     */
    public function actionExport()
    {
        header('Content-Type: text/csv; charset=Shift_JIS');
        header('Content-Disposition: attachment; filename=commodity_'.date('Ymd').'.csv');
        $fp = fopen('php://output', 'w');
        $obj = new Sdptm05com();
        $header = mb_convert_encoding(implode(',', $obj->header), 'SJIS');
        fputcsv($fp, explode(',', $header));
        $data = $this->createDataExport();
        foreach ($data as $k => $v) {
            $v = mb_convert_encoding(implode(',', $v), 'SJIS');
            fputcsv($fp, explode(',', $v));
        }

        fclose($fp);
        exit();
    }

    /**
     * action import
     * @return \yii\web\Response
     */
    public function actionImport()
    {
        $request = Yii::$app->request;
        if ($request->isPost && isset($_FILES['commodity'])) {
            $obj = new Sdptm05com();
            if (substr($_FILES['commodity']['name'], -4) == '.csv') {
                $file = utilities::convertUtf8($_FILES['commodity']['tmp_name']);
                $result = $obj->saveImport($file);
                $error = $result['error'];
            } else {
                $error[] = 'ＣＳＶのフォーマットが正しくありません';
            }

            if (empty($error) && $result['insert']) {
                Yii::$app->session->setFlash('success', 'success');
            } else {
                Yii::$app->session->setFlash('error', $error);
            }

            return $this->redirect(BaseUrl::base(true).'/update-commodity');

        }
    }
}
