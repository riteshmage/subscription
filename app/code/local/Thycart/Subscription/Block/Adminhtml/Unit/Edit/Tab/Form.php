<?php
class Thycart_Subscription_Block_Adminhtml_Unit_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('unit_form', array('legend'=>Mage::helper('subscription')->__('Unit')));

		$fieldset->addField('subscription_unit', 'text', array(
			'label'     => Mage::helper('subscription')->__('Unit name'),
			'name'      => 'subscription_unit',
			'required'  => true,
			'class' 	=> 'validate-code validate-no-html-tags required-entry',
			'after_element_html' => '<small>Add Name</small>'
		));
		$fieldset->addField('active', 'select', array(
			'label'     => Mage::helper('subscription')->__('Active'),
			'name'      => 'active',
			'required'  => true,
			'class'     => 'validate-select required-entry',
			'values' => array(''=>'Please select','1'=>'Yes','0'=>'No'),
			'after_element_html' => '<small>please select to activate subscription unit</small>'
		));
		
		if (Mage::getSingleton('adminhtml/session')->getSubscriptionData())
		{
			$form->addValues(Mage::getSingleton('adminhtml/session')->getSubscriptionData());
			Mage::getSingleton('adminhtml/session')->setSubscriptionData(null);			
		} 
		elseif(Mage::registry('unit_data'))
		{
			$form->addValues(Mage::registry('unit_data')->getData());
		}
		return parent::_prepareForm();
	}
}