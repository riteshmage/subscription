<?php 
//grid is created from this class
class Test_Message_Block_Adminhtml_Message_Grid extends Mage_Adminhtml_Block_Widget_Grid
{	
	public function __construct()
		{        
			parent::__construct();
			$this->setId("messageGrid");
			$this->setDefaultSort("message_id");
			$this->setDefaultDir("DESC");
			$this->setSaveParametersInSession(true);
		}

    protected function _prepareCollection()
		{//to get all the values from db using  model collection
				$collection = Mage::getModel("message/message")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}

	protected function _prepareColumns()
		{//this columns are created to show all the data from db when menu is selected
			$this->addColumn("message_id", array(
			"header" => Mage::helper("message")->__("ID"),
			"align" =>"left",
			"width" => "50px",
		    "type" => "number",
			"index" => "message_id",
			));

			$this->addColumn("message_name", array(
			"header" => Mage::helper("message")->__("Message Name"),
			"align" =>"left",
			"width" => "50px",
		    "type" => "text",
			"index" => "message_name",
			));

			$this->addColumn("message_description", array(
			"header" => Mage::helper("message")->__("Message Description"),
			"align" =>"left",
			"width" => "50px",
		    "type" => "text",
			"index" => "message_description",
			));
			
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

			return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{//this url is shown when we hovwer over the message
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}

		protected function _prepareMassaction()
		{//this function for mass acions like select all delete all
			$this->setMassactionIdField('id');//key which is been selected to perform mass action
			$this->getMassactionBlock()->setFormFieldName('ids');
			//this name is set which is been used to perform mass actions
			$this->getMassactionBlock()->setUseSelectAll(true);
			//select all link is visible we can select all the rows by clicking
			$this->getMassactionBlock()->addItem('remove_message', array(
					 'label'=> Mage::helper('message')->__('Remove Message'),
					 'url'  => $this->getUrl('*/adminhtml_message/massRemove'),
					 'confirm' => Mage::helper('message')->__('Are you sure?')
				));
			//this action is appeared in actions dropdown
			return $this;
		}
}

