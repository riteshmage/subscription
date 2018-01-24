<?php
class Thycart_Subscription_Block_Adminhtml_Subscription_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{       
		$this->_objectId = 'id';
		$this->_blockGroup = 'blk_subscription';
		$this->_controller = 'adminhtml_subscription';
		parent::__construct();

		$this->_updateButton('save', 'label', Mage::helper('subscription')->__('Save Rule'));
		$this->_updateButton('save','onclick','sub_edit_form.submit()');
		$this->_addButton('save_and_continue_edit', array(
			"label"     => Mage::helper('subscription')->__("Save And Continue Edit"),
			"onclick"   => 'saveAndContinueEdit()',
			"class"     => 'save',
		), -100);
		$this->_formScripts[] = 
		"function saveAndContinueEdit()
		{
			sub_edit_form.action = sub_edit_form.action +'back/edit/';
			sub_edit_form.submit();
		}";
	}
	public function getHeaderText()
	{
		if(Mage::registry('subscription_data') && Mage::registry('subscription_data')->getId())
		{
			return Mage::helper('subscription')->__("Edit Rule %s", $this->htmlEscape(Mage::registry('subscription_data')->getId()));
		} 
		else
		{
			return Mage::helper('subscription')->__('Add rule');
		}
	} 	
}