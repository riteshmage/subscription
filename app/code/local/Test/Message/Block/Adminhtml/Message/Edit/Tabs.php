<?php
  
class Test_Message_Block_Adminhtml_Message_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
  
    public function __construct()
    {
        parent::__construct();
        $this->setId('message_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('message')->__('Message'));
    }
  
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('message')->__('Message Information'),
            'title'     => Mage::helper('message')->__('Message Info'),
            'content'   => $this->getLayout()->createBlock('message/adminhtml_message_edit_tab_form')->toHtml(),
        ));
        
        return parent::_beforeToHtml();
    }
}