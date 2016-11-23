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
class Cardmember extends \yii\bootstrap\Widget
{
    public $url;
    public function init()
    {

    }
    public function run()
    {
        return $this->render('cardmember');
    }
}
