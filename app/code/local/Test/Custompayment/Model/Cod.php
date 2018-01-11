<?php
class Test_Custompayment_Model_Cod extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'custompayment';
 
	protected $_isInitializeNeeded      = true;		
	protected $_canUseInternal          = false;
	protected $_canUseForMultishipping  = false;
 
	public function getOrderPlaceRedirectUrl()
	{
		echo "string";die;
		//return Mage::getUrl('customcard/standard/redirect', array('_secure' => true));
	}
}