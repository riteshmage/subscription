<?php
class Thycart_Subscription_Block_Subscription extends Mage_Core_Block_Template
{
	public function subscrptionStatus($productId)
	{
		$subscrptionStatus = Mage::getModel('subscription/master')->getCollection()
								->addFieldToSelect('active')
								->addFieldToFilter('product_id',array('finset'=> $productId))
								->getFirstItem()->getActive();
		return $subscrptionStatus;
	}
	public function subscriptionDetail()
	{
		return array('monthly'=>'100');
	}

}