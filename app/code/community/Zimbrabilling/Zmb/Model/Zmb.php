<?php

class Zimbrabilling_Zmb_Model_Zmb extends Mage_Core_Model_Abstract
{
        /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('zimbrabilling_zmb/zmb');
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
            $this->setData('created_at', Varien_Date::now());
        }
        return $this;
    }
}