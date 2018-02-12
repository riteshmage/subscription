<?php
class Thycart_Subscription_Block_Adminhtml_Unit_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{       
		$this->_objectId = 'id';
		$this->_blockGroup = 'blk_subscription';
		$this->_controller = 'adminhtml_unit';
		parent::__construct();
		$this->_removeButton('delete');
		$this->_updateButton('save', 'label', Mage::helper('subscription')->__('Save Unit'));
		$this->_addButton('save_and_continue_edit', array(
			"label"     => Mage::helper('subscription')->__('Save And Continue Edit'),
			"onclick"   => 'saveAndContinueEdit()',
			"class"     => 'save',
		), -100);
		$this->_formScripts[] = 
		"function saveAndContinueEdit()
		{
			editForm.submit($('edit_form').action+'back/edit/');
		}
		";
	}
	public function getHeaderText()
	{
		if(Mage::registry('unit_data') && Mage::registry('unit_data')->getId())
			{
				return Mage::helper('subscription')->__("Edit unit %s", $this->htmlEscape(Mage::registry('unit_data')->getId()));
			} 
			else
			{
				return Mage::helper('subscription')->__('Add Unit');
			}
		} 	
	}