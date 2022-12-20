<?php

/**
 * Form Validation on editing or assign a financial account for a type
 */
class CRM_Multicompanyaccounting_Hook_ValidateForm_FinancialTypeAccount {

  private $form;
  private $fields;
  private $errors;

  public function __construct(&$form, &$fields, &$errors) {
    $this->form = $form;
    $this->fields = &$fields;
    $this->errors = &$errors;
  }

  /**
   * Validates the financial type account form
   * when editing or assigning a new account
   */
  public function validate() {
    $this->validateFinancialAccountOrangistion();
  }

  /**
   * Gets the owner of the financial account from the submitted fields
   */
  private function getOwnerContactIdFromFinancialAccountField() {
    $result = \Civi\Api4\FinancialAccount::get()
      ->addSelect('contact_id')
      ->addWhere('id', '=', $this->fields['financial_account_id'])
      ->execute()
      ->first();

    return $result['contact_id'];
  }

  /**
   * Gets the Financial Accounts based on financial type id sent by the form
   */
  private function getFinancialAccountByFinancialTypeId($financial_type_id) {

    $result = \Civi\Api4\EntityFinancialAccount::get()
      ->addSelect('COUNT(*) AS count', 'financial_account.contact_id')
      ->setJoin([['FinancialAccount AS financial_account', 'INNER', NULL, ['financial_account_id', '=', 'financial_account.id']]])
      ->setGroupBy(['financial_account.contact_id'])
      ->addWhere('entity_table', '=', 'civicrm_financial_type')
      ->addWhere('entity_id', '=', $financial_type_id)
      ->execute()
      ->first();

    return [
      'count' => $result['count'],
      'owner' => $result['financial_account.contact_id'],
    ];
  }

  private function validateFinancialAccountOrangistion() {
    if ($this->form->getAction() === CRM_Core_Action::UPDATE || $this->form->getAction() === CRM_Core_Action::ADD) {
      $financialTypeId = $this->form->getVar('_aid');
      $contactId = $this->getOwnerContactIDFromFinancialAccountField();
      $financialTypeAccounts = $this->getFinancialAccountByFinancialTypeId($financialTypeId);

      if ($contactId !== $financialTypeAccounts['owner']) {
        $errorFlag = TRUE;
        if ($financialTypeAccounts['count'] <= 1 && $this->form->getAction() === CRM_Core_Action::UPDATE) {
          $errorFlag = FALSE;
        }
      }
      if ($errorFlag) {
        $this->errors['financial_account_id'] = ts('You cannot have multiple Owner for a Financial Type');
      }
    }
  }

}
