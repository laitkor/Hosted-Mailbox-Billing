<?php

class Zimbrabilling_Zmb_Block_Adminhtml_Invoices_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('zimbrabilling_zmb')->__('Plans Info'));
    }
}