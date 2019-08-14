<?php

class Skebby_Smsnotifier_Block_Adminhtml_Smsnotifier_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('smsnotifier_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('smsnotifier')->__('SMS Content'));
  }

  protected function _beforeToHtml()
  {

      $this->addTab('form_section', array(
          'label'     => Mage::helper('smsnotifier')->__('SMS Content'),
          'title'     => Mage::helper('smsnotifier')->__('SMS Content'),
          'content'   => $this->getLayout()->createBlock('smsnotifier/adminhtml_smsnotifier_edit_tab_form')->toHtml(),
      ));

      return parent::_beforeToHtml();
  }
}
