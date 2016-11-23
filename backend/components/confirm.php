<?php
namespace backend\components;

class confirm
{
    public static function writeconfirm($post = [])
    {

        $confirm = confirm::readconfirm(['D03_DEN_NO' => $post['D03_DEN_NO']]);
        if ($confirm['status'] == 1 && file_exists(getcwd() . '/data/pdf/' . $post['D03_DEN_NO'] . '.pdf')) {
            $post['status'] = 1;
        }

        if (isset($post['pressure_front']) == false) {
            $post['pressure_front'] = '';
            if ($post['pressure_front_1'] && $post['pressure_front_2']) {
                $post['pressure_front'] = $post['pressure_front_1'] . '.' . $post['pressure_front_2'];
            } elseif ($post['pressure_front_1'] && !$post['pressure_front_2']) {
                $post['pressure_front'] = $post['pressure_front_1'].'.0';
            } elseif (!$post['pressure_front_1'] && $post['pressure_front_2']) {
                $post['pressure_front'] = '0' . '.' . $post['pressure_front_2'];
            }
        }

        if (isset($post['pressure_behind']) == false) {
            $post['pressure_behind'] = '';
            if ($post['pressure_behind_1'] && $post['pressure_behind_2']) {
                $post['pressure_behind'] = $post['pressure_behind_1'] . '.' . $post['pressure_behind_2'];
            } elseif ($post['pressure_behind_1'] && !$post['pressure_behind_2']) {
                $post['pressure_behind'] = $post['pressure_behind_1'].'.0';
            } elseif (!$post['pressure_behind_1'] && $post['pressure_behind_2']) {
                $post['pressure_behind'] = '0' . '.' . $post['pressure_behind_2'];
            }
        }

        $data[0] = [
            'タイヤ交換図1',
            'タイヤ交換図2',
            'タイヤ交換図3',
            'タイヤ交換図4',
            '空気圧_前',
            '空気圧_後',
            'リムバルブ',
            'トルクレンチ',
            'ホイルキャップ',
            '持帰ナット',
            'オイル量',
            'オイルキャップ',
            'レベルゲージ',
            'ドレンボルト',
            'パッキン',
            'オイル漏れ',
            '次回交換目安_date',
            '次回交換目安_km',
            'ターミナル締付',
            'ステー取付',
            'バックアップ',
            'スタートアップ',
            'status',
            '右前',
            '左前',
            '右後',
            '左後',
            'オイル量',
            'オイル漏れ',
            'キャップゲージ',
            'ドレンボルト',
            'タイヤ損傷・磨耗',
            'ボルト・ナット',
            '備考',
        ];

        $data[1] = [
            'tire_1' => '',
            'tire_2' => '',
            'tire_3' => '',
            'tire_4' => '',
            'pressure_front' => $post['pressure_front'],
            'pressure_behind' => $post['pressure_behind'],
            'rim' => isset($post['rim']) && $post['rim'] ? 1 : 0,
            'torque' => isset($post['torque']) && $post['torque'] ? 1 : 0,
            'foil' => isset($post['foil']) && $post['foil'] ? 1 : 0,
            'nut' => isset($post['nut']) && $post['nut'] ? 1 : 0,
            'oil' => isset($post['oil']) && $post['oil'] ? 1 : 0,
            'oil_cap' => isset($post['oil_cap']) && $post['oil_cap'] ? 1 : 0,
            'level' => isset($post['level']) && $post['level'] ? 1 : 0,
            'drain_bolt' => isset($post['drain_bolt']) && $post['drain_bolt'] ? 1 : 0,
            'packing' => isset($post['packing']) && $post['packing'] ? 1 : 0,
            'oil_leak' => isset($post['oil_leak']) && $post['oil_leak'] ? 1 : 0,
            'date' => isset($post['date']) ? $post['date'] : '',
            'km' => isset($post['km']) && $post['km'] ? $post['km'] : '',
            'terminal' => isset($post['terminal']) && $post['terminal'] ? 1 : 0,
            'stay' => isset($post['stay']) && $post['stay'] ? 1 : 0,
            'backup' => isset($post['backup']) && $post['backup'] ? 1 : 0,
            'startup' => isset($post['startup']) && $post['startup'] ? 1 : 0,
            'status' => isset($post['status']) && $post['status'] ? $post['status'] : 0,
            'front_right' => isset($post['front_right']) && $post['front_right'] ? $post['front_right'] : '',
            'front_left' => isset($post['front_left']) && $post['front_left'] ? $post['front_left'] : '',
            'behind_right' => isset($post['behind_right']) && $post['behind_right'] ? $post['behind_right'] : '',
            'behind_left' => isset($post['behind_left']) && $post['behind_left'] ? $post['behind_left'] : '',
            'oil_check' => isset($post['oil_check']) ? $post['oil_check'] : '',
            'oil_leak_check' => isset($post['oil_leak_check']) ? $post['oil_leak_check'] : '',
            'cap_check' => isset($post['cap_check']) ? $post['cap_check'] : '',
            'drain_bolt_check' => isset($post['drain_bolt_check']) ? $post['drain_bolt_check'] : '',
            'tire_check' => isset($post['tire_check']) ? $post['tire_check'] : '',
            'nut_check' => isset($post['nut_check']) ? $post['nut_check'] : '',
            'note' => isset($post['note']) ? $post['note'] : '',
        ];
        utilities::createFolder('data/confirm/');
        $fp = fopen(getcwd() . '/data/confirm/' . $post['D03_DEN_NO'] . '.csv', 'w+');
        fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        foreach ($data as $key => $value) {
            fputcsv($fp, $value);
        }
        fclose($fp);
    }

    public static function readconfirm($post = [])
    {
        if (file_exists(getcwd() . '/data/confirm/' . $post['D03_DEN_NO'] . '.csv')) {
            $data = file_get_contents(getcwd() . '/data/confirm/' . $post['D03_DEN_NO'] . '.csv');

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
                'tire_1' => '',
                'tire_2' => '',
                'tire_3' => '',
                'tire_4' => '',
                'pressure_front' => $data['4'],
                'pressure_behind' => $data['5'],
                'rim' => $data['6'],
                'torque' => $data['7'],
                'foil' => $data['8'],
                'nut' => $data['9'],
                'oil' => $data['10'],
                'oil_cap' => $data['11'],
                'level' => $data['12'],
                'drain_bolt' => $data['13'],
                'packing' => $data['14'],
                'oil_leak' => $data['15'],
                'date' => $data['16'],
                'km' => $data['17'],
                'terminal' => $data['18'],
                'stay' => $data['19'],
                'backup' => $data['20'],
                'startup' => $data['21'],
                'status' => $data['22'],
                'front_right' => isset($data['23']) ? $data['23'] : '',
                'front_left' => isset($data['24']) ? $data['24'] : '',
                'behind_right' => isset($data['25']) ? $data['25'] : '',
                'behind_left' => isset($data['26']) ? $data['26'] : '',
                'oil_check' => isset($data['27']) ? $data['27'] : '',
                'oil_leak_check' => isset($data['28']) ? $data['28'] : '',
                'cap_check' => isset($data['29']) ? $data['29'] : '',
                'drain_bolt_check' => isset($data['30']) ? $data['30'] : '',
                'tire_check' => isset($data['31']) ? $data['31'] : '',
                'nut_check' => isset($data['32']) ? $data['32'] : '',
                'note' => isset($data['33']) ? $data['33'] : '',
            ];
            return $result;
        }

        return [
            'tire_1' => '',
            'tire_2' => '',
            'tire_3' => '',
            'tire_4' => '',
            'pressure_front' => '',
            'pressure_behind' => '',
            'rim' => '',
            'torque' => '',
            'foil' => '',
            'nut' => '',
            'oil' => '',
            'oil_cap' => '',
            'level' => '',
            'drain_bolt' => '',
            'packing' => '',
            'oil_leak' => '',
            'date' => '',
            'km' => '',
            'terminal' => ' ',
            'stay' => '',
            'backup' => '',
            'startup' => '',
            'status' => '',
            'front_right' => '',
            'front_left' => '',
            'behind_right' => '',
            'behind_left' => '',
            'oil_check' => '',
            'oil_leak_check' => '',
            'cap_check' => '',
            'drain_bolt_check' => '',
            'tire_check' => '',
            'nut_check' => '',
            'note' => '',
        ];
    }
}
