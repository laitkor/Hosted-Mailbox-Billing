<?php
class Zimbrabilling_Zmb_Block_Adminhtml_Invoices extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_invoices';
    $this->_blockGroup = 'zimbrabilling_zmb';
    $this->_headerText = Mage::helper('zimbrabilling_zmb')->__('Invoices');
    $this->_addButtonLabel = Mage::helper('zimbrabilling_zmb')->__('Add Plan');
    parent::__construct();
	$this->removeButton('add');
	$this->setFilterVisibility(false);
  }
  

  
}