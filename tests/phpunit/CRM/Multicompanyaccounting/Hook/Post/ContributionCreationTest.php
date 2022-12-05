<?php

/**
 * @group headless
 */
class CRM_Multicompanyaccounting_Hook_Post_ContributionCreationTest extends BaseHeadlessTest {

  public function testOwnerOrganizationIsSetToFirstLineItemIncomeAccountOwner() {
    $firstOrg = $this->createOrganization('testorg1');
    $firstFinancialType = $this->updateFinancialAccountOwner('Donation', $firstOrg['id']);

    $secondOrg = $this->createOrganization('testorg2');
    $secondFinancialType = $this->updateFinancialAccountOwner('Member Dues', $secondOrg['id']);

    $params = [
      'price_field_id' => 1,
      'label' => 'Price Field 2',
      'amount' => 100,
      'financial_type_id' => 'Donation',
    ];
    $secondPriceFieldValue = civicrm_api3('PriceFieldValue', 'create', $params);

    // Creating order with two line items,
    // where each line item has different
    // financial_type_id
    $orderParams = [
      'contact_id' => 1,
      'financial_type_id' => 'Donation',
      'contribution_status_id' => 'Pending',
      'line_items' => [
        '0' => [
          'line_item' => [
            '0' => [
              'price_field_id' => '1',
              'price_field_value_id' => '1',
              'label' => 'Price Field 1',
              'field_title' => 'Price Field 1',
              'qty' => 1,
              'unit_price' => '100',
              'line_total' => '100',
              'financial_type_id' => $secondFinancialType['id'],
              'entity_table' => 'civicrm_contribution',
            ],
          ],
        ],
        '1' => [
          'line_item' => [
            '0' => [
              'price_field_id' => '1',
              'price_field_value_id' => $secondPriceFieldValue['id'],
              'label' => 'Price Field 2',
              'field_title' => 'Price Field 2',
              'qty' => 1,
              'unit_price' => '100',
              'line_total' => '100',
              'financial_type_id' => $firstFinancialType['id'],
              'entity_table' => 'civicrm_contribution',
            ],
          ],
        ],
      ],
    ];
    $order = civicrm_api3('Order', 'create', $orderParams);

    $contributionId = key($order['values']);
    $contributionOwnerOrgId = $this->getContributionOwnerOrgId($contributionId);

    $this->assertEquals($secondOrg['id'], $contributionOwnerOrgId);
  }

  private function createOrganization($orgName) {
    return civicrm_api3('Contact', 'create', [
      'sequential' => 1,
      'contact_type' => 'Organization',
      'organization_name' => $orgName,
    ])['values'][0];
  }

  private function updateFinancialAccountOwner($accountName, $newOwnerId) {
    return civicrm_api3('FinancialAccount', 'get', [
      'sequential' => 1,
      'name' => $accountName,
      'api.FinancialAccount.create' => ['id' => '$value.id', 'contact_id' => $newOwnerId],
    ])['values'][0];
  }

  private function getContributionOwnerOrgId($contributionId) {
    $ownerOrgCgId = civicrm_api3('CustomField', 'getvalue', [
      'return' => 'id',
      'custom_group_id' => 'multicompanyaccounting_contribution_owner',
      'name' => 'owner_organization',
    ]);

    return civicrm_api3('Contribution', 'getvalue', [
      'return' => "custom_$ownerOrgCgId",
      'id' => $contributionId,
    ]);
  }

}
