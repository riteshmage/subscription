<?php 
class Test_Message_Adminhtml_MessageController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
        {//isAllowed() is used to check if your controller(for specific method) is authorized for logged in user or not,As you can see, by default, this method returns true. That means if you don’t define your own _isAllowed method your Admin Panel features will be open to any user with an Admin Panel account, and people using your code will have no way to restrict access to your features
            return true;
        }
        protected function _initAction()
        {  
            //_setActiveMenu() will set active menu it will appear highlighted	    
          $this->loadLayout()->_setActiveMenu("message/message");
          return $this;
      }

      public function indexAction() 
      {
            //will show the message on browser tab when we hover over it
         $this->_title($this->__("Message details"));
         $this->_initAction();
         $this->renderLayout();
     }	

     public function editAction()
     { 
        //this function  gets called when edited action is perfornmed
        $this->_title($this->__("Edit Message"));
        $id     = $this->getRequest()->getParam('id');
        //it gets the id from the url
        $model  = Mage::getModel('message/message')->load($id);

        if ($model->getId() || $id == 0) {

            Mage::register('message_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('message/message');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Message Manager'), Mage::helper('adminhtml')->__('Messsage Manager'));

            $this->_addContent($this->getLayout()->createBlock('message/adminhtml_message_edit'))
            ->_addLeft($this->getLayout()->createBlock('message/adminhtml_message_edit_tabs'));

            $this->renderLayout();
        }
        else
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('message')->__('Message does not exist'));
            $this->_redirect('*/*/');
        }
    }		

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ( $this->getRequest()->getPost() ) {
            try {
                $postData = $this->getRequest()->getPost();
                $model = Mage::getModel('message/message');

                if($this->getRequest()->getParam('id'))
                {
                    $model->load($this->getRequest()->getParam('id'));
                }
                $model->addData($postData);
                $model ->save();  
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Message was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setMessageData(false);
                //====================================================
                //Mage::getSingleton("adminhtml/session")->setMessageData(false);
                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));
                    return;
                }
                $this->_redirect("*/*/");
                return;
                //===============================================

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setMessageData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }

        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('message/message');

                $model->setId($this->getRequest()->getParam('id'))
                ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Message was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
    public function massRemoveAction()
    {
        try {
            $ids = $this->getRequest()->getPost('ids', array());

            foreach ($ids as $id) {
              $model = Mage::getModel("message/message");

              $model->setId($id)->delete();
          }
          Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Message(s) was successfully removed"));
        }
        catch (Exception $e)
        {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}