<?php
	class Zimbrabilling_Zmb_Model_Resource_Invoices extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize connection and define main table and primary key
     */
    protected function _construct()
    {
        $this->_init('zimbrabilling_zmb/invoices', 'id');
    }
}
