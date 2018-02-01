<?php
class Thycart_Subscription_Adminhtml_UnitController extends Mage_Adminhtml_Controller_Action
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
		$this->_title('Unit Master');
		$this->_initAction();
		$this->renderLayout();
	}
	public function editAction()
	{
		$model = new stdClass();
		$id = 0;
		$this->_title($this->__("Unit Master"));
		if($this->getRequest()->getParam('id'))
		{
			$id = $this->getRequest()->getParam('id');
		}
		try
		{
			$model  = Mage::getModel('subscription/unit')->load($id);			
		}
		catch (Exception $e)
		{
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			Mage::getSingleton('adminhtml/session')->setMessageData($this->getRequest()->getPost());
			$this->_redirect('*/*/edit', array('id' => $id));
			return;
			exit();
		}
		if (!empty($model) || empty($id))
		{
			Mage::register('unit_data', $model);
			$this->loadLayout();
			$this->_setActiveMenu('promo');
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Unit Manager'), Mage::helper('adminhtml')->__('unit Manager'));

			$this->_addContent($this->getLayout()->createBlock('blk_subscription/adminhtml_unit_edit'))
			->_addLeft($this->getLayout()->createBlock('blk_subscription/adminhtml_unit_edit_tabs'));
			$this->renderLayout();
		}
		else
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('subscription')->__('Unit does not exists'));
			$this->_redirect('*/*/');
			exit();
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
				$model = Mage::getModel('subscription/unit');
				if(!empty($this->getRequest()->getParam('id')))
				{	
					$id = $this->getRequest()->getParam('id');
					$model->load($id);
				}
				$model->addData($postData);
				$model->save();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Unit successfully saved'));
				Mage::getSingleton('adminhtml/session')->setSubscriptionData(false);
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
				Mage::getSingleton('adminhtml/session')->setUnitData($this->getRequest()->getPost());
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
				$model = Mage::getModel('subscription/unit');
				$model->setId($id)->delete();
			}
			Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__('Unit were romeved'));
		}
		catch (Exception $e) {
			Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
		}
		$this->_redirect('*/*/');
	}
	public function deleteAction()
	{
		if( $this->getRequest()->getParam('id') > 0 )
		{
			try {
				$model = Mage::getModel('subscription/unit');

				$model->setId($this->getRequest()->getParam('id'))
				->delete();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Unit successfully deleted'));
				$this->_redirect('*/*/');
			}
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}
}