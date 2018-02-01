<?php
class Thycart_Subscription_Block_Subscription extends Mage_Core_Block_Template
{
	private $subscrptionData = array();
	
	public function discountDetail()
	{
		$multipleUnit = false;
		$subscriptionDetails = $this->subscrptionData;
		$tempArr = [];
		$units = array();
		foreach ($subscriptionDetails as $key => $value)
		{
			if (strpos($value['unit'], ',') !== false) {
				$units[] = explode(',', $value['unit']);
				$multipleUnit = true;
			}
			else
			{
				$units[] = $value['unit'];				
			}
			$tempArr[$value['unit']] = $value;
		}	
		
		$unitModel = Mage::getSingleton('subscription/unit')
		->getCollection()
		->addFieldToFilter('unit_id', array('in' => $units))
		->addFieldToSelect(array('unit_id','subscription_unit'))
		->getData();
		$finalSubscriptionResult = [];
		// echo "<pre>";print_r($tempArr);
		// echo "<pre>";print_r($unitModel);
		foreach ($tempArr as $key => $tempvalue)
		{
			if(strpos($key,','))
			{
				$tempUnits = '';
				$unit = explode(',', $key);
				foreach ($unit as $unitId)
				{
					foreach ($unitModel as $value)
					{
						if ($value['unit_id']== $unitId)
						{
							$tempUnits .= $value['subscription_unit'].',';  
						}	
					}
				}
				$tempUnits = rtrim($tempUnits, ',');
				$tempArr[$key]['subscription_unit'] = $tempUnits;	
			}
			else
			{
				$tempUnit = '';
				foreach ($unitModel as $value)
				{
					if ($value['unit_id']== $key)
					{
						$tempUnit .= $value['subscription_unit'];  
					}	
				}
				$tempArr[$key]['subscription_unit'] = $tempUnit;	
			}	
		}
		// print_r($tempArr);die;
		// echo "<pre>";print_r($finalSubscriptionResult);die;
		return $tempArr;
	}

	public function subscrptionStatus($productId)
	{
		$subscrptionData = $this->subscrptionData($productId);
		$active = array();
		if(empty($subscrptionData))
		{
			return false;
		}
		return true;
	}

	public function subscrptionData($productId)
	{	
		if(!isset($productId) && empty($product_id))
		{
			return false;	
		}
		if(empty($this->subscrptionData))
		{
			$this->subscrptionData = Mage::getModel('subscription/master')->getCollection()
			->addFieldToSelect(array('subscription_id','unit','max_billing_cycle','discount_type','discount_value','active'))
			->addFieldToFilter('product_id',array('finset'=> $productId))
			->addFieldToFilter('active',1)
			->getData();
		}
		return $this->subscrptionData;
	}

}