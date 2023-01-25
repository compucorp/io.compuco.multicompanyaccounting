<?php

/**
 * Form Validation on adding/editing price field or price field option.
 */
class CRM_Multicompanyaccounting_Hook_ValidateForm_PriceField {

  private $form;
  private $fields;
  private $errors;

  public function __construct(&$form, &$fields, &$errors) {
    $this->form = $form;
    $this->fields = &$fields;
    $this->errors = &$errors;
  }

  public function validate() {
    $this->validateConsistentIncomeAccountOwner();
  }

  /**
   * Validates if the owner organization of the income
   * account for the selected financial type(s), match
   * the owner of the income account for the parent
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

    return $this->getFinancialTypesOwnerOrganizationIds($selectedFinancialTypes);
  }

  /**
   * Gets the financial types list
   * that are selected by the user
   * on the form.
   *
   * @return string
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

    return implode(',', array_unique($selectedFinancialTypes));
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
    $priceSetId = CRM_Utils_Request::retrieve('sid', 'Positive');
    $priceSetFinancialTypeId = civicrm_api3('PriceSet', 'getvalue', [
      'return' => 'financial_type_id',
      'id' => $priceSetId,
    ]);

    return $this->getFinancialTypesOwnerOrganizationIds($priceSetFinancialTypeId)[0];
  }

  private function getFinancialTypesOwnerOrganizationIds($financialTypes) {
    $orgIds = [];

    $incomeAccountRelationId = key(CRM_Core_PseudoConstant::accountOptionValues('account_relationship', NULL, " AND v.name LIKE 'Income Account is' "));
    $query = "SELECT fa.contact_id FROM civicrm_entity_financial_account efa
              INNER JOIN civicrm_financial_account fa ON efa.financial_account_id = fa.id
              WHERE efa.entity_id IN ({$financialTypes}) AND efa.entity_table = 'civicrm_financial_type' AND efa.account_relationship = {$incomeAccountRelationId}";
    $results = CRM_Core_DAO::executeQuery($query);
    while ($results->fetch()) {
      $orgIds[] = $results->contact_id;
    }

    return array_unique($orgIds);
  }

}
