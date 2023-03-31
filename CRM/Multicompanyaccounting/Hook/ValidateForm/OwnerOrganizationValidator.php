<?php

use CRM_Multicompanyaccounting_Hook_ValidateForm_OwnerOrganizationRetriever as OwnerOrganizationRetriever;

/**
 * Owner Organization Form Validation
 */
class CRM_Multicompanyaccounting_Hook_ValidateForm_OwnerOrganizationValidator {

  private $fields;
  private $errors;
  private $priceSetId;

  public function __construct(&$fields, &$errors, $priceSetId) {
    $this->fields = &$fields;
    $this->errors = &$errors;
    $this->priceSetId = $priceSetId;
  }

  public function validate() {
    $this->validateConsistentIncomeAccountOwner();
  }

  /**
   * Validates if the owner organization of the income
   * account for the selected financial type(s), match
   * the owner of the income account for the
   * price set financial type.
   *
   * @return void
   */
  private function validateConsistentIncomeAccountOwner() {
    $selectedFinancialTypesOwnerOrganizations = $this->getSelectedFinancialTypesOwnerOrganizations();
    if (empty($selectedFinancialTypesOwnerOrganizations)) {
      return;
    }

    if (count($selectedFinancialTypesOwnerOrganizations) > 1) {
      $this->errors['financial_type_id'] = 'The owner of the income account for the financial types you selected do not match';
      return;
    }

    $priceSetOwnerOrganization = $this->getPriceSetOwnerOrganization();
    if ($selectedFinancialTypesOwnerOrganizations[0] != $priceSetOwnerOrganization) {
      $this->errors['financial_type_id'] = 'The owner of the income account for the financial type you selected, does not match the owner of the income account for price set financial type.';
    }
  }

  private function getSelectedFinancialTypesOwnerOrganizations() {
    $selectedFinancialTypes = $this->getSelectedFinancialTypes();
    if (empty($selectedFinancialTypes)) {
      return [];
    }

    return OwnerOrganizationRetriever::getFinancialTypesOwnerOrganizationIds($selectedFinancialTypes);
  }

  /**
   * Gets the financial types list
   * that are selected by the user
   * on the form.
   *
   * @return array
   */
  private function getSelectedFinancialTypes() {
    $selectedFinancialTypes = [];
    if (!empty($this->fields['html_type']) && $this->fields['html_type'] != 'Text') {
      foreach ($this->fields['option_label'] as $index => $optionLabel) {
        if (!empty($optionLabel)) {
          $selectedFinancialTypes[] = $this->fields['option_financial_type_id'][$index];
        }
      }
    }
    else {
      $selectedFinancialTypes = [$this->fields['financial_type_id']];
    }

    return $selectedFinancialTypes;
  }

  /**
   * Gets the owner organization
   * for the income account associated
   * with financial type of the parent
   * price set of this price field.
   *
   * @return string|null
   */
  private function getPriceSetOwnerOrganization() {
    $priceSetFinancialTypeId = civicrm_api3('PriceSet', 'getvalue', [
      'return' => 'financial_type_id',
      'id' => $this->priceSetId,
    ]);

    return OwnerOrganizationRetriever::getFinancialTypesOwnerOrganizationIds([$priceSetFinancialTypeId])[0];
  }

}
