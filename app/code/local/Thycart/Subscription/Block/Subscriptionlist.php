<?php

class Thycart_Subscription_Block_Subscriptionlist extends Mage_Core_Block_Template
{
	public function __construct()
	{
		parent::__construct();

		$this->subscriptionList();
	}

	public function subscriptionList()
	{
		if(Mage::getSingleton('customer/session')->isLoggedIn()) 
		{
			$customerData = Mage::getSingleton('customer/session')->getCustomer();
			$customerId = $customerData->getId();
			if($customerId)
			{
				try
				{
					$collection = array();
					$collection = Mage::getResourceModel('subscription/subscriptioncustomer_collection')->addFieldToFilter('customer_id',$customerId);
					return $this->setSubscription($collection);
				}
				catch(Exception $e)
				{
					throw new Exception("Unable to fetch your subscription list", 1);	
				}
			}
		}
	}
	
	protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager')
        	->setLimit(1)
            ->setCollection($this->getSubscription());            
        $this->setChild('pager', $pager);
        $pager->toHtml();

        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}