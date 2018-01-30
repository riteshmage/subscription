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
		}
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
			exit();
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
				unset($postData['page'],$postData['limit'],$postData['in_products'],$postData['type'],$postData['set_name'],$postData['chooser_sku'],$postData['chooser_sku'],$postData['entity_id'],$postData['chooser_name'],$postData['form_key'],$postData['is_active']);
				$model = Mage::getModel('subscription/master');
				if(!empty($this->getRequest()->getParam('id')))
				{	
					$id = $this->getRequest()->getParam('id');
					$model->load($id);
				}
				if($this->getRequest()->getParam('product_sku') && $this->getRequest()->getParam('unit'))
				{
					$sku  = $this->getRequest()->getParam('product_sku');
					$unit = $this->getRequest()->getParam('unit');
					$sku  = explode(',',$sku);
					$sku  = array_map('trim',$sku);
					if(is_array($unit))
					{
						$unit = implode(',', $unit);						
					}
					$product_id ='';
					foreach ($sku as $key => $value) 
					{	
						$product_ids = Mage::getSingleton("catalog/product")->getIdBySku($value);
						$product_id .= $product_ids.',';
					}
					$product_id = rtrim($product_id,',');
					$sku = implode(',', $sku);

					$postData['product_id']  = $product_id;
					$postData['product_sku'] = $sku;
					$postData['unit']        = $unit;
				}
				$model->addData($postData);
				$model->save();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rule successfully saved'));
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
				Mage::getSingleton('adminhtml/session')->setSubscriptionData($this->getRequest()->getPost());
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