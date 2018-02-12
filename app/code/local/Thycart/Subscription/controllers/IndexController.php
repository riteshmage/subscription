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
    	if(empty($this->getRequest()->getParam('order_number')) || empty($this->getRequest()->getParam('subscription_id')))
    	{
    		 Mage::getSingleton('core/session')->addError('Order Id is wrong');
    		 $this->_redirect('subscription/index/subscription');
    		 return;
    	}
        $orderNumber = $this->getRequest()->getParam('order_number');
        $subscriptionId = $this->getRequest()->getParam('subscription_id');
        $order = Mage::getModel('sales/order')->load($orderNumber, 'increment_id');

        $subscriptionCancelled = $this->cancelSubscription($subscriptionId);
        if(strtolower($order->getStatusLabel()) === 'pending')
        {
            $orderCancelled = $this->cancelOrder($order);
        }
        if($subscriptionCancelled)
        {            
            Mage::getSingleton('core/session')->addSuccess('Subscription Cancelled Sucessfully');
        }
        else
        {
            Mage::getSingleton('core/session')->addError('Subscription cannot be canceled');
            exit;
        }
        $this->_redirect('subscription/index/subscription');
        return;
    }

    public function cancelSubscription($subscriptionId=0)
    {
        if(empty($subscriptionId))
        {
            return;
        }
        $subscriptionModel = Mage::getModel('subscription/subscriptioncustomer')->load($subscriptionId);
        $data = array('active'=>0);
        try
        {
            $subscriptionModel->addData($data);
            $subscriptionModel->save();
            return true;
        }
        catch(Exception $e)
        {
            throw new Exception(EXCEPTION_MSG);return;            
        }
    }

    public function cancelOrder($order)
    {
        if(empty($order))
        {
            return;
        }
        try
        {
            $order->cancel();
            $order->save();
            $order->sendOrderUpdateEmail();
            return true;
        }
        catch(Exception $e)
        {
            throw new Exception(EXCEPTION_MSG);return;            
        }
    }
}