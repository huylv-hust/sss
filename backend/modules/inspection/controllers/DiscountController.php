<?php
namespace backend\modules\inspection\controllers;

use app\models\DenpyoInspection;
use app\models\DiscountPackages;
use app\models\Discounts;
use app\models\ParentDiscount;
use backend\controllers\WsController;
use Yii;
use yii\data\Pagination;
use yii\helpers\BaseUrl;

class DiscountController extends WsController
{
    public function actionIndex()
    {
        $obj_denpyo_inspection = new DenpyoInspection();
        $obj_parent = new ParentDiscount();
        $obj_package = new DiscountPackages();
        $obj_discount = new Discounts();
        $request = Yii::$app->request;

        //get data by id
        $parent_id = $request->get('id', '');
        $discount_used = [-1];
        $package_used = [-1];
        if ($parent_id) {
            if (!$parent = $obj_parent = ParentDiscount::findOne($parent_id)) {
                Yii::$app->session->setFlash('error', '割引・割増料金は存在しません。');
                $this->redirect(BaseUrl::base(true) . '/discount/list');
            }
            $package = $obj_package->getDataByParent($parent_id);
            $discount = [];
            foreach ($package as $k => $v) {
                $discount[$k] = $obj_discount->getDataByPackage($v['ID']);
            }
            $tmp = $obj_denpyo_inspection->getData(['PARENT_DISCOUNT_ID' => $parent_id], 'DISCOUNTS');
            $discount_used = ',';
            foreach ($tmp as $k => $v) {
                $discount_used .= trim($v['DISCOUNTS'], ',') . ',';
            }
            $discount_used = trim($discount_used, ',');
            $discount_used = !$discount_used ? [-1] : array_unique(explode(',', $discount_used));
            $package_used = $obj_discount->getData(['ID_IN' => implode(',', $discount_used)], 'DISCOUNT_PACKAGES_ID');
            $package_used = empty($package_used) ? [] : array_unique(array_column($package_used, 'DISCOUNT_PACKAGES_ID'));
        }

        //add
        if ($request->isPost) {
            $login_info = Yii::$app->session->get('login_info');
            $parent = $request->post('parent');
            $parent['SS_CD'] = $login_info['M50_SS_CD'];
            $package = $request->post('package', []);
            $discount = $request->post('discount', []);
            $discount_remove = $request->post('discount_remove');
            $package_remove = $request->post('package_remove');

            if ($obj_parent->saveData($parent, $package, $discount, $discount_remove, $package_remove)) {
                Yii::$app->session->setFlash('success', '保存を完了しました。');
                $url = Yii::$app->session->get('url_list_discount') && $parent_id ? Yii::$app->session->get('url_list_discount') : BaseUrl::base(true) . '/discount/list';
                return $this->redirect($url);
            }

            Yii::$app->session->setFlash('error', '保存を完了しません。');
        }

        Yii::$app->params['titlePage'] = '割引・割増料金登録・編集';
        Yii::$app->view->title = '割引・割増料金登録・編集';

        return $this->render('index', compact('parent', 'package', 'discount', 'discount_used', 'package_used'));
    }

    public function actionList()
    {
        $obj = new ParentDiscount();

        //pagination
        $pagination = new Pagination([
            'totalCount' => $obj->countData(),
            'defaultPageSize' => Yii::$app->params['defaultPageSize']
        ]);
        $filters['limit'] = $pagination->limit;
        $filters['offset'] = $pagination->offset;

        //get data
        $discount = $obj->getData($filters);

        //store url
        $filter = Yii::$app->request->get();
        $query_string = empty($filter) ? '' : '?' . http_build_query($filter);
        Yii::$app->session->set('url_list_discount', BaseUrl::base() . '/discount/list' . $query_string);

        Yii::$app->params['titlePage'] = '割引・割増料金設定一覧';
        Yii::$app->view->title = '割引・割増料金設定一覧';

        return $this->render('list', compact('pagination', 'discount'));
    }

    public function actionDetail($id)
    {
        $obj_package = new DiscountPackages();
        $obj_discount = new Discounts();

        //Get SS_Code
        $login_info = Yii::$app->session->get('login_info');
        if (isset($login_info['M50_SS_CD']) && $login_info['M50_SS_CD'] != '') {
            $ss_cd = $login_info['M50_SS_CD'];
        }
        if (!$parent = ParentDiscount::findOne($id)) {
            Yii::$app->session->setFlash('error', '割引・割増料金は存在しません。');
            $this->redirect(BaseUrl::base(true) . '/discount/list');
        }
        $package = $obj_package->getDataByParent($id);

        $discount = [];
        foreach ($package as $k => $v) {
            $discount[$k] = $obj_discount->getDataByPackage($v['ID']);
        }

        Yii::$app->params['titlePage'] = '割引・割増詳細';
        Yii::$app->view->title = '割引・割増詳細';

        return $this->render('detail', compact('ss_cd', 'parent', 'package', 'discount'));
    }

    public function actionRemove()
    {
        $obj = new ParentDiscount();
        $id = Yii::$app->request->post('parent_id');
        $url = Yii::$app->session->get('url_list_discount') ? Yii::$app->session->get('url_list_discount') : BaseUrl::base(true) . '/discount/list';
        if (!ParentDiscount::findOne($id)) {
            return $this->redirect($url);
        }

        $result = $obj->deleteData($id);
        if ($result == 0) {
            Yii::$app->session->setFlash('success', '削除を完了しました。');
        } elseif ($result == 1) {
            Yii::$app->session->setFlash('error', '割引・割増は使用中、削除できません。');
        } else {
            Yii::$app->session->setFlash('error', '削除をできません。');
        }

        return $this->redirect($url);
    }
}
