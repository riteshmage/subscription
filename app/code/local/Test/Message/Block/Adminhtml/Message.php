<?php 

class Test_Message_Block_Adminhtml_Message extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
     //indicate where we can find the controller
        $this->_controller = 'adminhtml_message';
        $this->_blockGroup = 'message';
        //header text its been added on left side of window
        $this->_headerText = Mage::helper("message")->__("Message Manager");
        //label on  button for adding neew message
        $this->_addButtonLabel = Mage::helper("message")->__("Add Message");
        parent::__construct();
    }
}