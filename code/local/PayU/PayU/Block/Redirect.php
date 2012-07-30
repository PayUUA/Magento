<?php
/*
 *
 * @category   Community
 * @package    PayU_PayU
 * @copyright  http://payu.ua
 * @license    Open Software License (OSL 3.0)
 *
 */

/*
 * PayU payment module
 *
 * @author     PayU Ukraine
 *
 */

class PayU_PayU_Block_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        include_once "PayU.cls.php";
        $payu = Mage::getModel('PayU/PayU');
        $form = new Varien_Data_Form();
        $data = $payu->getFormFields();
        $state = $payu->getConfigData('order_status'); 
        
        $order = $payu->getQuote();
        $order->setStatus($state);
        $order->save();
        
        $pay = PayUForm::getInst()->setOptions( $data['settings'] )->setData( $data['fields'] )->LU();
        
    return $pay;
    }
}
