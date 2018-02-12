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

		$connection = Mage::getSingleton('core/resource')
		->getConnection('core_write');

		try 
		{
			$connection->beginTransaction();

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
			$user = Mage::getSingleton('admin/session');
			if(empty($id))
			{
				$userId = $user->getUser()->getUserId();
				$postData['created_by']	 =$userId;
				$postData['updated_by']	 =$userId;
			}
			else
			{
				$userId = $user->getUser()->getUserId();
				$postData['updated_by']	 =$userId;
			}
			
			$mappingExist =$this->mappingExist($productIdArray, $unitArr);

			if($this->getRequest()->getParam('active'))
			{
				if($mappingExist)
				{
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Rule alredy exists'));
					$this->_redirect('*/*/');return;
				}

				$model->addData($postData);
				$model->save();
				$lastId = $model->getId();

				$this->saveMappingData($productIdArray, $unitArr, $lastId);
			}
			else
			{
				if($mappingExist && $this->getRequest()->getParam('id'))
				{
					$this->deleteMappingData($this->getRequest()->getParam('id'));
				}
				$model->addData($postData);
				$model->save();
			}

			$connection->commit();

			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rule successfully saved'));
			Mage::getSingleton('adminhtml/session')->setSubscriptionData(false);
			if ($this->getRequest()->getParam('back'))
			{
				$this->_redirect("*/*/edit", array("id" => $model->getId()));return;
			}
			$this->_redirect("*/*/");return;
		}
		catch (Exception $e)
		{
			$connection->rollback();

			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			Mage::getSingleton('adminhtml/session')->setSubscriptionData($this->getRequest()->getPost());
			$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));return;
		}
		$this->_redirect('*/*/');
	}

	public function massRemoveAction()
	{
		try {
			$ids = $this->getRequest()->getPost('ids', array());

			foreach ($ids as $id) {
				$this->deleteAction($id);
			}
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rules were romeved'));
		}
		catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/');
	}

	public function deleteAction($id=0)
	{
		if($id > 0)
		{
			$this->getRequest()->setParam('id', $id);
		}
		if( $this->getRequest()->getParam('id') > 0 ) 
		{
			try 
			{
				$model = Mage::getModel('subscription/master');

				$model->setId($this->getRequest()->getParam('id'))
				->delete();

				$this->deleteMappingData($this->getRequest()->getParam('id'));

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rule successfully deleted'));
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

	public function mappingExist($productIdArray, $unitArr)
	{
		if(empty($productIdArray) || empty($unitArr))
		{
			return true;
		}
		try
		{
			$mappingModel = Mage::getModel('subscription/unitproductmapping');
			$checkMapping = $mappingModel->getCollection()
			->addFieldToFilter('product_id',array('in'=> $productIdArray))
			->addFieldToFilter('unit_id',array('in'=> $unitArr))
			->addFieldToFilter('active',1);
			if($this->getRequest()->getParam('id') > 0 && $this->getRequest()->getParam('active') > 0)
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
		catch(Exception $e)
		{
			throw new Exception(EXCEPTION_MSG);return;
		}
	}

	public function saveMappingData($productIdArray, $unitArr, $subscriptionId)
	{
		if(empty($productIdArray) || empty($unitArr) || empty($subscriptionId))
		{
			return false;
		}
		
		foreach ($productIdArray as $productId) 
		{
			foreach ($unitArr as $unitId) 
			{
				$data = array();
				$data = array(
					'product_id'=>$productId,
					'unit_id'=>$unitId,
					'subscription_id'=>$subscriptionId,
					'active'=>1
				);
				try
				{
					$mappingModel = Mage::getModel('subscription/unitproductmapping');
					$mappingModel->addData($data);
					$mappingModel->save();
				}
				catch (Exception $e) 
				{		
					//ignore unique constraint error for product_id,unit_id,subscription_id,active 
					if($e->getCode() !== 23000)
					{
						throw new Exception(EXCEPTION_MSG);return;		
					}
				}
			}
		}
	}

	public function deleteMappingData($subscriptionId)
	{
		if(empty($subscriptionId))
		{
			return false;
		}
		try
		{
			$mappingModel = Mage::getModel('subscription/unitproductmapping');
			$mapping = $mappingModel->getCollection()
			->addFieldToFilter('subscription_id',$subscriptionId);

			foreach ($mapping as $value) 
			{
				$value->delete();
			}
			return true;
		}
		catch (Exception $e) 
		{				
			throw new Exception(EXCEPTION_MSG);return;		
		}
	}
}