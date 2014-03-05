<?php

class Zimbrabilling_Zmb_Model_Invoices extends Mage_Core_Model_Abstract
{
        /**
     * Define resource model
     */
    protected function _construct()
    {    
        $this->_init('zimbrabilling_zmb/invoices');
    }

    /**
     * If object is new adds creation date
     *
     * @return Magentostudy_News_Model_News
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        if ($this->isObjectNew()) {
            $this->setData('payment_date', Varien_Date::now());
        }
        return $this;
    }
}