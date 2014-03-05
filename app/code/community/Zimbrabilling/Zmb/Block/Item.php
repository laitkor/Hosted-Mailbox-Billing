<?php
/**
 * News Item block
 *
 * @author Magento
 */
class Zimbrabilling_Zmb_Block_Item extends Mage_Core_Block_Template
{
    /**
     * Current news item instance
     *
     * @var Magentostudy_News_Model_News
     */
    protected $_item;

    /**
     * Return parameters for back url
     *
     * @param array $additionalParams
     * @return array
     */
    protected function _getBackUrlQueryParams($additionalParams = array())
    {
        return array_merge(array('p' => $this->getPage()), $additionalParams);
    }

    /**
     * Return URL to the news list page
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/', array('_query' => $this->_getBackUrlQueryParams()));
    }

    /**
     * Return URL for resized News Item image
     *
     * @param Magentostudy_News_Model_News $item
     * @param integer $width
     * @return string|false
     */
    public function getImageUrl($item, $width)
    {
        return Mage::helper('zimbrabilling_zmb/image')->resize($item, $width);
    }
}
