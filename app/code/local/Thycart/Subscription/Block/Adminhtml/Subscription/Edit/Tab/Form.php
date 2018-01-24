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
			'class' => 'required-entry',
			'after_element_html' => '<small>Add (productName)_(weekly/monthly/yearly)</small>'
		));

		//product id
//==============================================================================================
		$productConfig = array(
			'input_name'  => 'sku',
			'input_label' => $this->__('Product'),
			'button_text' => $this->__('Select...'),
			'required'    => true,
			'input_id'    => 'sku'
		);
		$productChooserHelper = Mage::helper('multiproductchooser/chooser');
		$productChooserHelper->createProductChooser(Mage::getModel('catalog/product'), $fieldset, $productConfig);
//=============================================================================================


		$fieldset->addField('max_billing_cycle', 'text', array(
			'label'     => Mage::helper('subscription')->__('Max billing cycles allowed'),
			'name'      => 'max_billing_cycle',
			'required'  => true,
			'class' => 'required-entry',
			'after_element_html' => '<small>Number of cyles allowed to subscription</small>'
		));
		$fieldset->addField('show_start_date', 'radios', array(
			'label'     => Mage::helper('subscription')->__('Allow user to select start date'),
			'name'      => 'show_start_date',
			'values' => array(
				array('value'=>'0','label'=>'yes'),
				array('value'=>'1','label'=>'no')
			),
		));
		$fieldset->addField('discount_type', 'select', array(
			'label'     => Mage::helper('subscription')->__('Discount Type'),
			'name'      => 'discount_type',
			'values' => array('-1'=>'Please Select..','fixed' => 'Fixed','%' => 'percentage'),
			'after_element_html' => '<small>Select discount type (fixed or %)</small>'
		));
		$fieldset->addField('discount_value', 'text', array(
			'label'     => Mage::helper('subscription')->__('Discount value'),
			'name'      => 'discount_value',
			'after_element_html' => '<small>Enter Discount Amount or %</small>'
		));	
		$fieldset->addField('active', 'radios', array(
			'label'     => Mage::helper('subscription')->__('Active'),
			'name'      => 'is_active',
			'values' => array(
				array('value'=>'0','label'=>'yes'),
				array('value'=>'1','label'=>'no')
			),
		));
		if(Mage::getSingleton('adminhtml/session')->getSubscriptionData())
			{
				$form->setValues(Mage::getSingleton('adminhtml/session')->getSubscriptionData());
				Mage::getSingleton('adminhtml/session')->setSubscriptionData(null);
			} 
			elseif(Mage::registry('subscription_data'))
				{
					$form->setValues(Mage::registry('subscription_data')->getData());
				}
				return parent::_prepareForm();
			}
		}