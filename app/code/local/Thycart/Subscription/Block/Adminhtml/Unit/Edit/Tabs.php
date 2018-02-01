<?php
class Thycart_Subscription_Block_Adminhtml_Unit_Edit_Tabs extends  Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('unit_tab_id');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('subscription')->__('Unit'));
	}

	protected function  _beforeToHtml()
	{
		$this->addTab('unit_section', array(
			'label'     => Mage::helper('subscription')->__('Unit Information'),
			'title'     => Mage::helper('subscription')->__('unit Info'),
			'content'   => $this->getLayout()->createBlock('blk_subscription/adminhtml_unit_edit_tab_form')->toHtml(),
		));
		return parent::_beforeToHtml();
	}
}