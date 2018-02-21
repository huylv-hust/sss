<?php
/**
 * Api class
 * @author NamDD <namdd6566@seta-asia.com.vn>
 * @date 08/05/2015
 */
namespace backend\components;

use linslin\yii2\curl;

class api
{
    public static $api = [];

    public function __construct()
    {
        static::$api = \Yii::$app->params['api'];
    }

    /**
     * get sscode
     * @author NamDD
     * @since 1.0.0
     * @param
     * @return array ss
     */

    protected static function _api($url, $params, $method = 'post')
    {
        $curl = new curl\Curl();
        $curl->setOption(CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        if (isset(static::$api['proxy'])) {
            $curl->set_option(CURLOPT_PROXY, static::$api['proxy']);
        }

        if ($method == 'post') {
            $curl->setOption(CURLOPT_POSTFIELDS, json_encode($params, JSON_UNESCAPED_UNICODE));
        } else {
            $url = $url . '?' . http_build_query($params);
        }

        $res = $curl->$method($url);
        $res = json_decode($res, true);

        $status = $curl->responseCode;
        if ($status == 200) {
            \Yii::info('Api result: ' . print_r($params, true) . print_r($res, true));
            return $res;
        }

        \Yii::info('Api error: ' . print_r($params, true) . print_r($res, true));
        return [];
    }

    public static function updateCar($kaiinCd, $carLength, $carField)
    {
        $url = static::$api['car']['url_update_member_car'];
        $params = [
            'secret' => static::$api['secret'],
            'carLength' => (string)$carLength,
            'kaiinCd' => $kaiinCd,
        ];

        $temp = [];
        unset($carField['carLength']);
        foreach ($carField as $keys => $vals) {
            foreach ($vals as $key => $val) {
                if ($key != 'carLength') {
                    $temp[$key][] = $val;
                }
            }
        }
        $params = array_merge($params, $temp);
        $res = self::_api($url, $params);
        return $res;
    }

    public static function getSsName($sscode = '')
    {

        if ($sscode === '') {
            $url = static::$api['ss']['url_ss'];
        } else {
            $url = static::$api['ss']['url_ss'] . '?sscode=' . $sscode;
        }

        if ($res = \Yii::$app->cache->get('ss' . $sscode)) {
            return $res;
        }

        $res = self::_api($url, [], 'get');
        \Yii::$app->cache->set('ss' . $sscode, $res, 300);
        return $res;
    }

    public static function search($branch_code = '', $keywork = '')
    {
        $url = static::$api['ss']['url_ss'];
        $params = [
            'branch_code' => $branch_code,
            'keyword' => $keywork,
        ];
        $res = self::_api($url, $params, 'get');
        return $res;
    }

    /**
     * get info card
     * @param type $card_no
     * @return array card_infor empty array error 500 else
     */
    public static function getInfoCard($card_no)
    {
        $url = static::$api['member']['url_card'];
        $params = [
            'secret' => static::$api['secret'],
            'cardNo' => $card_no,
        ];

        $res = self::_api($url, $params);
        if (count($res) == 0) {
            return ['result' => 500];
        }

        if (array_key_exists('member_kaiinCd', $res)) {
            $res['result'] = 1;
            return $res;
        }

        return ['result' => 3];

    }

    /**
     * get info card
     * @param type $card_no
     * @return array card_infor empty array error 500 else
     */
    public static function getInfoCardTop($card_no)
    {
        $res = self::getInfoCard($card_no);
        if (array_key_exists('member_kaiinCd', $res)) {
            return $res;
        } else {
            return false;
        }
    }

    /**
     * get list info card
     * @param type kaiinCd
     * @return array card_infor empty array error 500 else
     */
    public static function getInfoListCar($kaiinCd)
    {
        $url = static::$api['car']['url_car_info'];
        $params = [
            'secret' => static::$api['secret'],
            'kaiinCd' => $kaiinCd,
        ];

        $res = self::_api($url, $params);

        if (count($res) == 0) {
            return false;
        }

        return $res;
    }

    /**
     * get list info card
     * @param type kaiinCd
     * @return array card_infor empty array error 500 else
     */
    public static function getInfoListCard($kaiinCd)
    {
        $url = static::$api['car']['url_card_info'];
        $params = [
            'secret' => static::$api['secret'],
            'kaiinCd' => $kaiinCd,
        ];

        $res = self::_api($url, $params);

        if (count($res) == 0) {
            return false;
        }

        return $res;
    }

    /**
     * update card number
     * @param type kaiinCd
     * @return true if update success, false if update error
     */
    public static function updateCardNumber($kaiinCd, $info_card)
    {
        $url = static::$api['car']['url_update_member_card'];
        $params = [
            'secret' => static::$api['secret'],
            'kaiinCd' => $kaiinCd,
            'cardLength' => $info_card['cardLength'],
            'card_cardBangou' => $info_card['card_cardBangou'],
            'cardLength' => $info_card['cardLength'],
            'card_cardKbn' => $info_card['card_cardKbn'],
            'card_cardSort' => $info_card['card_cardSort'],
            'card_upCreFlg' => $info_card['card_upCreFlg'],
        ];

        $res = self::_api($url, $params);

        if (count($res)) {
            return true;
        }


        return false;
    }

    /**
     *
     * @param type $kaiincd
     * @param type $values
     * @return array
     */
    public static function updateMemberBasic($kaiincd, $values = [])
    {
        if (array_key_exists('member_yuubinBangou', $values) && strlen($values['member_yuubinBangou']) == 0) {
            unset($values['member_yuubinBangou']);
        }
        $url = static::$api['member']['url_update_member'];
        $values['secret'] = static::$api['secret'];
        $values['kaiinCd'] = $kaiincd;
        $res = self::_api($url, $values);
        if (count($res)) {
            return true;
        }

        return false;
    }

    /**
     *
     */
    public static function getMemberBaseInfo($member_id)
    {
        return Api::get_member_info($member_id, 'result,member_telNo2,member_telNo1,member_kaiinName,member_kaiinKana,member_mailAddress1,member_mailAddress2');
    }

    public static function getMemberInfo($member_id, $list_title = '', $mail_addr = '', $mob_id = '')
    {
        if ($member_id == '' && $mail_addr == '' && $mob_id == '') {
            return -1;
        }

        $values = [];
        $url = static::$api['member']['url_member'];
        $values['secret'] = static::$api['secret'];
        if ($member_id != '') {
            $values['kaiinCd'] = $member_id;
        }

        if ($mail_addr != '') {
            $values['mailAddr'] = $mail_addr;
        }

        if ($mob_id != '') {
            $values['mobId'] = $mob_id;
        }

        if ($list_title != '') {
            $list_title = $list_title . ',member_kaiinCd';
            $values['columns'] = $list_title;
        }

        $res = self::_api($url, $values);

        if (count($res) == 0) {
            return -1;
        }

        return $res;
    }


    public static function getListMaker()
    {

        $res = \yii::$app->cache->get('list_maker');
        if ($res) {
            return $res;
        }

        $url = static::$api['car']['url_car'];
        $res = self::_api($url, [], 'get');
        if (count($res)) {
            \Yii::$app->cache->set('list_maker', $res, 3600 * 24);
            return $res;
        }

        return false;
    }

    public static function getListModel($maker_code)
    {


        if ($res = \Yii::$app->cache->get('maker_code')) {
            //return $res;
        }

        $res = self::getCarInfo($maker_code);
        if (count($res)) {
            \Yii::$app->cache->set('maker_code', $res, 3600 * 24);
            return $res;
        }

        return false;

    }

    public static function getListYearMonth($maker_code, $model_code)
    {
        return self::getCarInfo($maker_code, $model_code);
    }

    public static function getListTypeCode($maker_code, $model_code, $year)
    {
        return self::getCarInfo($maker_code, $model_code, $year);
    }

    public static function getListGradeCode($maker_code, $model_code, $year, $type_code)
    {
        return self::getCarInfo($maker_code, $model_code, $year, $type_code);
    }


    private static function getCarInfo($maker_code = '', $model_code = '', $year = '', $type_code = '')
    {
        $url = static::$api['car']['url_car'];
        $values = [];
        if ($maker_code != '') {
            $values['maker_code'] = $maker_code;
        }

        if ($model_code != '') {
            $values['model_code'] = $model_code;
        }

        if ($year != '') {
            $values['year'] = $year;
        }

        if ($type_code != '') {
            $values['type_code'] = $type_code;
        }

        $res = self::_api($url, $values, 'get');
        return $res;
    }

    public static function getMembers($member_id, $list_title = 'members_kaiinKana')
    {
        $url = static::$api['member']['url_member_list'];
        $values['secret'] = static::$api['secret'];
        $values['kaiinCd'] = $member_id;
        $values['columns'] = $list_title . ',members_kaiinCd';
        $res = self::_api($url, $values);

        if (count($res) == 0) {
            return -1;
        }


        $member = [];
        if (array_key_exists('members_kaiinCd', $res)) {
            $members_kaiincd = $res['members_kaiinCd'];
            for ($i = 0; $i < count($members_kaiincd); ++$i) {
                $member[$members_kaiincd[$i]] = $res['members_kaiinKana'][$i];
            }

            return $member;
        }

        return -1;
    }

    public static function searchSs($params, $only_opened = true)
    {
        array_filter($params);
        if ($only_opened) {
            $params['only_opened'] = 1;
        }

        if ($res = \Yii::$app->cache->get(md5(json_encode($params)))) {
            return $res;
        }
        $url = static::$api['ss']['url_ss'];
        $res = self::_api($url, $params, 'get');
        if (count($res)) {
            \Yii::$app->cache->set(md5(json_encode($params)), $res, 3600 * 24);
            return $res;

        }

        return false;

    }

    public function getAddrFromZipcode($zipcode)
    {
        $url = static::$api['zipcode']['url_addr'];
        $params = ['zipcode' => $zipcode];
        $res = self::_api($url, $params, 'get');
        if (count($res) == 0) {
            return false;
        }
        return $res;
    }

    public function getZipcodeFromSscode($sscode)
    {
        $url = static::$api['ss']['url_ss'];
        $params = ['sscode' => $sscode];
        $res = self::_api($url, $params, 'get');
        if (count($res) == 0) {
            return false;
        }
        return $res;
    }
}
