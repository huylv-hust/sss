<?php
namespace console\controllers;

class DebugController extends \yii\console\Controller
{
    public function actionIndex($denNo)
    {
        $pdf = new \mPDF('ja', 'A4', 0, 'DejaVuSansCondensed', '4', '4', '5', '5', '4', '4');
        $html = file_get_contents(\Yii::$app->getBasePath() . '/../backend/web/data/pdf/' . $denNo . '.html');
        $pdf->WriteHTML(preg_replace('/@page[^}]+}/s', '', $html));
        $pdf->Output($denNo . '.pdf', 'F');
    }
}
