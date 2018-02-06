<?php
class Thycart_Subscription_Block_Adminhtml_Subscription_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('subscription_form', array('legend'=>Mage::helper('subscription')->__('Subscription')));

		$fieldset->addField('subscription_name', 'text', array(
			'label'     => Mage::helper('subscription')->__('Rule name'),
			'name'      => 'subscription_name',
			'required'  => true,
			'class' 	=> 'validate-code validate-no-html-tags required-entry',
			'after_element_html' => '<small>Add (productName)_(weekly/monthly/yearly)</small>'
		));
		$fieldset->addField('max_billing_cycle', 'text', array(
			'label'     => Mage::helper('subscription')->__('Max billing cycles allowed'),
			'name'      => 'max_billing_cycle',
			'class'		=> 'validate-digits-range digits-range-10-20 validate-no-html-tags',
			'value'		=>	2,
			'after_element_html' => '<small>Number of cyles allowed to subscription</small>'
		));
		// $fieldset->addField('show_start_date', 'radios', array(
		// 	'label'     => Mage::helper('subscription')->__('Allow user to select start date'),
		// 	'name'      => 'show_start_date',
		// 	'values' => array(
		// 		array('value'=>'0','label'=>'yes'),
		// 		array('value'=>'1','label'=>'no')
		// 	),
		// ));
		$fieldset->addField('discount_type', 'select', array(
			'label'     => 	Mage::helper('subscription')->__('Discount Type'),
			'name'      => 	'discount_type',
			'required'  => 	true,
			'class' 	=> 	'validate-select required-entry',
			'values' 	=> 	array(''=>'Please Select..','1' => 'Fixed','2' => 'percentage'),
			'after_element_html' => '<small>Select discount type (fixed or %)</small>'
		));
		$fieldset->addField('discount_value', 'text', array(
			'label'     => 	Mage::helper('subscription')->__('Discount value'),
			'name'      => 	'discount_value',
			'class'		=> 	'validate-digits-range digits-range-0-100 validate-no-html-tags',
			'after_element_html' => '<small>Enter Discount Amount or %</small>'
		));	
		$fieldset->addField('active', 'select', array(
			'label'     => 	Mage::helper('subscription')->__('Active'),
			'name'      => 	'active',
			'required'  => 	true,
			'class' 	=> 	'validate-select required-entry',
			'values' 	=> 	array(''=>'Please select','1'=>'Yes','0'=>'No'),
			'after_element_html' => '<small>please select to show on frontend </small>'
		));
		$fieldset->addField('unit', 'multiselect', array(
			'label'     => 	Mage::helper('subscription')->__('Unit'),
			'name'      => 	'unit',
			'required'  => 	true,
			'class' 	=> 	'validate-select required-entry',
			'values'	=>	array(Mage::helper('subscription')->getUnitMaster()),
			'after_element_html' => '<small>select subscription type</small>',
		));
		if (Mage::getSingleton('adminhtml/session')->getSubscriptionData())
		{
			$form->addValues(Mage::getSingleton('adminhtml/session')->getSubscriptionData());
			Mage::getSingleton('adminhtml/session')->setSubscriptionData(null);			
		} 
		elseif(Mage::registry('subscription_data'))
		{
			$form->addValues(Mage::registry('subscription_data')->getData());
		}
		return parent::_prepareForm();
	}
}