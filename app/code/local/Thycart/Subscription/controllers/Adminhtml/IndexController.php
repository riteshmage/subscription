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
		$this->_title($this->__('Subscription Rule'));
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
		$postData = $this->getRequest()->getPost();
		$postData = $this->validateFilterData($postData);

		try 
		{
			$productIdArray = array();
			
			$model = Mage::getModel('subscription/master');
			if($this->getRequest()->getParam('id') > 0)
			{	
				$id = $this->getRequest()->getParam('id');
				$model->load($id);
			}			

			$unitArr = $this->getRequest()->getParam('unit');
			$unit = implode(',', $unitArr);						
			
			$sku  = $this->getRequest()->getParam('product_sku');

			if( strpos($sku,',') !== false ) 
			{
				$product_ids ='';
				$skuArr  = explode(',',$sku);
				foreach ($skuArr as $key => $value) 
				{	
					$product_id = Mage::getSingleton("catalog/product")->getIdBySku($value);
					$product_ids .= $product_id.',';
					$productIdArray[] = $product_id;					
				}
				$product_ids = rtrim($product_ids,',');
			}
			else
			{
				$product_ids = Mage::getSingleton("catalog/product")->getIdBySku($sku);
				$productIdArray[] = $product_ids;
			}

			$postData['product_id']  = $product_ids;
			$postData['product_sku'] = $sku;
			$postData['unit']        = $unit;
			
			if($this->getRequest()->getParam('active'))
			{
				$mappingModel = Mage::getSingleton('subscription/unitproductmapping');
				$mappingExist =$this->mappingExist($productIdArray, $unitArr, $mappingModel);
				if (!empty($mappingExist))
				{
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Rule alredy exists'));
					$this->_redirect('*/*/');
					return;
				}

				$model->addData($postData);
				$model->save();
				$lastId = $model->getId();

				$this->saveMappingData($productIdArray, $unitArr, $mappingModel, $lastId);
			}
			else
			{
				$model->addData($postData);
				$model->save();
			}

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

	public function validateFilterData($postData)
	{
		if ( empty($this->getRequest()->getParam('subscription_name')) || empty($this->getRequest()->getParam('discount_type')) || empty($this->getRequest()->getParam('unit')) || empty($this->getRequest()->getParam('product_sku')))
		{
			Mage::getSingleton('adminhtml/session')->addError('Please enter required fields');
			if ($this->getRequest()->getParam('back'))
			{
				$this->_redirect("*/*/edit", array("id" => empty($this->getRequest()->getParam('id'))));
				return;
			}
			$this->_redirect("*/*/");
			return;
		}
		if(!empty($postData))
		{
			unset(
				$postData['page'],$postData['limit'],
				$postData['in_products'],$postData['type'],
				$postData['set_name'],$postData['chooser_sku'],
				$postData['chooser_sku'],$postData['entity_id'],
				$postData['chooser_name'],$postData['is_active']
			);
			return $postData;
		}
	}

	public function mappingExist($productIdArray, $unitArr, $mappingModel)
	{
		if(empty($productIdArray) || empty($unitArr) || empty($mappingModel))
		{
			return true;
		}

		$checkMapping = $mappingModel->getCollection()
		->addFieldToFilter('product_id',array('in'=> $productIdArray))
		->addFieldToFilter('unit_id',array('in'=> $unitArr))
		->addFieldToFilter('active',1);
		if($this->getRequest()->getParam('id') > 0)
		{
			$checkMapping->addFieldToFilter(
				'subscription_id',
				array(
					'nin'=>array($this->getRequest()->getParam('id'))
				)
			);
		}
		$mappingData = $checkMapping->getData();

		return $mappingData;
	}

	public function saveMappingData($productIdArray, $unitArr, $mappingModel, $subscriptionId)
	{
		if(empty($productIdArray) || empty($unitArr) || empty($mappingModel) || empty($subscriptionId))
		{
			return false;
		}

		foreach ($productIdArray as $productkey => $productValue) 
		{
			foreach ($unitArr as $unitkey => $unitValue) 
			{
				$data = array();
				$data = array(
					'product_id'=>$productValue,
					'unit_id'=>$unitValue,
					'subscription_id'=>$subscriptionId,
					'active'=>1
				);
				
				$mappingModel->addData($data);
				$mappingModel->save();
			}
		}
		return true;
	}
}