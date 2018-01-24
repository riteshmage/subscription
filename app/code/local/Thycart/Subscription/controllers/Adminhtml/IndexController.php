<?php
class Thycart_Subscription_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed()
	{
		return true;
	}

	protected function _initAction()
	{
		$this->loadLayout()->_setActiveMenu('promo');
		return $this;
	}

	public function indexAction()
	{
		$this->_title('Subscription Rules');
		$this->_initAction();
		$this->renderLayout();
	}

	public function editAction()
	{
		$model = new stdClass();
		$id = 0;
		$this->_title($this->__("Subscription Rule"));
		if($this->getRequest()->getParam('id'))
		{
			$id = $this->getRequest()->getParam('id');
			try
			{
				$model  = Mage::getModel('subscription/master')->load($id);	
			}
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setMessageData($this->getRequest()->getPost());
				$this->_redirect('*/*/edit', array('id' => $id));
				return;
			}
		}
		if (!empty($model) || empty($id))
		{
			Mage::register('subscription_data', $model);
			$this->loadLayout();
			$this->_setActiveMenu('promo');
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('subscription Manager'), Mage::helper('adminhtml')->__('subscription Manager'));

			$this->_addContent($this->getLayout()->createBlock('blk_subscription/adminhtml_subscription_edit'))
			->_addLeft($this->getLayout()->createBlock('blk_subscription/adminhtml_subscription_edit_tabs'));

			$this->renderLayout();
		}
		else
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('subscription')->__('Subscription does not exists'));
			$this->_redirect('*/*/');
		}
	}

	public function newAction()
	{
		$this->_forward('edit');
	}

	public function saveAction()
	{
		if ( $this->getRequest()->getPost())
		{
			try {
				$postData = $this->getRequest()->getPost();
				$model = Mage::getModel('subscription/master');
				if(!empty($this->getRequest()->getParam('id')))
				{	
					$id =$this->getRequest()->getParam('id');
					$model->load($id);
				}
				$model->addData($postData);
				$model->save();  
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rule successfully saved'));
				Mage::getSingleton('adminhtml/session')->setMessageData(false);
				if ($this->getRequest()->getParam('back'))
				{
					$this->_redirect("*/*/edit", array("id" => $model->getId()));
					return;
				}
				$this->_redirect("*/*/");
				return;
			}
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setMessageData($this->getRequest()->getPost());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		$this->_redirect('*/*/');
	}

	public function massRemoveAction()
	{
		try {
			$ids = $this->getRequest()->getPost('ids', array());

			foreach ($ids as $id) {
				$model = Mage::getModel('subscription/master');
				$model->setId($id)->delete();
			}
			Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__('Rules were romeved'));
		}
		catch (Exception $e) {
			Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
		}
		$this->_redirect('*/*/');
	}
	public function deleteAction()
	{
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('subscription/master');

				$model->setId($this->getRequest()->getParam('id'))
				->delete();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rule successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}
}