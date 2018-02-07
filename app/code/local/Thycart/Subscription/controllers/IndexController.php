<?php
class Thycart_Subscription_IndexController extends Mage_Core_Controller_Front_Action
{
	protected function subscriptionAction()
	{
		if(!Mage::getSingleton('customer/session')->isLoggedIn()) 
		{
			$this->_redirect('/');
			return;
		}
		$this->loadLayout();
		$this->renderLayout();
	}
}