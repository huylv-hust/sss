<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace common\widgets;

/**
 * @author Dangbc <dang6591@>
 */
class Pdfbill extends \yii\bootstrap\Widget
{
    public $info_warranty;
    public $info_car;
    public $info_bill;
    public $info_ss;
    public function init()
    {

    }
    public function run()
    {
        $data = [
            'info_warranty' => $this->info_warranty,
            'info_car' => $this->info_car,
            'info_bill' => $this->info_bill,
            'info_ss' => $this->info_ss
        ];
        return $this->render('pdfbill', $data);
    }
}
