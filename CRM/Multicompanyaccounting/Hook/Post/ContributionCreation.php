<?php

class CRM_Multicompanyaccounting_Hook_Post_ContributionCreation {

  private $contributionId;

  public function __construct($contributionId) {
    $this->contributionId = $contributionId;
  }

  public function run() {
    $this->updateOwnerOrganization();
  }

  private function updateOwnerOrganization() {
    $ownerOrganizationId = $this->getOwnerOrganizationId();
    if (!empty($ownerOrganizationId)) {
      $updateQuery = "INSERT INTO civicrm_value_multicompanyaccounting_ownerorg (entity_id, owner_organization)
                    VALUES ({$this->contributionId}, {$ownerOrganizationId})
                    ON DUPLICATE KEY UPDATE owner_organization = {$ownerOrganizationId}";
      CRM_Core_DAO::executeQuery($updateQuery);
    }
  }

  /**
   * Gets the owner organization Id,
   * which comes from the owner of the
   * first line item income account.
   *
   * @return string|null
   */
  private function getOwnerOrganizationId() {
    $incomeAccountRelationId = key(CRM_Core_PseudoConstant::accountOptionValues('account_relationship', NULL, " AND v.name LIKE 'Income Account is' "));
    $OwnerOrgQuery = "SELECT fa.contact_id FROM civicrm_line_item li
                      INNER JOIN civicrm_entity_financial_account efa ON li.financial_type_id = efa.entity_id AND efa.entity_table = 'civicrm_financial_type'
                      INNER JOIN civicrm_financial_account fa ON efa.financial_account_id = fa.id
                      WHERE efa.account_relationship = {$incomeAccountRelationId} AND li.contribution_id = {$this->contributionId}
                      LIMIT 1";
    return CRM_Core_DAO::singleValueQuery($OwnerOrgQuery);
  }

}
