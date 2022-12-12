<?php

use CRM_Multicompanyaccounting_BAO_BatchOwnerOrganisation as BatchOwnerOrganisation;

class CRM_Multicompanyaccounting_Hook_PostProcess_FinancialBatch {

  private $form;

  public function __construct($form) {
    $this->form = $form;
  }

  public function run() {
    $this->updateBatchOwnerOrganisation();
  }

  private function updateBatchOwnerOrganisation() {
    $batchId = $this->form->_id;

    // In case of updating the owner organisations
    // in an existing batch, deleting all the existing
    // owner orgs is easier than trying to figure out which
    // should be kept and which should be removed.
    BatchOwnerOrganisation::deleteByBatchId($batchId);

    $submittedValues = $this->form->exportValues();
    if (empty($submittedValues['multicompanyaccounting_owner_org_id'])) {
      return;
    }

    $ownerOrganisationIds = explode(',', $submittedValues['multicompanyaccounting_owner_org_id']);
    foreach ($ownerOrganisationIds as $organisationId) {
      $params = [
        'batch_id' => $batchId,
        'owner_org_id' => $organisationId,
      ];
      BatchOwnerOrganisation::create($params);
    }
  }

}
