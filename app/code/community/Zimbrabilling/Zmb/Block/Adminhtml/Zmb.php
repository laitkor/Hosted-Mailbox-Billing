<?php
class Zimbrabilling_Zmb_Block_Adminhtml_Zmb extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_zmb';
    $this->_blockGroup = 'zimbrabilling_zmb';
    $this->_headerText = Mage::helper('zimbrabilling_zmb')->__('Plans Manager');
    $this->_addButtonLabel = Mage::helper('zimbrabilling_zmb')->__('Add Plan');
    parent::__construct();
  }
  
  
}