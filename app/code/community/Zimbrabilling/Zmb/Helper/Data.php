<?php
/**
 * News Data helper
 *
 * @author Magento
 */
class Zimbrabilling_Zmb_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     * Path to store config if front-end output is enabled
     *
     * @var string
     */
    const XML_PATH_ENABLED            = 'zmb/view/enabled';

    /**
     * Path to store config where count of news posts per page is stored
     *
     * @var string
     */
    const XML_PATH_ITEMS_PER_PAGE     = 'zmb/view/items_per_page';

    /**
     * Path to store config where count of days while news is still recently added is stored
     *
     * @var string
     */
    const XML_PATH_DAYS_DIFFERENCE    = 'zmb/view/days_difference';

    /**
     * News Item instance for lazy loading
     *
     * @var Magentostudy_News_Model_News
     */
    protected $_zmbItemInstance;

    /**
     * Checks whether news can be displayed in the frontend
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function isEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED, $store);
    }

    /**
     * Return the number of items per page
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return int
     */
    public function getZmbPerPage($store = null)
    {
        return abs((int)Mage::getStoreConfig(self::XML_PATH_ITEMS_PER_PAGE, $store));
    }

    /**
     * Return difference in days while news is recently added
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return int
     */
    public function getDaysDifference($store = null)
    {
        return abs((int)Mage::getStoreConfig(self::XML_PATH_DAYS_DIFFERENCE, $store));
    }

    /**
     * Return current news item instance from the Registry
     *
     * @return Magentostudy_News_Model_News
     */
    public function getZmbItemInstance()
    {
        if (!$this->_zmbItemInstance) {
            $this->_zmbItemInstance = Mage::registry('zmb_item');

            if (!$this->_zmbItemInstance) {
                Mage::throwException($this->__('Zmb item instance does not exist in Registry'));
            }
        }

        return $this->_zmbItemInstance;
    }
	
	public function getBillingItemInstance(){
		
		$zmbid = $this->_zmbItemInstance->zmb_id;
	   $read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$getdata = $read->query("SELECT * FROM zimbrabilling_cycles WHERE planid = '".$zmbid."'")->fetchAll();	
	   return $getdata;	
	}
	
	public function getInvoicesItemInstance(){
		
	   $read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$getdata = $read->query("SELECT * FROM zimbrabilling_invoices")->fetchAll();	
	   return $getdata;	
	}
	
}