<?php
class Thycart_Subscription_Model_Observer extends Varien_Object
{
    public function setDiscount($observer)
    {
        $params =Mage::getSingleton('core/session')->getSubscriptionParam();
        if(empty($params))
        {
            return;
        }
        $quote         =  $observer->getEvent()->getQuote();
        $quoteid       =  $quote->getId();
        $discountAmount=  $params['discount_value'];
        $disTotal = $quote->getItemsQty() * $discountAmount;

        $productPrice = 123;
        if(isset($quote->getAllItems()[0]))
        {
            $productPrice = $quote->getAllItems()[0]->getPrice();
        }
        
        if($params['discount_type'] == 2)
        {
            $disTotal = ($quote->getItemsQty() * $productPrice) * ($discountAmount/100);
            $discountAmount = $disTotal;
        }
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
                    ->setBaseSubtotalWithDiscount(300)
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
                            $address->setDiscountDescription($address->getDiscountDescription().', Custom Discount');
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
