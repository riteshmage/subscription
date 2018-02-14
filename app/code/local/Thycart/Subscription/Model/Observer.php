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
        if(Mage::app()->getRequest()->getActionName() == 'index' && $params['product'])
        {
            $item = $observer->getEvent()->getQuoteItem();
            $cart = Mage::getSingleton('checkout/cart');
            foreach ($cart->getQuote()->getItemsCollection() as $_item) 
            {
                $_item->isDeleted(true);
            }  
            $product = Mage::getModel('catalog/product');
            $productToAdd = $product->load($params['product']);
            $cart = Mage::getSingleton('checkout/session')->getQuote();
            $cart->addProduct($productToAdd, $params['qty']);
            $cart->save();
        } 
        
        $quote         =  $observer->getEvent()->getQuote();
        $discountAmount=  $params['discount_value'];
        $disTotal = $quote->getItemsQty() * $discountAmount;

        $productPrice = 0;
        if(isset($quote->getAllItems()[0]))
        {
            $productPrice = $quote->getAllItems()[0]->getPrice();
        }
        
        if($params['discount_type'] == 2)
        {
            $disTotal = ($quote->getItemsQty() * $productPrice) * ($discountAmount/100);
            $discountAmount = $disTotal;
        }

        Mage::getModel('subscription/discount')->setDiscount($quote, $discountAmount, $disTotal);
    }
    public function successfullySubscribed($observer)
    {
        $params =Mage::getSingleton('core/session')->getSubscriptionParam();
        if(empty($params))
        {
            return;
        }       
        if(empty($observer))
        {
            return ;
        }
        $orderId     =   $observer->getData('order_ids');
        $order       =   Mage::getSingleton('sales/order')->load($orderId);
        $Incrementid =   $order->getIncrementId();
        $unit        =   $params['unit'];
        $date        =   Mage::getModel('core/date')->gmtDate('Y-m-d');
        $customerId  =   Mage::getSingleton('customer/session')->getId();
        $productId   =   $params['product'];

        
        $data       =   array(
                                'start_date'    =>  $date,
                                'last_date'     =>  $date,
                                'unit_selected' =>  $unit,
                                'order_id'      =>  $Incrementid,
                                'product_id'    =>  $productId,
                                'customer_id'   =>  $customerId,
                                'number_of_orders_placed'=>1,
                                'discount_type' =>  $params['discount_type'],
                                'discount_value'=>  $params['discount_value'],
                                'active'        =>  1
                              );
        try
        {
            $model = Mage::getSingleton('subscription/subscriptioncustomer');
            $model->addData($data)
            ->save();         
        }
        catch(Mage_Core_Exception $e)
        {
            Mage::throwExceptoin('unable to subscried');
            return; 
        }
    }       
}