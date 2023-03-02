<?php

/**
 * @group headless
 */
class CRM_Multicompanyaccounting_Hook_ValidateForm_PriceSetValidatorTest extends BaseHeadlessTest {

  private $donationFinancialTypeId;

  private $eventFeeFinancialTypeId;

  private $memberDuesFinancialTypeId;

  public function setUp() {
    $this->donationFinancialTypeId = civicrm_api3('FinancialType', 'getvalue', [
      'return' => 'id',
      'name' => 'Donation',
    ]);
    $this->eventFeeFinancialTypeId = civicrm_api3('FinancialType', 'getvalue', [
      'return' => 'id',
      'name' => 'Event Fee',
    ]);
    $this->memberDuesFinancialTypeId = civicrm_api3('FinancialType', 'getvalue', [
      'return' => 'id',
      'name' => 'Member Dues',
    ]);

    // Set the owner for 'Donation' and 'Event Fee' to be the same, where
    // 'Member Dues' has different owner.
    $firstOwnerOrgId = $this->createCompany(1)['contact_id'];
    $secondOwnerOrgId = $this->createCompany(2)['contact_id'];
    $this->updateFinancialAccountOwner('Donation', $firstOwnerOrgId);
    $this->updateFinancialAccountOwner('Event Fee', $firstOwnerOrgId);
    $this->updateFinancialAccountOwner('Member Dues', $secondOwnerOrgId);
  }

  public function testUpdatePriceSetWithFinancialTypeWithOwnerThatMatchChildPriceFieldsFinancialTypeOwnersShowsNoValidationError() {
    $errors = [];
    $fields = [];

    $testPriceSetId = civicrm_api3('PriceSet', 'create', [
      'sequential' => 1,
      'title' => 'test',
      'extends' => 'CiviContribute',
      'financial_type_id' => 'Donation',
    ])['id'];

    civicrm_api3('PriceField', 'create', [
      'label' => 'test1',
      'name' => 'test1',
      'price_set_id' => $testPriceSetId,
      'html_type' => 'Text',
      'financial_type_id' => $this->donationFinancialTypeId,
      'option_label' => [1 => 'test1'],
      'option_weight' => [1 => 1],
      'option_amount' => [1 => 100],
    ]);

    $fields['financial_type_id'] = $this->eventFeeFinancialTypeId;
    $hook = new CRM_Multicompanyaccounting_Hook_ValidateForm_PriceSetValidator($fields, $errors, $testPriceSetId);
    $hook->validate();

    $this->assertEmpty($errors);
  }

  public function testUpdatePriceSetWithFinancialTypeWithOwnerThatDoesNotMatchChildPriceFieldsFinancialTypeOwnersShowsValidationError() {
    $errors = [];
    $fields = [];

    $testPriceSetId = civicrm_api3('PriceSet', 'create', [
      'sequential' => 1,
      'title' => 'test',
      'extends' => 'CiviContribute',
      'financial_type_id' => 'Donation',
    ])['id'];

    $pF = civicrm_api3('PriceField', 'create', [
      'label' => 'test1',
      'name' => 'test1',
      'price_set_id' => $testPriceSetId,
      'html_type' => 'Text',
      'financial_type_id' => $this->donationFinancialTypeId,
      'option_label' => [1 => 'test1'],
      'option_weight' => [1 => 1],
      'option_amount' => [1 => 100],
    ]);

    $fields['financial_type_id'] = $this->memberDuesFinancialTypeId;
    $hook = new CRM_Multicompanyaccounting_Hook_ValidateForm_PriceSetValidator($fields, $errors, $testPriceSetId);
    $hook->validate();

    $this->assertNotEmpty($errors['financial_type_id']);
  }

}
