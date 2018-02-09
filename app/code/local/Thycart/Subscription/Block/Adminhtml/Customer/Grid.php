<?php
class Thycart_Subscription_Block_Adminhtml_Customer_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('subscribed_customer');
		$this->setDefaultSort('id');
		$this->setDefaultDir('ASC');
		$this->setUseAjax(false);
		$this->setSaveParametersInSession(true);
	}
	protected function _prepareCollection()
	{
		$collection = Mage::getResourceModel('subscription/subscriptioncustomer_collection');
		foreach ($collection as $value)
		{
			$product_id 	= $value->getData()['product_id'];
			$product_name	= Mage::getSingleton('catalog/product')->load($product_id)->getName();
			$value->setData('product_name',$product_name);
		}
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	protected function _prepareColumns()
	{
		$this->addColumn('id',array(
			'header'    => Mage::helper('subscription')->__('ID'),
			'align'     =>'center',
			'width'     => '50px',
			'index'     => 'id',
		));
		$this->addColumn('customer_id',array(
			'header'    => Mage::helper('subscription')->__('Customer id'),
			'align'     =>'center',
			'width'     => '50px',
			'index'     => 'customer_id',
		));
		$this->addColumn('unit_selected',array(
			'header'    => Mage::helper('subscription')->__('Unit Selected'),
			'align'     =>'center',
			'width'     => '50px',
			'index'     => 'unit_selected',
		));
		$this->addColumn('order_id',array(
			'header'    => Mage::helper('subscription')->__('Order Id'),
			'align'     =>'center',
			'width'     => '50px',
			'index'     => 'order_id',
		));
		$this->addColumn('product_name',array(
			'header'    => Mage::helper('subscription')->__('Product Name'),
			'align'     =>'center',
			'width'     => '50px',
			'index'     => 'product_name',
		));
		$this->addColumn('number_of_orders_placed',array(
			'header'    => Mage::helper('subscription')->__('Orders Placed'),
			'align'     =>'center',
			'width'     => '50px',
			'index'     => 'number_of_orders_placed',
		));
		$this->addColumn('active',array(
			'header'    => Mage::helper('subscription')->__('Active'),
			'align'     =>'center',
			'width'     => '50px',
			'index'     => 'active',
			'type'		=>'options',
			'options'	=>array(
							'1' => 'Yes',
							'0' => 'No'
			)
		));
	}
	public function getRowUrl($row)
	{
		return false;
	}
}