<?php
class Thycart_Subscription_Block_Adminhtml_Customer extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_customer';
		$this->_blockGroup = 'blk_subscription';
		$this->_headerText = Mage::helper('subscription')->__('Subscribed Customer Manager');
		parent::__construct();
		$this->_removeButton('add');
	}
}