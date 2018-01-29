<?php
class Thycart_Subscription_Block_Adminhtml_Subscription_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Form
{
	 public function getProductChooserURL() {
        return 'getProductChooser(\'' . Mage::getUrl(
                        'adminhtml/promo_widget/chooser/attribute/sku/form/rule_conditions_fieldset', array('_secure' => Mage::app()->getStore()->isAdminUrlSecure())
                ) . '?isAjax=true\'); return false;';
    }

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('subscription_form', array('legend' => Mage::helper('subscription')->__('Item information')));

        // Add product SKU text preview
        $fieldset->addField('product_sku', 'text', array(
            'label' => Mage::helper('subscription')->__('Product(s)'),
            'name' => 'product_sku',
            'required' => true,
            'class' => 'rule_conditions_fieldset',
            'readonly' => true,
            'onclick' => $this->getProductChooserURL(),           
        ));
        $fieldset->addField('trigger', 'button', array(
            'value' => Mage::helper('subscription')->__('choose'),
            'name' => 'trigger',
            'style' => 'width:100px;',
            'onclick' => $this->getProductChooserURL(),
        ));

         $fieldset->addFieldset('product_chooser', array('legend' => ('')));

        if (Mage::getSingleton('adminhtml/session')->getProductsselectorData()) {
            $form->addValues(Mage::getSingleton('adminhtml/session')->getProductsselectorData());
            Mage::getSingleton('adminhtml/session')->setProductsselectorData(null);
        } 
        elseif (Mage::registry('subscription_data')) 
        {
            $form->addValues(Mage::registry('subscription_data')->getData());
        }
        return parent::_prepareForm();
    }

}
