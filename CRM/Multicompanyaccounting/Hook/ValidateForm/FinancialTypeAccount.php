<?php

/**
 * Form Validation on editing or assign a financial account for a financial type
 */
class CRM_Multicompanyaccounting_Hook_ValidateForm_FinancialTypeAccount {

  private $form;
  private $errors;
  private $fields;

  public function __construct(&$form, &$errors, &$fields) {
    $this->form = $form;
    $this->errors = &$errors;
    $this->fields = &$fields;
  }

  public function validate() {
    $this->validateFinancialAccountOrganisation();
  }

  /**
   * Validates the financial type account form
   * when editing or assigning a new account
   */
  private function validateFinancialAccountOrganisation() {
    $isAddAction = $this->form->getAction() === CRM_Core_Action::ADD;
    $isUpdateAction = $this->form->getAction() === CRM_Core_Action::UPDATE;
    $isFormActionToValidate = $isAddAction || $isUpdateAction;
    if (!$isFormActionToValidate) {
      return;
    }

    $financialTypeId = $this->form->getVar('_aid');
    $existingFinancialAccountsData = $this->getExistingFinancialTypeAccountsCountAndOwner($financialTypeId);
    $selectedFinancialAccountOwnerOrganisationId = $this->getSelectedFinancialAccountOwnerOrganisationId();

    // Allowing changing the owner organisation if there is only one financial account.
    if ($isUpdateAction && $existingFinancialAccountsData['accounts_count'] === 1) {
      return;
    }
    // Allow add financial account type if there is no assigned financial accounts
    if ($isAddAction && $existingFinancialAccountsData['accounts_count'] === 0) {
      return;
    }

    if ($selectedFinancialAccountOwnerOrganisationId !== $existingFinancialAccountsData['owner_organisation_id']) {
      $this->errors['financial_account_id'] = ts('You cannot have multiple Owner for a Financial Type');
    }
  }

  /**
   * Gets the Financial Accounts based on financial type id sent by the form
   */
  private function getExistingFinancialTypeAccountsCountAndOwner($financial_type_id) {
    $result = \Civi\Api4\EntityFinancialAccount::get()
      ->addSelect('COUNT(*) AS count', 'financial_account.contact_id')
      ->setJoin([['FinancialAccount AS financial_account', 'INNER', NULL, ['financial_account_id', '=', 'financial_account.id']]])
      ->setGroupBy(['financial_account.contact_id'])
      ->addWhere('entity_table', '=', 'civicrm_financial_type')
      ->addWhere('entity_id', '=', $financial_type_id)
      ->execute()
      ->first();

    return [
      'accounts_count' => $result['count'] ?? 0,
      'owner_organisation_id' => $result['financial_account.contact_id'] ?? 0,
    ];
  }

  /**
   * Gets the owner of the financial account from the submitted fields
   */
  private function getSelectedFinancialAccountOwnerOrganisationId() {
    $result = \Civi\Api4\FinancialAccount::get()
      ->addSelect('contact_id')
      ->addWhere('id', '=', $this->fields['financial_account_id'])
      ->execute()
      ->first();

    return $result['contact_id'];
  }

}
