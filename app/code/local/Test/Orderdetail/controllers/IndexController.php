<?php 

class Test_Orderdetail_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
    {
//        $orderId =  100000008;
//        $order = Mage::getSingleton('sales/order')->loadByIncrementId($orderId);
//       //echo"<pre>";print_r($order);
//        echo "Subtotal: ".$order->getSubtotal();
//        echo "Shipping Amount: ".$order->getShippingAmount();
//        echo "Discount: ".$order->getDiscountAmount();
//        echo "Tax Amt: ".$order->getTaxAmount();
//        echo "Grand Total".$order->getGrandTotal();
//        echo $order->getPayment()->getMethodInstance()->getTitle();
//        $items = $order->getAllVisibleItems();
//        foreach($items as $i)
//        {
//           echo"<pre>"; print_r( $i->getData());
// //        }
//         $model = Mage::getModel('catalog/product')->load(3)->getData()['billing_unit'];

//        $attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product','billing_unit');
//        echo $attributeModel->getStoreLabel($storeId);

        // $option_value = Mage::getResourceModel('eav/entity_attribute_option_collection')
        //     ->setIdFilter($model)
        //     ->getFirstItem();

        $attribute_option_id = Mage::getResourceModel('catalog/product')->getAttributeRawValue(3, 'billing_unit');
        $product = Mage::getModel('catalog/product')
        ->setData('billing_unit', $attribute_option_id);//the result from above
        $text = $product->getAttributeText('billing_unit');
        echo"<pre>"; print_r($text);
}
}