<?php
class Thycart_Subscription_IndexController extends Mage_Core_Controller_Front_Action
{
	public function subscriptionAction()
	{
		if(!Mage::getSingleton('customer/session')->isLoggedIn()) 
		{
			$this->_redirect('/');
			return;
		}
		$this->loadLayout();
		$this->renderLayout();
	}
	public function cancelAction()
    {
    	if(empty($this->getRequest()->getParam('increment_id')))
    	{
    		 Mage::getSingleton('core/session')->addError('Order Id is wrong');
    		 $this->_redirect('subscription/index/subscription');
    		 return;
    	}
        $incrementId = $this->getRequest()->getParam('increment_id');  		
        try
        {	
        	$order = Mage::getModel('sales/order')->load($incrementId, 'increment_id');
            $order->cancel();
            $order->save();
            $order->sendOrderUpdateEmail();
            Mage::getSingleton('core/session')->addSuccess('Order Cancelled Sucessfully');
        }
        catch(Exception $e)
        {
            Mage::logException($e);
            Mage::getSingleton('core/session')->addError('Order cannot be canceled.');
            exit;
        }
        $this->_redirect('subscription/index/subscription');
        return;
        $order->getStatusLabel();
    }
}