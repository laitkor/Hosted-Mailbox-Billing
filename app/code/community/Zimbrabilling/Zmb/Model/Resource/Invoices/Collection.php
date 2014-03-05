<?php
/**
 * News collection
 *
 * @author Magento
 */
class Zimbrabilling_Zmb_Model_Resource_Invoices_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define collection model
     */
    protected function _construct()
    {
        $this->_init('zimbrabilling_zmb/invoices');
    }

    /**
     * Prepare for displaying in list
     *
     * @param integer $page
     * @return Magentostudy_News_Model_Resource_News_Collection
     */
    public function prepareForList($page)
    {
        $this->setPageSize(Mage::helper('zimbrabilling_zmb')->getZmbPerPage());
        $this->setCurPage($page)->setOrder('published_at', Varien_Data_Collection::SORT_ORDER_DESC);
        return $this;
    }
}