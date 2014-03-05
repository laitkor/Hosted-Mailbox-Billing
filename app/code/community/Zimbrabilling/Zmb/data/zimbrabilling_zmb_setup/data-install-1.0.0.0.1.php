<?php
/**
 * Zmb data installation script
 *
 * @author Magento
 */

/**
 *  @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

/**
 * @var $model Zimbrabilling_Zmb_Model_Zmb
 */
$model = Mage::getModel('zimbrabilling_zmb/zmb');
$installer->run("INSERT INTO `zimbrabilling_zmb`(`zmb_id`,`title`,`author`,`content`,`created_at`,`mailboxes`,`mailboxsize`)VALUES(
				'1','Test Plan1','Author','<p>Description</p>','2013-07-02','5','512MB')");
				
$installer->run("INSERT INTO `zimbrabilling_zmb`(`zmb_id`,`title`,`author`,`content`,`created_at`,`mailboxes`,`mailboxsize`)VALUES(
				'2','Test Plan2','Author','<p>Description</p>','2013-07-02','10','1024MB')");
				
				
/**********insert into zimbrabilling_cycles table for plan1********************/

$installer->run("INSERT INTO `zimbrabilling_cycles`(`id`,`title`,`planid`,`price`,`duration`,`per_mailbox_price`)VALUES(
				'1','Monthly','1','$0','monthly','$0')");
				
$installer->run("INSERT INTO `zimbrabilling_cycles`(`id`,`title`,`planid`,`price`,`duration`,`per_mailbox_price`)VALUES(
				'2','Quaterly','1','$0','quaterly','$0')");
				
$installer->run("INSERT INTO `zimbrabilling_cycles`(`id`,`title`,`planid`,`price`,`duration`,`per_mailbox_price`)VALUES(
				'3','Half-yearly','1','$0','semi-anually','$0')");
				
$installer->run("INSERT INTO `zimbrabilling_cycles`(`id`,`title`,`planid`,`price`,`duration`,`per_mailbox_price`)VALUES(
				'4','Anually','1','$0','anually','$0')");
				
/*********insert into zimbrabilling_cycles table for plan2*****************/				

$installer->run("INSERT INTO `zimbrabilling_cycles`(`id`,`title`,`planid`,`price`,`duration`,`per_mailbox_price`)VALUES(
				'5','Monthly','2','$0','monthly','$0')");
				
$installer->run("INSERT INTO `zimbrabilling_cycles`(`id`,`title`,`planid`,`price`,`duration`,`per_mailbox_price`)VALUES(
				'6','Quaterly','2','$0','quaterly','$0')");
				
$installer->run("INSERT INTO `zimbrabilling_cycles`(`id`,`title`,`planid`,`price`,`duration`,`per_mailbox_price`)VALUES(
				'7','Half-yearly','2','$0','semi-anually','$0')");
				
$installer->run("INSERT INTO `zimbrabilling_cycles`(`id`,`title`,`planid`,`price`,`duration`,`per_mailbox_price`)VALUES(
				'8','Anually','2','$0','anually','$0')");
			
$installer->endSetup(); 
				