<?php
class Thycart_Subscription_Model_Discount
{
	public function setDiscount($quote, $discountAmount, $disTotal)
	{
		$quoteid = $quote->getId();

		if($quoteid) 
		{
			if($discountAmount>0) 
			{

				$total=$quote->getBaseSubtotal();
				$quote->setSubtotal(0);
				$quote->setBaseSubtotal(0);
				$quote->setSubtotalWithDiscount(0);
				$quote->setBaseSubtotalWithDiscount(0);
				$quote->setGrandTotal(0);
				$quote->setBaseGrandTotal(0);
				$canAddItems = $quote->isVirtual()? ('billing') : ('shipping'); 

				foreach ($quote->getAllAddresses() as $address) 
				{
					$address->setSubtotal(0);
					$address->setBaseSubtotal(0);
					$address->setGrandTotal(0);
					$address->setBaseGrandTotal(0);
					$address->collectTotals();
					$quote->setSubtotal((float) $quote->getSubtotal() + $address->getSubtotal());
					$quote->setBaseSubtotal((float) $quote->getBaseSubtotal() + $address->getBaseSubtotal());
					$quote->setSubtotalWithDiscount((float) $quote->getSubtotalWithDiscount() + $address->getSubtotalWithDiscount());
					$quote->setBaseSubtotalWithDiscount((float) $quote->getBaseSubtotalWithDiscount() + $address->getBaseSubtotalWithDiscount());
					$quote->setGrandTotal((float) $quote->getGrandTotal() + $address->getGrandTotal());
					$quote->setBaseGrandTotal((float) $quote->getBaseGrandTotal() + $address->getBaseGrandTotal());
					$quote ->save();
					$quote->setGrandTotal($quote->getBaseSubtotal()-$discountAmount)
					->setBaseGrandTotal($quote->getBaseSubtotal()-$discountAmount)
					->setSubtotalWithDiscount($quote->getBaseSubtotal()-$discountAmount)
					->save(); 


					if($address->getAddressType()==$canAddItems) 
					{
						$address->setSubtotalWithDiscount((float)$address->getSubtotalWithDiscount()-$disTotal);
						$address->setGrandTotal((float) $address->getGrandTotal()-$disTotal);
						$address->setBaseSubtotalWithDiscount((float)$address->getBaseSubtotalWithDiscount()-$disTotal);
						$address->setBaseGrandTotal((float)$address->getBaseGrandTotal()-$disTotal);
						if($address->getDiscountDescription())
						{
							$address->setDiscountAmount($quote->getItemsQty() * $disTotal);
							$address->setDiscountDescription($address->getDiscountDescription().', Subscription Discount');
							$address->setBaseDiscountAmount($quote->getItemsQty() * $disTotal);
						}
						else 
						{
							$address->setDiscountAmount($disTotal);
							$address->setDiscountDescription('Subscription Discounts');
							$address->setBaseDiscountAmount();
						}
						$address->save();
					}
				}
				foreach($quote->getAllItems() as $item)
				{
					$item->setDiscountAmount($disTotal);
					$item->setBaseDiscountAmount($disTotal)->save();
				}
			}
		}
	}
}