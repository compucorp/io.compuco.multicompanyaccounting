-- /*******************************************************
-- *
-- * Clean up the existing tables - this section generated from drop.tpl
-- *
-- *******************************************************/
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `multicompanyaccounting_company`;

SET FOREIGN_KEY_CHECKS=1;

-- /*******************************************************
-- *
-- * Create new tables
-- *
-- *******************************************************/

-- /*******************************************************
-- *
-- * multicompanyaccounting_company
-- *
-- * Holds the company (legal entity) information
-- *
-- *******************************************************/
CREATE TABLE `multicompanyaccounting_company` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Company ID',
  `contact_id` int unsigned COMMENT 'FK to Contact',
  `invoice_template_id` int unsigned COMMENT 'FK to the message template.',
  `invoice_prefix` varchar(11),
  `next_invoice_number` varchar(11),
  `creditnote_prefix` varchar(11),
  `next_creditnote_number` varchar(11),
  PRIMARY KEY (`id`),
  CONSTRAINT FK_multicompanyaccounting_company_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE CASCADE,
  CONSTRAINT FK_multicompanyaccounting_company_invoice_template_id FOREIGN KEY (`invoice_template_id`) REFERENCES `civicrm_msg_template`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;
