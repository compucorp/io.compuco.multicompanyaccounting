<?php

use CRM_Multicompanyaccounting_Hook_ValidateForm_OwnerOrganizationRetriever as OwnerOrganizationRetriever;

/**
 * Form Validation on contribution form.
 */
class CRM_Multicompanyaccounting_Hook_ValidateForm_Contribution {

  private $contributionId;
  private $errors;
  private $fields;

  public function __construct($contributionId, &$errors, &$fields) {
    $this->contributionId = $contributionId;
    $this->errors = &$errors;
    $this->fields = &$fields;
  }

  public function validate() {
    $this->validateConsistentIncomeAccountOwners();
  }

  /**
   * Validates if the selected contribution
   * financial type owner, or any of the
   * added line item financial type owners matches
   * the original contribution financial type owner.
   *
   * This is to prevent users from changing the owner
   * organization of the contribution, or form them
   * to have line items with inconsistent owners.
   *
   * @return void
   */
  private function validateConsistentIncomeAccountOwners() {
    $selectedFinancialTypeId = $this->fields['financial_type_id'];
    $currentFinancialTypeId = civicrm_api3('Contribution', 'getvalue', [
      'return' => 'financial_type_id',
      'id' => $this->contributionId,
    ]);

    $formFinancialTypeIds = [$currentFinancialTypeId, $selectedFinancialTypeId];
    if (!empty($this->fields['item_financial_type_id'])) {
      $lineItemsFinancialTypes = array_filter($this->fields['item_financial_type_id']);
      $formFinancialTypeIds = array_merge($formFinancialTypeIds, $lineItemsFinancialTypes);
    }

    $formFinancialTypeOwners = OwnerOrganizationRetriever::getFinancialTypesOwnerOrganizationIds($formFinancialTypeIds);
    if (count($formFinancialTypeOwners) > 1) {
      $this->errors['financial_type_id'] = 'It is not possible to make the proposed changes to this contribution as the owner organisation of the contribution is not connected to the financial type of the proposed new line items. Please update the financial types.';
    }
  }

}
