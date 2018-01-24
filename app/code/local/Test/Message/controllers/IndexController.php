<?php
class Test_Message_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $test = 
        "<script> alert('sdfsdfs'); </script>";
        //echo $this->htmlEscape($test);
        echo Mage::helper('core')->escapeHtml("$test");


    }
    public function testAction()
    {
        $this->loadLayout();
        //header('Content-Type: text-xml');
        //die($this->getLayout()->getNode()->asXml());
        $this->renderLayout();
        // Zend_Debug::dump($this->getLayout()->getUpdate()->getHandles());
    }
    public function saveAction()
    {
        if(!$this->getRequest()->isPost())
        { 
            Mage::getSingleton('core/session')->addError('Data not posted');
            $this->_redirect('message/index/test'); 
        }
        $name = $this->getRequest()->getPost('message_name');
        $description = $this->getRequest()->getParam('message_description');
        $data = array('message_name' => $name,'message_description' => $description);
        if($name == '')
        {
            echo "Name is empty";
        } 
        elseif($name!='' && $description!='')
        {
            $contact = Mage::getModel('message/message');
            $contact->addData($data);
            $contact->save();
            Mage::getSingleton('core/session')->addSuccess('Entered Sucessfully');
            $this->_redirect('message/index/test');
        }
        else
        {
            Mage::getSingleton('core/session')->addError('Data not entered');
            $this->_redirect('message/index/test');
        }
    }  
}