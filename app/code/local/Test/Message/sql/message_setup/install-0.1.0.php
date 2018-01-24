<?php 
$installer = $this;
$installer->startSetup();
$table = $installer->getConnection()
         ->newTable($installer->getTable('message'))
         ->addColumn('message_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
         'identity'  => true,
         'unsigned'  => true,
         'nullable'  => false,
         'primary'   => true,
         ), 'Message ID')
         ->addColumn('message_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
         'unsigned'  => true,
         'nullable'  => false,
         
         ), 'Message Name')
         ->addColumn('message_description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
         'unsigned'  => true,
         'nullable'  => false,
        
         ), 'Message Description');
if (!$installer->getConnection()->isTableExists($table->getName('message')))
{
   $installer->getConnection()->createTable($table);
}
$installer->endSetup();