<?php
namespace backend\components;

class utilities
{
    public static function getAllBranch()
    {
        $api = new api();
        $ss = $api->getSsName();
        $all_branch = [];
        $all_ss = [];
        $ss_address = [];
        $ss_tel = [];
        foreach ($ss as $k => $v) {
            $all_branch[$v['branch_code']] = $v['branch_name'];
            $all_ss[$v['sscode']] = $v['ss_name'];
            $all_ss_branch[$v['sscode']] = $v['branch_code'];
            $all_branch_ss[$v['branch_code']][] = $v['sscode'];
            $ss_address[$v['sscode']] = $v['address'];
            $ss_tel[$v['sscode']] = $v['tel'];
        }

        return [
            'all_ss' => $all_ss,
            'all_branch' => \Yii::$app->params['branch'],
            'all_branch_ss' => $all_branch_ss,
            'all_ss_branch' => $all_ss_branch,
            'ss_address' => $ss_address,
            'ss_tel' => $ss_tel,
        ];
    }

    public static function convertUtf8($file)
    {
        $data = file_get_contents($file);
        if (mb_detect_encoding($data, 'UTF-8', true) === false) {
            $encode_ary = [
                'ASCII',
                'JIS',
                'eucjp-win',
                'sjis-win',
                'EUC-JP',
                'UTF-8',
            ];
            $data = mb_convert_encoding($data, 'UTF-8', $encode_ary);
        }

        $fp = tmpfile();
        fwrite($fp, $data);
        rewind($fp);
        return $fp;
    }

    public static function convertSJIS($file)
    {
        $data = file_get_contents($file);
        if (mb_detect_encoding($data, 'SJIS', true) === false) {
            $encode_ary = [
                'ASCII',
                'JIS',
                'eucjp-win',
                'sjis-win',
                'EUC-JP',
                'UTF-8',
            ];
            $data = mb_convert_encoding($data, 'SJIS', $encode_ary);
        }

        $fp = tmpfile();
        fwrite($fp, $data);
        rewind($fp);
        return $fp;
    }


    /**
     * @inheritdoc
     * delete cookie
     * @author: dangbc6591
     */
    public static function deleteCookie($namecookie)
    {
        $cookies = \Yii::$app->response->cookies;
        $cookies->remove($namecookie);
        unset($cookies[$namecookie]);
    }

    /**
     * Create folder in server if folder not exist
     */
    public static function createFolder($path)
    {
        $string_path = explode('/', $path);
        $string = '';
        foreach ($string_path as $k => $v) {
            $string = $string . '/' . $v;
            $string = trim($string, '/');
            if (!is_dir($string)) {
                mkdir($string);
            }
        }
    }

    public static function to_jp_date($year,$month = 1,$day = 1)
    {
        if( ! checkdate($month, $day, $year) || $year < 1800)
        {
            return false;
        }

        $date = (int) sprintf('%04d%02d%02d', $year, $month, $day);
        if($date >= 19890108)
        {
            $era = '平成';
            $jp_year = $year - 1988;
        }

        elseif($date >= 19261225)
        {
            $era = '昭和';
            $jp_year = $year - 1925;
        }

        elseif($date >= 19120730)
        {
            $era = '大正';
            $jp_year = $year - 1911;
        }

        elseif($date >= 18680125)
        {
            $era = '明治';
            $jp_year = $year - 1867;
        }

        elseif($date >= 18650407)
        {
            $era = '慶応';
            $jp_year = $year - 1864;
        }

        elseif ($date >= 18640220)
        {
            $era = '元治';
            $jp_year = $year - 1863;
        }

        elseif ($date >= 18610219)
        {
            $era = '文久';
            $jp_year = $year - 1860;
        }

        elseif ($date >= 18600318)
        {
            $era = '万延';
            $jp_year = $year - 1859;
        }

        elseif ($date >= 18541127)
        {
            $era = '安政';
            $jp_year = $year - 1853;
        }

        elseif ($date >= 18480228)
        {
            $era = '嘉永';
            $jp_year = $year - 1847;
        }

        elseif ($date >= 18441202)
        {
            $era = '弘化';
            $jp_year = $year - 1843;
        }

        elseif ($date >= 18301210)
        {
            $era = '天保';
            $jp_year = $year - 1829;
        }

        elseif($date >= 18180422)
        {
            $era = '文政';
            $jp_year = $year - 1817;
        }

        elseif($date >= 18040211)
        {
            $era = '文化';
            $jp_year = $year - 1803;
        }

        elseif ($date >= 18010205)
        {
            $era = '享和';
            $jp_year = $year - 1800;
        }

        else
        {
            $era = '寛政';
            $jp_year = $year - 1789;
        }

        $wareki = null;
        if ($jp_year == 1)
        {
            $wareki = $era.'元年';
        }

        else
        {
            $wareki = $era.$jp_year.'年';
        }

        return $wareki;
    }

    public static function log($message, $fileName = 'debug')
    {
        if (is_string($message) == false) {
            $message = var_export($message, true);
        }

        $logFile = \Yii::$app->getRuntimePath() . '/logs/' . $fileName . '.log';
        error_log('[' . date('Y-m-d H:i:s') . ']' . $message . "\n", 3, $logFile);
        chmod($logFile, 0666);
    }
}
