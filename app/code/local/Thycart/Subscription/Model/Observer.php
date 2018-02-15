<?php
class Thycart_Subscription_Model_Observer extends Varien_Object
{
    public function cron()
    {   
        try
        {
            $subscribedCustomers    =   Mage::getModel('subscription/subscriptioncustomer')
                                        ->getCollection()
                                        ->addFieldToFilter('active',1)
                                        ->addFieldToSelect(array('id','last_date','unit_selected','order_id','customer_id','discount_type','discount_value','number_of_orders_placed'))
                                        ->getData();
            $units                  =   Mage::getModel('subscription/unit')
                                         ->getCollection()
                                         ->addFieldToSelect(array('subscription_unit','number_of_days'))
                                         ->getData();

            $unitDays = array_column($units, 'number_of_days','subscription_unit');

            foreach ($subscribedCustomers as $customer)
            {
                foreach ($unitDays as $unit => $days)
                {
                    if($customer['unit_selected'] === $unit)
                    {
                        $lastDate  = date_create($customer['last_date']);
                        $todayDate = date_create(Mage::getModel('core/date')->gmtDate('Y-m-d'));
                        $diff      = date_diff($lastDate,$todayDate);
                        if ($diff->format("%a") == $days)
                        {
                            $orderId = $customer['order_id'];
                            Mage::unregister('rule_data');
                            Mage::getSingleton('adminhtml/session_quote')->clear();
                            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
                            $newQuote = new Mage_Sales_Model_Quote();
                            $newQuote->setStoreId($order->getStoreId());
                            Mage::getSingleton('adminhtml/sales_order_create')->setQuote($newQuote);
                            
                            $order_model = Mage::getSingleton('adminhtml/sales_order_create');
                            $order_model->getSession()->clear();

                            try {
                                $order->setReordered(true);
                                Mage::getSingleton('adminhtml/session_quote')->setUseOldShippingMethod(true);

                                $reorder = new Varien_Object();
                                $reorder = $order_model->initFromOrder($order);
                                $quote   =   $reorder->getQuote();
                                
                                $discountAmount = $customer['discount_value'];
                                $disTotal = $quote->getItemsQty() * $discountAmount;
                                $productPrice = 0;
                                if(isset($quote->getAllItems()[0]))
                                {
                                    $productPrice = $quote->getAllItems()[0]->getPrice();
                                }
                                if($customer['discount_type'] == 2)
                                {
                                    $disTotal = ($quote->getItemsQty() * $productPrice) * ($discountAmount/100);
                                    $discountAmount = $disTotal;
                                }
                                Mage::getModel('subscription/discount')->setDiscount($quote, $discountAmount, $disTotal);
                                $newOrder = $reorder->createOrder();
                                
                                $reOrderIncId  = $newOrder->getIncrementId();
                                $customerId    = $newOrder->getCustomerId();
                                $customerEmail = $newOrder->getCustomerEmail();
                                $update = Mage::getModel('subscription/subscriptioncustomer')->load($customer['id']);
                                $update->setLastDate(Mage::getModel('core/date')->date('Y-m-d'));
                                $update->setNumberOfOrdersPlaced($customer['number_of_orders_placed']+1);
                                $update->save();
                                Mage::log(" Subscription Order Successfully palced for Customer with ID =$customerId  and  email = $customerEmail, order number is $reOrderIncId",null,"subscriptionorder.log");
                            }
                            catch (Exception $e)
                            {
                                Mage::log("Order Error : {$e->getMessage()}",null,"subscriptionorder.log");
                            }
                            $reorder->getSession()->clear();
                            Mage::unregister('rule_data');
                            Mage::getSingleton('adminhtml/session_quote')->clear();
                        }
                    }   
                }
            }
        }

        catch (Mage_Core_Exception $e)
        {
            echo $e;    
        }
    
    }
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
            $cartitem = Mage::getSingleton('checkout/cart');
            foreach ($cartitem->getQuote()->getItemsCollection() as $_item) 
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