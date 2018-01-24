<?php

class Test_Message_Block_Adminhtml_Message_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {       
        parent::__construct();
        $this->_blockGroup = "message";
        $this->_controller = "adminhtml_message";
            //for save button
        $this->_updateButton("save", "label", Mage::helper("message")->__("Save Message"));
            //for delete button
        $this->_updateButton("delete", "label", "Delete Message");
            //for save and continue button
        $this->_addButton("saveandcontinue", array(
            "label"     => Mage::helper("message")->__("Save And Continue Edit"),
            "onclick"   => "saveAndContinueEdit()",
            "class"     => "save",
        ), -100);
            //script for save and continue required so it edits and come back on same form
        $this->_formScripts[] = 
        "function saveAndContinueEdit(){
            editForm.submit($('edit_form').action+'back/edit/');
        }
        ";
    }

    public function getHeaderText()
        {   //check from register by using registry and get the id from registry
            if( Mage::registry("message_data") && Mage::registry("message_data")->getId() )
                {
                    return Mage::helper("message")->__("Edit Mesasge %s", $this->htmlEscape(Mage::registry("message_data")->getId()));
                } 
                else
                {
                 return Mage::helper("message")->__("Add Message");
                 //this text will appear instead of edit message
             }
         }
     }