<?php

/**
 * @group headless
 */
class CRM_Multicompanyaccounting_Hook_AlterMailParams_InvoiceTemplateTest extends BaseHeadlessTest {

  public function testStandardInvoiceTemplateWillBeReplacedByContributionOwnerOrganisationTemplate() {
    $organization = $this->createOrganization('testorg1');
    $orgOneinvoiceTemplateId = $this->createMessageTemplate('testorg1');
    $orgOneCompany = CRM_Multicompanyaccounting_BAO_Company::create(['contact_id' => $organization['id'], 'invoice_template_id' => $orgOneinvoiceTemplateId]);
    $firstOrgContribution = civicrm_api3('Contribution', 'create', [
      'financial_type_id' => 'Donation',
      'receive_date' => '2022-11-11',
      'total_amount' => 100,
      'contact_id' => 1,
    ]);
    CRM_Multicompanyaccounting_CustomGroup_ContributionOwnerOrganisation::setOwnerOrganisation($firstOrgContribution['id'], $orgOneCompany->contact_id);

    $templateParams['messageTemplateID'] = NULL;
    $templateParams['tplParams']['id'] = $firstOrgContribution['id'];
    $alterInvoiceParams = new CRM_Multicompanyaccounting_Hook_AlterMailParams_InvoiceTemplate($templateParams);
    $alterInvoiceParams->run();

    $this->assertEquals($orgOneinvoiceTemplateId, $templateParams['messageTemplateID']);
  }

  public function testDomainTokensWillBeReplacedByOwnerOrganisationDetails() {
    $organization = $this->createOrganization('testorg1');
    $orgOneInvoiceTemplateId = $this->createMessageTemplate('testorg1');

    // set owner organisation address
    $addressParams = [
      'contact_id' => $organization['id'],
      'location_type_id' => 'Billing',
      'is_primary' => 1,
      'street_address' => 'teststreet',
      'supplemental_address_1' => 'testsupp1',
      'supplemental_address_2' => 'testsupp2',
      'supplemental_address_3' => 'testsupp3',
      'city' => 'testcity',
      'postal_code' => '0056',
      'country_id' => 'GB',
      'state_province_id' => 'Aberdeen City',
    ];
    civicrm_api3('Address', 'create', $addressParams);

    // set owner organisation email
    civicrm_api3('Email', 'create', [
      'contact_id' => $organization['id'],
      'email' => 'testorg1@example.com',
    ]);

    // set owner organisation phone
    civicrm_api3('Phone', 'create', [
      'contact_id' => $organization['id'],
      'phone' => '079000005',
    ]);

    $orgOneCompany = CRM_Multicompanyaccounting_BAO_Company::create(['contact_id' => $organization['id'], 'invoice_template_id' => $orgOneInvoiceTemplateId]);
    $firstOrgContribution = civicrm_api3('Contribution', 'create', [
      'financial_type_id' => 'Donation',
      'receive_date' => '2022-11-11',
      'total_amount' => 100,
      'contact_id' => 1,
    ]);
    CRM_Multicompanyaccounting_CustomGroup_ContributionOwnerOrganisation::setOwnerOrganisation($firstOrgContribution['id'], $orgOneCompany->contact_id);

    $templateParams['tplParams'] = NULL;
    $templateParams['tplParams']['id'] = $firstOrgContribution['id'];
    $alterInvoiceParams = new CRM_Multicompanyaccounting_Hook_AlterMailParams_InvoiceTemplate($templateParams);
    $alterInvoiceParams->run();
    unset($templateParams['tplParams']['id']);

    $expectedParams = [
      'domain_organization' => 'testorg1',
      'domain_logo' => '',
      'domain_street_address' => 'teststreet',
      'domain_supplemental_address_1' => 'testsupp1',
      'domain_supplemental_address_2' => 'testsupp2',
      'domain_supplemental_address_3' => 'testsupp3',
      'domain_city' => 'testcity',
      'domain_postal_code' => '0056',
      'domain_state' => 'ABE',
      'domain_country' => 'United Kingdom',
      'domain_email' => 'testorg1@example.com',
      'domain_phone' => '079000005',
    ];
    $this->assertEquals($expectedParams, $templateParams['tplParams']);
  }

  public function testDomainLogoTokenWillResolveToTheOrganisationImageURL() {
    $fakeOrganisationImageURL = 'https://example.com/test1/test2/image.png';
    $organization = civicrm_api3('Contact', 'create', [
      'sequential' => 1,
      'contact_type' => 'Organization',
      'organization_name' => 'testorg1',
      'image_URL' => $fakeOrganisationImageURL,
    ])['values'][0];
    $orgOneInvoiceTemplateId = $this->createMessageTemplate('testorg1');

    $orgOneCompany = CRM_Multicompanyaccounting_BAO_Company::create(['contact_id' => $organization['id'], 'invoice_template_id' => $orgOneInvoiceTemplateId]);
    $firstOrgContribution = civicrm_api3('Contribution', 'create', [
      'financial_type_id' => 'Donation',
      'receive_date' => '2022-11-11',
      'total_amount' => 100,
      'contact_id' => 1,
    ]);
    CRM_Multicompanyaccounting_CustomGroup_ContributionOwnerOrganisation::setOwnerOrganisation($firstOrgContribution['id'], $orgOneCompany->contact_id);

    $templateParams['tplParams'] = NULL;
    $templateParams['tplParams']['id'] = $firstOrgContribution['id'];
    $alterInvoiceParams = new CRM_Multicompanyaccounting_Hook_AlterMailParams_InvoiceTemplate($templateParams);
    $alterInvoiceParams->run();

    $this->assertEquals($fakeOrganisationImageURL, $templateParams['tplParams']['domain_logo']);
  }

  private function createMessageTemplate($invoiceName) {
    return civicrm_api3('MessageTemplate', 'create', [
      'sequential' => 1,
      'msg_title' => $invoiceName,
      'workflow_name' => $invoiceName,
      'is_active' => 1,
    ])['id'];
  }

}
