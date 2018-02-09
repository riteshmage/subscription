<?php
	
class Thycart_Subscription_Block_Subscription extends Mage_Core_Block_Template
{
	const DISCOUNT_CONFIG = array(1=>' Fixed',2=>'% Off');
		
	private $subscrptionData = array();
	
	public function discountDetail()
	{
		$unitArray = array();
		$units = array();
		$finalData = array();

		$subscriptionDetails = $this->subscrptionData;

		foreach ($subscriptionDetails as $key => $value)
		{
			if (strpos($value['unit'], ',') !== false) {
				$units[] = explode(',', $value['unit']);
			}
			else
			{
				$units[] = $value['unit'];				
			}
			$finalData[$value['unit']] = $value;
		}	
		unset($subscriptionDetails);
		
		try
		{
			$unitModel = Mage::getSingleton('subscription/unit')
			->getCollection()
			->addFieldToFilter('unit_id', array('in' => $units))
			->addFieldToSelect(array('unit_id','subscription_unit'))
			->getData();
		}
		catch(Exception $e)
		{
			throw new Exception("unable to fetch data");return;
		}
		
		$unitArray = array_column($unitModel, 'subscription_unit','unit_id');

		unset($unitModel);
		
		foreach ($finalData as $finalKey => $tempValue)
		{
			if (strpos($finalKey, ',') !== false) {
				$unitKeyArray = array();
				$unitKeyArray = explode(',', $finalKey);
				foreach ($unitKeyArray as $unitKey => $unit) {
					if(isset($unitArray[$unit]))
					$finalData[$finalKey]['available_units'][$unit] = $unitArray[$unit]; 
				}
			}
			else
			{
				if(isset($unitArray[$unit]))
				{
					$finalData[$finalKey]['available_units'][$finalKey] = $unitArray[$finalKey];
				}
			}
		}
		return $finalData;
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