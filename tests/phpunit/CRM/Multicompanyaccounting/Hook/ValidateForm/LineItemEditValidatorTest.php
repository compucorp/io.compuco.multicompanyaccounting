<?php

/**
 * @group headless
 */
class CRM_Multicompanyaccounting_Hook_ValidateForm_LineItemEditValidatorTest extends BaseHeadlessTest {

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

    $firstOwnerOrgId = $this->createCompany(1)['contact_id'];
    $secondOwnerOrgId = $this->createCompany(2)['contact_id'];
    $this->updateFinancialAccountOwner('Donation', $firstOwnerOrgId);
    $this->updateFinancialAccountOwner('Event Fee', $firstOwnerOrgId);
    $this->updateFinancialAccountOwner('Member Dues', $secondOwnerOrgId);
  }

  public function testAllowChangingLineItemToFinancialTypeWithSameOwnerOrganization() {
    $errors = [];
    $fields = [];

    $testLineItem = civicrm_api3('LineItem', 'create', array(
      'qty' => 1,
      'entity_table' => 'civicrm_contribution',
      'entity_id' => 1,
      'financial_type_id' => $this->donationFinancialTypeId,
      'unit_price' => 100,
      'line_total' => 100,
    ));

    $fields['financial_type_id'] = $this->eventFeeFinancialTypeId;
    $hook = new CRM_Multicompanyaccounting_Hook_ValidateForm_LineItemEdit($testLineItem['id'], $errors, $fields);
    $hook->validate();

    $this->assertEmpty($errors);
  }

  public function testPreventChangingLineItemToFinancialTypeWithDifferentOwnerOrganization() {
    $errors = [];
    $fields = [];

    $testLineItem = civicrm_api3('LineItem', 'create', array(
      'qty' => 1,
      'entity_table' => 'civicrm_contribution',
      'entity_id' => 1,
      'financial_type_id' => $this->donationFinancialTypeId,
      'unit_price' => 100,
      'line_total' => 100,
    ));

    $fields['financial_type_id'] = $this->memberDuesFinancialTypeId;
    $hook = new CRM_Multicompanyaccounting_Hook_ValidateForm_LineItemEdit($testLineItem['id'], $errors, $fields);
    $hook->validate();

    $this->assertNotEmpty($errors['financial_type_id']);
  }

}
