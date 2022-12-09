<?php

class CRM_Multicompanyaccounting_Hook_BuildForm_BatchTransaction {

  private $form;

  public function __construct($form) {
    $this->form = $form;
  }

  public function run() {
    $this->setTransactionsOwnerOrganisationFilterValue();
  }

  /**
   * Sets the Owner Organisation filter value
   * for the transactions, based on the batch
   * owner organisations.
   *
   * @return void
   * @throws CRM_Core_Exception
   */
  private function setTransactionsOwnerOrganisationFilterValue() {
    $batchId = CRM_Utils_Request::retrieve('bid', 'Int');
    if (empty($batchId)) {
      return;
    }

    $batchOwnerOrganisationIds = CRM_Multicompanyaccounting_BAO_BatchOwnerOrganisation::getByBatchId($batchId);

    $fieldName = 'custom_' . $this->getContributionOwnerOrganisationFieldId();
    $defaults[$fieldName] = $batchOwnerOrganisationIds;
    $this->form->setDefaults($defaults);
  }

  private function getContributionOwnerOrganisationFieldId() {
    return civicrm_api3('CustomField', 'getvalue', [
      'return' => 'id',
      'custom_group_id' => 'multicompanyaccounting_contribution_owner',
      'name' => 'owner_organization',
    ]);
  }

}
