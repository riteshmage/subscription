<?php
class Thycart_Subscription_Block_Adminhtml_Subscription_Edit_Tabs extends  Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('subscription_tab_id');
        $this->setDestElementId('sub_edit_form');
        $this->setTitle(Mage::helper('subscription')->__('subscription'));
	}

	protected function  _beforeToHtml()
	{
		$this->addTab('subscription_section', array(
            'label'     => Mage::helper('subscription')->__('subscription Information'),
            'title'     => Mage::helper('subscription')->__('subscription Info'),
            'content'   => $this->getLayout()->createBlock('blk_subscription/adminhtml_subscription_edit_tab_form')->toHtml(),
        ));
        return parent::_beforeToHtml();
	}

}