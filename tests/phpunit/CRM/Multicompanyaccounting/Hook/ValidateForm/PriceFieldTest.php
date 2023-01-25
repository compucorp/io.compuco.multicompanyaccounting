<?php

/**
 * @group headless
 */
class CRM_Multicompanyaccounting_Hook_ValidateForm_PriceFieldTest extends BaseHeadlessTest {

  private $testPriceSetId;

  private $donationFinancialTypeId;

  private $memberDuesFinancialTypeId;

  public function setUp() {
    $this->testPriceSetId = civicrm_api3('PriceSet', 'create', [
      'sequential' => 1,
      'title' => 'test',
      'extends' => 'CiviContribute',
      'financial_type_id' => 'Donation',
    ])['id'];

    $this->donationFinancialTypeId = civicrm_api3('FinancialType', 'getvalue', [
      'return' => 'id',
      'name' => 'Donation',
    ]);

    $this->memberDuesFinancialTypeId = civicrm_api3('FinancialType', 'getvalue', [
      'return' => 'id',
      'name' => 'Member Dues',
    ]);

    // We set the owner of the two financial types used in tests to match by default.
    $firstOwnerOrgId = $this->createCompany(1)['contact_id'];
    $this->updateFinancialAccountOwner('Donation', $firstOwnerOrgId);
    $this->updateFinancialAccountOwner('Member Dues', $firstOwnerOrgId);
  }

  public function testCreatingTextPriceFieldWithOwnerAccountMatchesThePriceSetWillPassValidation() {
    $form = NULL;
    $fields = [];
    $errors = [];

    $fields['html_type'] = 'Text';
    $fields['financial_type_id'] = $this->memberDuesFinancialTypeId;

    $hook = new CRM_Multicompanyaccounting_Hook_ValidateForm_PriceField($fields, $errors, $this->testPriceSetId);
    $hook->validate();
    $this->assertEmpty($errors);
  }

  public function testCreatingTextPriceFieldWithOwnerAccountNotMatchingThePriceSetWillFailValidation() {
    $form = NULL;
    $fields = [];
    $errors = [];

    $fields['html_type'] = 'Text';
    $secondOwnerOrgId = $this->createCompany(2)['contact_id'];
    $this->updateFinancialAccountOwner('Member Dues', $secondOwnerOrgId);
    $fields['financial_type_id'] = $this->memberDuesFinancialTypeId;

    $hook = new CRM_Multicompanyaccounting_Hook_ValidateForm_PriceField($fields, $errors, $this->testPriceSetId);
    $hook->validate();
    $this->assertNotEmpty($errors['financial_type_id']);
  }

  public function testCreatingMultiOptionsPriceFieldWithOwnerAccountsMatchesThePriceSetWillPassValidation() {
    $form = NULL;
    $fields = [];
    $errors = [];

    $fields['html_type'] = 'Select';

    $fields['option_label'][1] = 'test1';
    $fields['option_financial_type_id'][1] = $this->donationFinancialTypeId;
    $fields['option_label'][2] = 'test2';
    $fields['option_financial_type_id'][2] = $this->memberDuesFinancialTypeId;

    $hook = new CRM_Multicompanyaccounting_Hook_ValidateForm_PriceField($fields, $errors, $this->testPriceSetId);
    $hook->validate();
    $this->assertEmpty($errors);
  }

  public function testCreatingMultiOptionsPriceFieldWithOwnerAccountsNotMatchingThePriceSetWillFailValidation() {
    $form = NULL;
    $fields = [];
    $errors = [];

    $fields['html_type'] = 'Select';

    $fields['option_label'][1] = 'test1';
    $fields['option_financial_type_id'][1] = $this->donationFinancialTypeId;

    $secondOwnerOrgId = $this->createCompany(2)['contact_id'];
    $this->updateFinancialAccountOwner('Member Dues', $secondOwnerOrgId);
    $fields['option_label'][2] = 'test2';
    $fields['option_financial_type_id'][2] = $this->memberDuesFinancialTypeId;

    $hook = new CRM_Multicompanyaccounting_Hook_ValidateForm_PriceField($fields, $errors, $this->testPriceSetId);
    $hook->validate();
    $this->assertNotEmpty($errors['financial_type_id']);
  }

}
