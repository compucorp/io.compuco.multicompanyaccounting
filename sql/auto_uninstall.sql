---- /*******************************************************
-- *
-- * Clean up the existing tables-- *
-- *******************************************************/
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `multicompanyaccounting_company`;
DROP TABLE IF EXISTS `multicompanyaccounting_batch_owner_org`;

SET FOREIGN_KEY_CHECKS=1;
