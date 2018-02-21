<?php

namespace backend\components;

class csv
{
    public static function writecsv($post = [])
    {
        $branch = utilities::getAllBranch();
        $branch_code = isset($branch['all_ss_branch'][$post['D01_SS_CD']]) ? $branch['all_ss_branch'][$post['D01_SS_CD']] : '';
        $branch_name = isset($branch['all_branch'][$branch_code]) ? $branch['all_branch'][$branch_code] : '';
        $ss_name = isset($branch['all_ss'][$post['D01_SS_CD']]) ? $branch['all_ss'][$post['D01_SS_CD']] : '';

        if ($post['D01_MOBTEL_NO'] && $post['D01_TEL_NO']) {
            $post['TEL_NUMBER'] = $post['D01_TEL_NO'] . ',' . $post['D01_MOBTEL_NO'];
        } elseif ($post['D01_MOBTEL_NO']) {
            $post['TEL_NUMBER'] = $post['D01_MOBTEL_NO'];
        } elseif ($post['D01_TEL_NO']) {
            $post['TEL_NUMBER'] = $post['D01_TEL_NO'];
        } else {
            $post['TEL_NUMBER'] = '';
        }

        $data[0] = [
            '保証書番号',
            '保証期間',
            '購入日',
            '購入本数',
            '顧客名',
            'フリガナ',
            '郵便番号',
            '住所',
            '電話番号',
            '車名',
            '車番',
            '右前メーカー',
            '右前商品名',
            '右前サイズ',
            '右前セリアル',
            '右前本数',
            '左前メーカー',
            '左前商品名',
            '左前サイズ',
            '左前セリアル',
            '左前本数',
            '右後メーカー',
            '右後商品名',
            '右後サイズ',
            '右後セリアル',
            '右後本数',
            '左後メーカー',
            '左後商品名',
            '左後サイズ',
            '左後セリアル',
            '左後本数',
            'その他Ａメーカー',
            'その他Ａ商品名',
            'その他Ａサイズ',
            'その他Ａセリアル',
            'その他Ａ本数',
            'その他Ｂメーカー',
            'その他Ｂ商品名',
            'その他Ｂサイズ',
            'その他Ｂセリアル',
            'その他Ｂ本数',
            'POS伝票番号',
            '支店コード',
            '支店名',
            'SSコード',
            'SS名',
            '作業伝票番号',
        ];
        $data[1] = [
            'warranty_card_number' => isset($post['M09_WARRANTY_NO']) ? $post['M09_WARRANTY_NO'] : '',
            'warranty_period' => isset($post['warranty_period']) ? $post['warranty_period'] : '',
            'purchase_date' => isset($post['M09_INP_DATE']) ? $post['M09_INP_DATE'] : '',
            'purchase_no' => isset($post['D05_SURYO']) ? $post['D05_SURYO'] : '',
            'D01_CUST_NAMEN' => isset($post['D01_CUST_NAMEN']) ? $post['D01_CUST_NAMEN'] : '',
            'D01_CUST_NAMEK' => isset($post['D01_CUST_NAMEK']) ? $post['D01_CUST_NAMEK'] : '',
            'D01_YUBIN_BANGO' => isset($post['D01_YUBIN_BANGO']) && trim($post['D01_YUBIN_BANGO']) != '' ? substr($post['D01_YUBIN_BANGO'], 0, 3) . '-' . substr($post['D01_YUBIN_BANGO'], 3, 4) : '',
            'D01_ADDR' => isset($post['D01_ADDR']) ? $post['D01_ADDR'] : '',
            'TEL_NUMBER' => $post['TEL_NUMBER'],
            'D02_MODEL_CD' => isset($post['D02_CAR_NAMEN_' . $post['D02_CAR_SEQ_SELECT']]) ? $post['D02_CAR_NAMEN_' . $post['D02_CAR_SEQ_SELECT']] : '',
            'D02_CAR_NO' => isset($post['D02_CAR_NO_' . $post['D02_CAR_SEQ_SELECT']]) ? $post['D02_RIKUUN_NAMEN_' . $post['D02_CAR_SEQ_SELECT']] . ' ' . $post['D02_CAR_ID_' . $post['D02_CAR_SEQ_SELECT']] . ' ' . $post['D02_HIRA_' . $post['D02_CAR_SEQ_SELECT']] . ' ' . $post['D02_CAR_NO_' . $post['D02_CAR_SEQ_SELECT']] : '',
            'right_front_manu' => isset($post['right_front_manu']) ? $post['right_front_manu'] : '',
            'right_front_product' => isset($post['right_front_product']) ? $post['right_front_product'] : '',
            'right_front_size' => isset($post['right_front_size']) ? $post['right_front_size'] : '',
            'right_front_serial' => isset($post['right_front_serial']) ? $post['right_front_serial'] : '',
            'right_front_no' => isset($post['right_front_no']) && $post['right_front_no'] ? $post['right_front_no'] : '',
            'left_front_manu' => isset($post['left_front_manu']) ? $post['left_front_manu'] : '',
            'left_front_product' => isset($post['left_front_product']) ? $post['left_front_product'] : '',
            'left_front_size' => isset($post['left_front_size']) ? $post['left_front_size'] : '',
            'left_front_serial' => isset($post['left_front_serial']) ? $post['left_front_serial'] : '',
            'left_front_no' => isset($post['left_front_no']) && $post['left_front_no'] ? $post['left_front_no'] : '',
            'right_behind_manu' => isset($post['right_behind_manu']) ? $post['right_behind_manu'] : '',
            'right_behind_product' => isset($post['right_behind_product']) ? $post['right_behind_product'] : '',
            'right_behind_size' => isset($post['right_behind_size']) ? $post['right_behind_size'] : '',
            'right_behind_serial' => isset($post['right_behind_serial']) ? $post['right_behind_serial'] : '',
            'right_behind_no' => isset($post['right_behind_no']) && $post['right_behind_no'] ? $post['right_behind_no'] : '',
            'left_behind_manu' => isset($post['left_behind_manu']) ? $post['left_behind_manu'] : '',
            'left_behind_product' => isset($post['left_behind_product']) ? $post['left_behind_product'] : '',
            'left_behind_size' => isset($post['left_behind_size']) ? $post['left_behind_size'] : '',
            'left_behind_serial' => isset($post['left_behind_serial']) ? $post['left_behind_serial'] : '',
            'left_behind_no' => isset($post['left_behind_no']) && $post['left_behind_no'] ? $post['left_behind_no'] : '',
            'other_a_manu' => isset($post['other_a_manu']) ? $post['other_a_manu'] : '',
            'other_a_product' => isset($post['other_a_product']) ? $post['other_a_product'] : '',
            'other_a_size' => isset($post['other_a_size']) ? $post['other_a_size'] : '',
            'other_a_serial' => isset($post['other_a_serial']) ? $post['other_a_serial'] : '',
            'other_a_no' => isset($post['other_a_no']) && $post['other_a_no'] ? $post['other_a_no'] : '',
            'other_b_manu' => isset($post['other_b_manu']) ? $post['other_b_manu'] : '',
            'other_b_product' => isset($post['other_b_product']) ? $post['other_b_product'] : '',
            'other_b_size' => isset($post['other_b_size']) ? $post['other_b_size'] : '',
            'other_b_serial' => isset($post['other_b_serial']) ? $post['other_b_serial'] : '',
            'other_b_no' => isset($post['other_b_no']) && $post['other_b_no'] ? $post['other_b_no'] : '',
            'D03_POS_DEN_NO' => isset($post['D03_POS_DEN_NO']) ? $post['D03_POS_DEN_NO'] : '',
            'branch_code' => $branch_code,
            'branch_name' => $branch_name,
            'D01_SS_CD' => isset($post['D01_SS_CD']) ? $post['D01_SS_CD'] : '',
            'ss_name' => $ss_name,
            'D03_DEN_NO' => isset($post['D03_DEN_NO']) ? $post['D03_DEN_NO'] : '',
        ];
        utilities::createFolder('data/csv/');
        $fp = fopen(getcwd() . '/data/csv/' . $post['D03_DEN_NO'] . '.csv', 'w+');
        fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        foreach ($data as $key => $value) {
            fputcsv($fp, $value);
        }
        fclose($fp);
    }

    public static function readcsv($post = [])
    {
        if (file_exists(getcwd() . '/data/csv/' . $post['D03_DEN_NO'] . '.csv')) {
            $data = file_get_contents(getcwd() . '/data/csv/' . $post['D03_DEN_NO'] . '.csv');
            if (substr($data, 0, 3) == "\xEF\xBB\xBF") {
                $data = substr($data, 3);
            }

            if (mb_detect_encoding($data, "UTF-8", true) === false) {
                $encode_ary = ["ASCII", "JIS", "eucjp-win", "sjis-win", "EUC-JP", "UTF-8"];
                $data = mb_convert_encoding($data, 'UTF-8', $encode_ary);
            }

            $fp = tmpfile();
            fwrite($fp, $data);
            rewind($fp);

            $title = fgetcsv($fp);
            $data = fgetcsv($fp);
            $result = [
                'M09_WARRANTY_NO' => $data['0'],
                'warranty_period' => $data['1'],
                'M09_INP_DATE' => $data['2'],
                'D05_SURYO' => $data['3'],
                'D01_CUST_NAMEN' => $data['4'],
                'D01_CUST_NAMEK' => $data['5'],
                'D01_YUBIN_BANGO' => $data['6'],
                'D01_ADDR' => $data['7'],
                'TEL_NUMBER' => $data['8'],
                'D02_MODEL_CD' => $data['9'],
                'D02_CAR_NO' => $data['10'],
                'right_front_manu' => $data['11'],
                'right_front_product' => $data['12'],
                'right_front_size' => $data['13'],
                'right_front_serial' => $data['14'],
                'right_front_no' => $data['15'],
                'left_front_manu' => $data['16'],
                'left_front_product' => $data['17'],
                'left_front_size' => $data['18'],
                'left_front_serial' => $data['19'],
                'left_front_no' => $data['20'],
                'right_behind_manu' => $data['21'],
                'right_behind_product' => $data['22'],
                'right_behind_size' => $data['23'],
                'right_behind_serial' => $data['24'],
                'right_behind_no' => $data['25'],
                'left_behind_manu' => $data['26'],
                'left_behind_product' => $data['27'],
                'left_behind_size' => $data['28'],
                'left_behind_serial' => $data['29'],
                'left_behind_no' => $data['30'],
                'other_a_manu' => $data['31'],
                'other_a_product' => $data['32'],
                'other_a_size' => $data['33'],
                'other_a_serial' => $data['34'],
                'other_a_no' => $data['35'],
                'other_b_manu' => $data['36'],
                'other_b_product' => $data['37'],
                'other_b_size' => $data['38'],
                'other_b_serial' => $data['39'],
                'other_b_no' => $data['40'],
                'D03_POS_DEN_NO' => $data['41'],
                'branch_code' => $data['42'],
                'branch_name' => $data['43'],
                'D01_SS_CD' => $data['44'],
                'ss_name' => $data['45'],
                'D03_DEN_NO' => $data['46'],
            ];
            return $result;
        }
        return self::defaultcsv();
    }

    public static function deletecsv($post = [])
    {
        if (isset($post['D03_DEN_NO']) && file_exists('data/csv/' . $post['D03_DEN_NO'] . '.csv')) {
            return unlink('data/csv/' . $post['D03_DEN_NO'] . '.csv');
        }
        return true;
    }

    public function defaultcsv()
    {
        $result = [
            'M09_WARRANTY_NO' => '',
            'warranty_period' => '',
            'M09_INP_DATE' => '',
            'D05_SURYO' => '',
            'D01_CUST_NAMEN' => '',
            'D01_CUST_NAMEK' => '',
            'D01_YUBIN_BANGO' => '',
            'D01_ADDR' => '',
            'TEL_NUMBER' => '',
            'D02_MODEL_CD' => '',
            'D02_CAR_NO' => '',
            'right_front_manu' => '',
            'right_front_product' => '',
            'right_front_size' => '',
            'right_front_serial' => '',
            'right_front_no' => '',
            'left_front_manu' => '',
            'left_front_product' => '',
            'left_front_size' => '',
            'left_front_serial' => '',
            'left_front_no' => '',
            'right_behind_manu' => '',
            'right_behind_product' => '',
            'right_behind_size' => '',
            'right_behind_serial' => '',
            'right_behind_no' => '',
            'left_behind_manu' => '',
            'left_behind_product' => '',
            'left_behind_size' => '',
            'left_behind_serial' => '',
            'left_behind_no' => '',
            'other_a_manu' => '',
            'other_a_product' => '',
            'other_a_size' => '',
            'other_a_serial' => '',
            'other_a_no' => '',
            'other_b_manu' => '',
            'other_b_product' => '',
            'other_b_size' => '',
            'other_b_serial' => '',
            'other_b_no' => '',
            'D03_POS_DEN_NO' => '',
            'branch_code' => '',
            'branch_name' => '',
            'D01_SS_CD' => '',
            'ss_name' => '',
            'D03_DEN_NO' => '',
        ];
        return $result;
    }
}
