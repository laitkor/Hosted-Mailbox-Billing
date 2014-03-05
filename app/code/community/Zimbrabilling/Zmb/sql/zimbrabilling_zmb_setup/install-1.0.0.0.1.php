<?php
/**
 * Zmb installation script
 *
 * @author Magento
 */

/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

/**
 * Creating tables for zimbrabilling
 */
 

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('zimbrabilling_zmb')};
CREATE TABLE IF NOT EXISTS `zimbrabilling_zmb` (
  `zmb_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(63) DEFAULT NULL ,
  `content` mediumtext ,
  `image` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `mailboxes` int(11) NOT NULL,
  `mailboxsize` varchar(50) NOT NULL,
  PRIMARY KEY (`zmb_id`)
) ENGINE=InnoDB ;

");

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('zimbrabilling_domains')};
 CREATE TABLE IF NOT EXISTS `zimbrabilling_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerid` int(11) NOT NULL,
  `planid` int(11) NOT NULL,
  `plan_title` varchar(255) NOT NULL,
  `domain` text NOT NULL,
  `mailboxes` int(11) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `billingcycle` int(11) NOT NULL,
  `additional_mailboxes` int(11) NOT NULL,
  `expiry_date` datetime NOT NULL,
  `email_prefered` text NOT NULL,
  `plan_price` text NOT NULL,
  `renew_status` varchar(50) NOT NULL,
  `upgrade_price` varchar(100) NOT NULL,
  `plan_mailboxes` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

");

$installer->run("
  -- DROP TABLE IF EXISTS {$this->getTable('zimbrabilling_cycles')};
 CREATE TABLE IF NOT EXISTS `zimbrabilling_cycles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `planid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` varchar(50) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `per_mailbox_price` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
");

$installer->run("
  CREATE TABLE IF NOT EXISTS `zimbrabilling_mailboxes` (
  `intID` int(11) NOT NULL AUTO_INCREMENT,
  `customerid` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `createdtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `domain` text NOT NULL,
  PRIMARY KEY (`intID`)
) ENGINE=InnoDB ;

");

$installer->run("
 CREATE TABLE IF NOT EXISTS `zimbrabilling_invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `payment_date` varchar(255) NOT NULL,
  `payment_status` varchar(100) NOT NULL,
  `address_street` text NOT NULL,
  `address_city` text NOT NULL,
  `address_country` text NOT NULL,
  `business_email` varchar(255) NOT NULL,
  `payer_email` varchar(255) NOT NULL,
  `txn_id` text NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `receiver_email` text NOT NULL,
  `receiver_id` text NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `payment_gross` varchar(255) NOT NULL,
  `item_number` varchar(100) NOT NULL,
  `address_status` varchar(100) NOT NULL,
  `protection_eligibility` varchar(100) NOT NULL,
  `customerid` int(11) NOT NULL,
  `payer_id` varchar(100) NOT NULL,
  `payer_status` varchar(50) NOT NULL,
  `address_zip` varchar(100) NOT NULL,
  `address_country_code` varchar(50) NOT NULL,
  `address_name` varchar(100) NOT NULL,
  `address_state` varchar(100) NOT NULL,
  `residence_country` varchar(100) NOT NULL,
  `handling_amount` varchar(100) NOT NULL,
  `pending_reason` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;


");

$installer->endSetup(); 
/*********create table code ends here*********************/

$cmsPageData = array(
    'title' => 'Create Mailboxes',
    'root_template' => 'one_column',
    'meta_keywords' => '',
    'meta_description' => '',
    'identifier' => 'mailboxes',
    'content_heading' => '',
    'stores' => array(0),//available for all store views
    'content' => '{{block type="zimbrabilling_zmb/mailboxes" name="zimbrabilling.zmb.mailboxes" template="zimbrabilling/zmb/mailboxes.phtml"}}'
);

Mage::getModel('cms/page')->setData($cmsPageData)->save();

$cmsPageData1 = array(
    'title' => 'Thankyou Page',
    'root_template' => 'one_column',
    'meta_keywords' => '',
    'meta_description' => '',
    'identifier' => 'thankyou-page',
    'content_heading' => '',
    'stores' => array(0),//available for all store views
    'content' => '{{block type="zimbrabilling_zmb/thankyou" name="zimbrabilling.zmb.thankyou" template="zimbrabilling/zmb/thankyou.phtml"}}'
);

Mage::getModel('cms/page')->setData($cmsPageData1)->save();

$cmsPageData2 = array(
    'title' => 'Renew Page',
    'root_template' => 'one_column',
    'meta_keywords' => '',
    'meta_description' => '',
    'identifier' => 'renew',
    'content_heading' => '',
    'stores' => array(0),//available for all store views
    'content' => '{{block type="zimbrabilling_zmb/renew" name="zimbrabilling.zmb.renew" template="zimbrabilling/zmb/renew.phtml"}}'
);

Mage::getModel('cms/page')->setData($cmsPageData2)->save();

$cmsPageData3 = array(
    'title' => 'Upgrade',
    'root_template' => 'one_column',
    'meta_keywords' => '',
    'meta_description' => '',
    'identifier' => 'upgrade',
    'content_heading' => '',
    'stores' => array(0),//available for all store views
    'content' => '{{block type="zimbrabilling_zmb/upgrade" name="zimbrabilling.zmb.upgrade" template="zimbrabilling/zmb/upgrade.phtml"}}'
);

Mage::getModel('cms/page')->setData($cmsPageData3)->save();

$cmsPageData4 = array(
    'title' => 'Cancel',
    'root_template' => 'one_column',
    'meta_keywords' => '',
    'meta_description' => '',
    'identifier' => 'cancel',
    'content_heading' => '',
    'stores' => array(0),//available for all store views
    'content' => '{{block type="zimbrabilling_zmb/cancel" name="zimbrabilling.zmb.cancel" template="zimbrabilling/zmb/cancel.phtml"}}'
);

Mage::getModel('cms/page')->setData($cmsPageData4)->save();