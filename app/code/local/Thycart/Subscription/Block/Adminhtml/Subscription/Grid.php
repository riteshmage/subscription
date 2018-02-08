<?php
class Thycart_Subscription_Block_Adminhtml_Subscription_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('subscription_rule');
		$this->setDefaultSort('subscription_id');
		$this->setDefaultDir('ASC');
		$this->setUseAjax(false);
		$this->setSaveParametersInSession(true);
	}
	protected function _prepareCollection()
	{
		$collection = Mage::getResourceModel('subscription/master_collection');
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	protected function _prepareColumns()
	{
		$this->addColumn('id',array(
			'header'    => Mage::helper('subscription')->__('ID'),
			'align'     =>'center',
			'width'     => '50px',
			'index'     => 'subscription_id',
		));
		$this->addColumn('Rule name',array(
			'header'    => Mage::helper('subscription')->__('Rule name'),
			'align'     =>'center',
			'width'     => '50px',
			'index'     => 'subscription_name',
		));
		$this->addColumn('created date',array(
			'header'    => Mage::helper('subscription')->__('created date'),
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

	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('id');
		$this->getMassactionBlock()->setFormFieldName('ids');
		$this->getMassactionBlock()->setUseSelectAll(true);
		$this->getMassactionBlock()->addItem('remove_rule', array(
			'label'=> Mage::helper('subscription')->__('Remove Rule'),
			'url'  => $this->getUrl('*/adminhtml_index/massRemove'),
			'confirm' => Mage::helper('subscription')->__('Are you sure?')
		));
		return $this;
	}
}