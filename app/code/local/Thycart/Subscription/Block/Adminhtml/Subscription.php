<?php
class Thycart_Subscription_Block_Adminhtml_Subscription extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_subscription';
		$this->_blockGroup = 'blk_subscription';
		$this->_headerText = Mage::helper('subscription')->__('Subscription Manager');
		$this->_addButtonLabel = Mage::helper('subscription')->__('Add new rule');
		parent::__construct();
	}
}