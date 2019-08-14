<?php

$installer = $this;
$installer->startSetup();
$table = $installer->getConnection()->newTable($installer->getTable('skebby_smsnotifier'))
      ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
          'identity'  => true,
          'unsigned'  => true,
          'nullable'  => false,
          'primary'   => true
        ), 'SMS ID')
      ->addColumn('sender', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
          'nullable'  => false,
        ), 'Sender')
      ->addColumn('message', Varien_Db_Ddl_Table::TYPE_TEXT, 1000, array(
          'nullable'  => false,
        ), 'Message')
      ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
          'unsigned'  => true,
          'nullable'  => true,
        ), 'Customer Entity ID')
      ->addColumn('telephone', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
          'nullable'  => false,
        ), 'Telephone')
      ->addColumn('message_type', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
          'nullable'  => false,
        ), 'Message')
      ->addColumn('delivery_status', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
          'nullable'  => false,
        ), 'Delivery Status')
      ->addColumn('delivery_reference', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
          'nullable'  => true,
        ), 'Delivery User Reference')
      ->addColumn('created', Varien_Db_Ddl_Table::TYPE_DATE , null, array(
          'nullable'  => false,
        ), 'First send date')
      ->setComment('Smsnotifier API Skebby');
$installer->getConnection()->createTable($table);
$installer->endSetup();
