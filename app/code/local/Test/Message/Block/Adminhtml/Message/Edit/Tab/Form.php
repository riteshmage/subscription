<?php
  
class Test_Message_Block_Adminhtml_Message_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('message_form', array('legend'=>Mage::helper('message')->__('Message')));

        $fieldset->addField('message_name', 'text', array(
                'label'     => Mage::helper('message')->__('Name'),
                'name'      => 'message_name',
                'required'  => true,
                'class' => 'required-entry',
            ));
  
        
         $fieldset->addField('message_description', 'textarea', array(
                'label'     => Mage::helper('message')->__('Message Description'),
                'name'      => 'message_description',
                'required'  => true,
                'class' => 'required-entry',
            ));
        if ( Mage::getSingleton('adminhtml/session')->getMessageData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getMessageData());
            Mage::getSingleton('adminhtml/session')->setMessageData(null);
        } 
        elseif ( Mage::registry('message_data') ) {
            $form->setValues(Mage::registry('message_data')->getData());
        }


        return parent::_prepareForm();
    }
} 
