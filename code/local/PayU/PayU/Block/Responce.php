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

class PayU_PayU_Block_Responce extends Mage_Core_Block_Abstract {
    
    protected function _toHtml()
    {

    	
       include_once "PayU.cls.php";
       $payu = Mage::getModel('PayU/PayU');
       $state = $payu->getConfigData('after_pay_status'); #$state = 'complete';
       $payansewer = PayUForm::getInst()->setOptions( $payu->getSettings() )->IPN();

        #echo $payansewer;
       echo $state;
      # echo $_POST['REFNOEXT'];
       $order = Mage::getModel('sales/order')->loadByIncrementId($_POST['REFNOEXT']); #load( $_POST['REFNOEXT'] );
    #   $order = $payu->getQuote();
        $order->setStatus($state);
       # $order->setState($state);
        #$order->complete()->save();
        $order->save();
       # echo var_dump( $order );
       	
    return $payansewer;
    }
}