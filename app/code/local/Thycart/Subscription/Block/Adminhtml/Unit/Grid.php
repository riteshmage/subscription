<?php
class Thycart_Subscription_Block_Adminhtml_Unit_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('unit_rule');
		$this->setDefaultSort('unit_id');
		$this->setDefaultDir('ASC');
		$this->setUseAjax(false);
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareCollection()
	{
		$collection = Mage::getResourceModel('subscription/unit_collection');
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$this->addColumn('unit_id',array(
			'header'    => Mage::helper('subscription')->__('ID'),
			'align'     =>'center',
			'width'     => '50px',
			'index'     => 'unit_id',
		));
		$this->addColumn('subscription_unit',array(
			'header'    => Mage::helper('subscription')->__('Subscription unit'),
			'align'     =>'center',
			'width'     => '50px',
			'index'     => 'subscription_unit',
		));
		$this->addColumn('created_date',array(
			'header'    => Mage::helper('subscription')->__('Created date'),
			'align'     =>'center',
			'width'     => '50px',
			'index'     => 'created_time',
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
		return $this->getUrl("*/*/edit", array("id" => $row->getId()));
	}
}