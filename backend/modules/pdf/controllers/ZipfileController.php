<?php
namespace backend\modules\pdf\controllers;

use app\models\Sdptd03denpyo;
use backend\components\utilities;
use Yii;
use yii\console\Controller;
use yii\helpers\BaseUrl;

class ZipfileController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction()
    {
        $session = \Yii::$app->session;
        if (!$session->get('login_admin_info')) {
            Yii::$app->response->redirect(BaseUrl::base(true) . '/operator/login');
            return false;
        }

        if ($login_info = $session->get('login_admin_info') and $login_info['expired'] < time()) {
            $session->remove('login_admin_info');
            unset($session['login_admin_info']);
        }

        if ($loginInfo = $session->get('login_admin_info')) {
            $login_info['expired'] = time() + Yii::$app->params['timeOutLogin'];
            $session->set('login_admin_info', $login_info);
        }
        return true;
    }


    private function equal3Time($time_file, $start_time = null, $end_time = null)
    {
        $time_file = intval($time_file);
        $start_time = intval($start_time);
        $end_time = intval($end_time);
        if (($time_file <= $end_time) && ($time_file >= $start_time)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Convert to format time
     */
    private function convertToDateFormat($string)
    {
        if (!isset($string) or ($s_length = mb_strlen(preg_replace('/\s+/', '', $string))) == 0) {
            return '';
        }
        if ($s_length == 8) {
            //20160729 => 16-Jul-29
            $string = mb_substr($string, 0, 4) . '/' . mb_substr($string, 4, 2) . '/' . mb_substr($string, 6, 2);
            $result = date('y-M-d', strtotime($string));
        } elseif ($s_length == 11) {
            //1970年01月01日 => 70-Jan-01
            $string = mb_substr($string, 0, 4) . '/' . mb_substr($string, 5, 2) . '/' . mb_substr($string, 8, 2);
            $result = date('y-M-d', strtotime($string));
        } else {
            $result = $string;
        }

        return $result;
    }

    /**
     * Get list denpyo status is 1
     * @return array
     * $author Dang Bui
     */
    private function getListDenpyoStatusOn()
    {
        $sdpt03denpyo = new Sdptd03denpyo();
        $listDenpyoNo = $sdpt03denpyo->getData(['D03_STATUS' => 1], 'D03_DEN_NO');
        foreach ($listDenpyoNo as $k => $v) {
            $listDenpyo[] = $v['D03_DEN_NO'];
        }
        return $listDenpyo;
    }

    /**
     * get list array file
     * @return string
     * @author: Dang Bui
     */
    public function getListFile($start_date = '', $end_date = '', $url_source = '')
    {
        if (!isset($start_date) || !isset($end_date)) {
            return false;
        }
        //Check folder exits
        if (!is_dir($url_source)) {
            return false;
        }

        $list_files = scandir($url_source);
        $list_files = array_diff($list_files, ['.', '..']);
        //Check file exits in folder
        if (empty($list_files)) {
            return false;
        }

        $arr_id = [];
        foreach ($list_files as $k => $v) {
            $filename = explode('.', $v);
            $extension = $filename[1];
            $create_time_file = date("Ymd", filectime($url_source . $v));
            if ($this->equal3Time($create_time_file, $start_date, $end_date)) {
                $arr_id[] = $filename[0];
            }
        }

        $file = [];
        $sdpt03denpyo = new Sdptd03denpyo();
        foreach ($arr_id as $k => $v) {
            $listDenpyoNo = $sdpt03denpyo->getData(['D03_STATUS' => 1, 'D03_DEN_NO' => $v], 'D03_DEN_NO');
            if (!empty($listDenpyoNo)) {
                $file[] = $url_source . $listDenpyoNo[0]['D03_DEN_NO'] . '.' . $extension;
            }
        }

        return $file;
    }

    /**
     * export pdf
     * @return string
     * @author: Dang Bui
     */
    public function exportPdf($start_date = null, $end_date = null, $folder_source = 'data/pdf/', $tmp_folder = '')
    {
        $zip = new \ZipArchive();
        $tmp_file = tempnam($tmp_folder, '');
        if ($zip->open($tmp_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            die('An error occurred creating your ZIP file.');
        }
        $arr_file = $this->getListFile($start_date, $end_date, $folder_source);
        if (empty($arr_file) || !$arr_file) {
            return false;
        }
        //Add file in zip
        foreach ($arr_file as $k => $v) {
            $new_filename = substr($v, strrpos($v, '/') + 1);
            $zip->addFile($v, $new_filename);
        }

        $zip->close();
        header('Content-disposition: attachment; filename=Pdf.zip');
        header('Content-type: application/zip');
        readfile($tmp_file);
        unlink($tmp_file);
        return true;
    }


    /**
     * export csv
     * @return string
     * @author: Dang Bui
     */
    public function exportCsv($start_date = null, $end_date = null, $folder_source = 'data/csv/', $tmp_folder = '')
    {
        $arr_file = $this->getListFile($start_date, $end_date, $folder_source);
        if (empty($arr_file) || !$arr_file) {
            return false;
        }

        $csv = [];
        foreach ($arr_file as $key => $value) {
            $file = fopen($value, 'r');
            $csv[0] = fgetcsv($file);
            while (($data = fgetcsv($file)) !== false) {
                if ($data[0] != '') {
                    $csv[] = $data;
                }
            }
        }
        if (count($csv) <= 1) {
            return false;
        }

        header('Content-Type: text/csv; charset=shift-jis');
        header('Content-Disposition: attachment; filename=保証リスト.csv');
        $fp = fopen('php://output', 'w');
        foreach ($csv as $k => $v) {
            $v['1'] = $this->convertToDateFormat($v['1']);
            $v['2'] = $this->convertToDateFormat($v['2']);
            $v = implode('","', $v);
            $v = mb_convert_encoding($v, 'shift-jis');
            if ($k == 0) {
                $v = str_replace('?', '', $v);
            }
            $v = explode('","', $v);
            fputcsv($fp, $v);
        }

        fclose($fp);
        return true;
    }


    public function actionIndex()
    {
        if ($data = \Yii::$app->request->post()) {
            $tmp_folder = 'data/tmp/';
            utilities::createFolder($tmp_folder);//Create folder data/zip

            $start_date = $data['start_year'] . $data['start_month'] . $data['start_day'];
            $end_date = $data['end_year'] . $data['end_month'] . $data['end_day'];
            if ($data['type-download'] == 'csv') {
                $folder_source = 'data/csv/';
                $isStatus = $this->exportCsv($start_date, $end_date, $folder_source, $tmp_folder);
                if ($isStatus) {
                    die;
                }
            } elseif ($data['type-download'] == 'pdf') {
                $folder_source = 'data/pdf/';
                $isStatus = $this->exportPdf($start_date, $end_date, $folder_source, $tmp_folder);
            } else {
                \Yii::$app->params['titlePage'] = 'パンク保証データダウンロード';
                \Yii::$app->view->title = 'パンク保証データダウンロード';
                $this->layout = '@backend/views/layouts/blank';
                return $this->render('index');
            }

            if (!$isStatus) {
                Yii::$app->session->setFlash('error', 'ファイルがありません');
            } else {
                Yii::$app->session->setFlash('success', 'ＰＤＦファイルを作りました。');
            }
        }
        //Get date time in select box
        $yesterday = date("Y-m-d", strtotime("- 1 day"));
        $year_now = date('Y');
        $year = date('Y', strtotime($yesterday));
        $day = date('d', strtotime($yesterday));
        $month = date('m', strtotime($yesterday));
        $select_date = ['year_now' => [$year_now - 2 => $year_now - 2, $year_now - 1 => $year_now - 1, $year_now => $year_now], 'year' => $year, 'day' => $day, 'month' => $month];

        \Yii::$app->params['titlePage'] = 'パンク保証データダウンロード';
        \Yii::$app->view->title = 'パンク保証データダウンロード';
        $this->layout = '@backend/views/layouts/blank';
        return $this->render('index', ['select_date' => $select_date]);
    }
}
