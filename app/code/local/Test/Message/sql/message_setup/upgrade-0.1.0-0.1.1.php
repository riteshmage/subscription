<?php
$installer = $this;
$table = $installer->getConnection()
         ->newTable($installer->getTable('cms_page_data'))
         ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
         'identity'  => true,
         'unsigned'  => true,
         'nullable'  => false,
         'primary'   => true,
         ),'ID')
         ->addColumn('page_url_key', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
         'unsigned'  => true,
         'nullable'  => false,
         ),'Url')
         ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array( 
         'unsigned'  => true,
         'nullable'  => false,
        
         ),'Time of creation');
if (!$installer->getConnection()->isTableExists($table->getName('cms_page_data')))
{
	$installer->getConnection()->createTable($table);
}
$installer->endSetup();




// CREATE TABLE `magento`.`cms_data_page1` ( `id` INT(11) NOT NULL AUTO_INCREMENT , 
// 	`url_key` VARCHAR(255) NOT NULL , 
// 	`created_at` TIMESTAMP NOT NULL , 
// 	`updated_at` TIMESTAMP NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

