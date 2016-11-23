<?php
namespace backend\modules\pdf\controllers;

use backend\components\utilities;
use common\widgets\Pdfbill;
use Yii;
use yii\base\Controller;

class PdfController
{
    /**
     * @param array $info_warranty
     * @param array $info_car
     * @param array $info_bill
     * @param array $info_ss
     * @param $denpyoNo
     * @param null $savetype
     * @param null $watermark
     * @return bool|string
     * @throws \Exception
     */
    public function exportBill($info_warranty = [], $info_car = [], $info_bill = [], $info_ss = [], $denpyoNo = null, $savetype = null, $watermark = null)
    {
        $data = [
            'info_warranty' => $info_warranty,
            'info_car' => $info_car,
            'info_bill' => $info_bill,
            'info_ss' => $info_ss
        ];

        $stringTarget = Pdfbill::widget($data);

        $pdf = new \mPDF('ja', 'A4', 0, 'DejaVuSansCondensed', '4', '4', '5', '5', '4', '4');
        $pdf->WriteHTML(preg_replace('/@page[^}]+}/s', '', $stringTarget));
        if ($watermark) {
            $pdf->SetWatermarkImage('../web/img/confidentiality.png', 0.6);
            $pdf->showWatermarkImage = true;
        }
        utilities::createFolder('data/pdf');//Create folder data/pdf
        if ($savetype == 'save') {
            if (file_exists('data/pdf/' . $denpyoNo . '.pdf')) {
                return false;
            }

            // make HTML
            file_put_contents('data/pdf/' . $denpyoNo . '.html', $stringTarget);
            $pdf->Output('data/pdf/' . $denpyoNo . '.pdf', 'F');

            @chmod('data/pdf/' . $denpyoNo . '.html', 0666);
            @chmod('data/pdf/' . $denpyoNo . '.pdf', 0666);

            if (file_exists('data/pdf/' . $denpyoNo . '.pdf')) {
                return true;
            }
        } else {
            utilities::createFolder('data/tmp');
            $name = 'draft-' . md5(uniqid(mt_rand(), true)) . '.pdf';
            $filename = "data/tmp/$name";
            $pdf->Output($filename, 'F');
            return $filename;
        }
    }

    public function checkExists($denpyoNo)
    {
        return file_exists('data/pdf/' . $denpyoNo . '.pdf');
    }
}
