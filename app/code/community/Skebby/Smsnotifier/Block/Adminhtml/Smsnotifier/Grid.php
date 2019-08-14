<?php

class Skebby_Smsnotifier_Block_Adminhtml_Smsnotifier_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('smsnotifier_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('smsnotifier/smsnotifier')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('id', array(
            'header'    => Mage::helper('smsnotifier')->__('ID'),
            'align'     =>'left',
            'width'     => '40px',
            'index'     => 'id',
        ));
        $this->addColumn('created', array(
            'header'    => Mage::helper('smsnotifier')->__('Created date'),
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'created',
            'type'      => 'date'
        ));
        $this->addColumn('sender', array(
            'header'    => Mage::helper('smsnotifier')->__('Sender'),
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'sender',
        ));
        $this->addColumn('telephone', array(
            'header'    => Mage::helper('smsnotifier')->__('Phone number'),
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'telephone',
        ));
        $this->addColumn('message', array(
            'header'    => Mage::helper('smsnotifier')->__('Message'),
            'align'     =>'left',
            'width'     => '250px',
            'index'     => 'message',
        ));
        $this->addColumn('message_type', array(
            'header'    => Mage::helper('smsnotifier')->__('Message Type'),
            'align'     =>'left',
            'width'     => '90px',
            'index'     => 'message_type',
        ));
        $this->addColumn('delivery_status', array(
            'header'    => Mage::helper('smsnotifier')->__('Delivery Status'),
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'delivery_status',
            'type'      => 'options',
            'options'   => Mage::getModel('smsnotifier/config')->getMessageStatuses()
        ));

        return parent::_prepareColumns();
    }

}
