<?php

use CRM_Multicompanyaccounting_Hook_ValidateForm_OwnerOrganizationRetriever as OwnerOrganizationRetriever;

/**
 * Form Validation on line item edit form, that is provided by
 * Lineitemedit extension.
 */
class CRM_Multicompanyaccounting_Hook_ValidateForm_LineItemEdit {

  private $lineItemId;
  private $errors;
  private $fields;

  public function __construct($lineItemId, &$errors, &$fields) {
    $this->lineItemId = $lineItemId;
    $this->errors = &$errors;
    $this->fields = &$fields;
  }

  public function validate() {
    $this->validateConsistentIncomeAccountOwners();
  }

  /**
   * Validates if the financial type owner
   * for the line item being edited matches
   * the original line item financial type owner.
   *
   * @return void
   */
  private function validateConsistentIncomeAccountOwners() {
    $selectedFinancialTypeId = $this->fields['financial_type_id'];
    $currentFinancialTypeId = civicrm_api3('LineItem', 'getvalue', [
      'return' => 'financial_type_id',
      'id' => $this->lineItemId,
    ]);

    $financialTypeOwners = OwnerOrganizationRetriever::getFinancialTypesOwnerOrganizationIds([$currentFinancialTypeId, $selectedFinancialTypeId]);
    if (count($financialTypeOwners) > 1) {
      $this->errors['financial_type_id'] = 'It is not possible to make the proposed changes to this line item as the owner organisation of the contribution is not connected to the financial type of the proposed line item. Please update the financial types.';
    }
  }

}
