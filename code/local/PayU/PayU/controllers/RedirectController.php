<?php
class PayU_PayU_RedirectController extends Mage_Core_Controller_Front_Action {

    protected $_order;

    protected function _expireAjax() {
        if (!Mage::getSingleton('PayU/session')->getQuote()->hasItems()) {
            $this->getResponse()->setHeader('HTTP/1.1','403 Session Expired');
            exit;
        }
    }

    public function indexAction() {
        $this->getResponse()
                ->setHeader('Content-type', 'text/html; charset=utf8')
                ->setBody($this->getLayout()
                ->createBlock('PayU/redirect')
                ->toHtml());
    }

    public function successAction() {
        if($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $session = Mage::getSingleton('PayU/session');
            Mage::getSingleton('ceckout/session')->getQuote()->setIsActive(false)->save();
            $this->_redirect('ceckout/onepage/success', array('_secure'=>true));
        }
        
    }

}

?>
