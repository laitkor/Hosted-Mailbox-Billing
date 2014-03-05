<?php

class Zimbrabilling_Zmb_Model_Cancel extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('zmb/cancel');
		
    }
}