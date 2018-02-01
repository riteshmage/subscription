<?php
class Thycart_Subscription_Block_Adminhtml_Unit extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_unit';
		$this->_blockGroup = 'blk_subscription';
		$this->_headerText = Mage::helper('subscription')->__('Unit Manager');
		$this->_addButtonLabel = Mage::helper('subscription')->__('Add New Unit');
		parent::__construct();
	}
}