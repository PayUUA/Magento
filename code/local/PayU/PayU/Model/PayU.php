<?php

class PayU_PayU_Model_PayU extends Mage_Payment_Model_Method_Abstract 
    {

    protected $_code = 'PayU';
    protected $_formBlockType = 'PayU/form';

    public function getCheckout() {
        return Mage::getSingleton('checkout/session');
    }

    public function getOrderPlaceRedirectUrl() {
        return Mage::getUrl('PayU/redirect', array('_secure' => true));
    }

    public function getWebmoneyUrl() {
        $url = 'https://secure.payu.ua/order/lu.php';
        return $url;
    }

    public function getQuote() {

        $orderIncrementId = $this->getCheckout()->getLastRealOrderId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);                   
        return $order;
    }

    public function getFormFields() {


        $order_id = $this->getCheckout()->getLastRealOrderId();
        $order    = Mage::getModel('sales/order')->loadByIncrementId($order_id);
        $amount   = trim(round($order->getGrandTotal(), 2));

        $items = $this->getQuote()->getAllItems();

        if ( $this->getConfigData('LU_url') !== "" ) $settings['luUrl'] = $this->getConfigData('LU_url');

       foreach($items as $item) {
            $pcode[] = $item->getProductId();
            $pname[] = $item->getName();
            $qty[] = $item->getQtyToShip();
            $vat[] = $this->getConfigData('vat');
            $price[] = $item->getPrice();
            $pinfo[] = "";
        }
    $fields = array();

    if ( $order->getBaseShippingAmount() )
    {
    $shipping = $order->getBaseShippingAmount();
    $a = $this->getQuote()->getBillingAddress();
    $b = $this->getQuote()->getShippingAddress();

      $fields += array( 
                    'BILL_FNAME' => $b->getFirstname(),
                    'BILL_LNAME' => $b->getLastname(),
                    'BILL_CITY' => $b->getCity() ,
                    'BILL_COUNTYCODE' => $b->getCountry(),
                    'BILL_ADRESS' => $b->getStreet(1),
                    'BILL_EMAIL' => $a->getEmail(),
                    'BILL_PHONE' => $a->getTelephone(),
                    'BILL_ZIPCODE' => $a->getPostcode(),
                    
                    );


    } else $shipping = 0;



        $fields += array(
          'ORDER_REF' => $order_id,
          'ORDER_PNAME' => $pname,
          'ORDER_PCODE' => $pcode,
          'ORDER_PINFO' => $pinfo,
          'ORDER_PRICE' => $price,
          'ORDER_QTY' => $qty,
          'ORDER_VAT' => $vat,
          'ORDER_SHIPPING' => $shipping,
          'PRICES_CURRENCY' => $this->getConfigData('currency') ,
          'LANGUAGE' => $this->getConfigData('language')
        );

        if ( $this->getConfigData('back_ref') !== "" ) $fields['BACK_REF'] = $this->getConfigData('back_ref');
        
        $params = array(
            'settings' =>  $this->getSettings(),
            'fields' => $fields,
          );
        return $params;
        }

        function getSettings()
        {
          $button = "<div style='position:absolute; top:50%; left:50%; margin:-40px 0px 0px -60px; '>".
                  #"<div><img src='/published/SC/html/img/logo-payu.png' width='120px' style='margin:0px 5px;'></div>".
                  "<div><img src='http://www.payu.ua/sites/default/files/logo-payu.png' width='120px' style='margin:5px 5px;'></div>".
                  "</div>".
                  "<script>
                  setTimeout( subform, 2000 );
                  function subform(){ document.getElementById('PayUForm').submit(); }
                  </script>";

          $settings = array( 
                          'merchant' => $this->getConfigData('merchant'), 
                          'secretkey' => $this->getConfigData('secret_key'), 
                          'debug' => $this->getConfigData('debug_mode'),
                          'button' => $button
                );
          return $settings;
      }
}
?>