<?php

use CRM_Multicompanyaccounting_BAO_BatchOwnerOrganisation as BatchOwnerOrganisation;
use CRM_Multicompanyaccounting_ExtensionUtil as ExtensionUtil;

class CRM_Multicompanyaccounting_Hook_BuildForm_FinancialBatch {

  private $form;

  public function __construct($form) {
    $this->form = $form;
  }

  public function run() {
    $this->addOwnerOrganisationField();
  }

  private function addOwnerOrganisationField() {
    // adds the owner org field element to the QuickForm instance
    $this->form->addEntityRef('multicompanyaccounting_owner_org_id', ts('Owner Organisation(s)'), [
      'api' => ['params' => ['contact_type' => 'Organization']],
      'select' => ['minimumInputLength' => 0],
      'placeholder' => ts('Select Owner Organisation(s)'),
      'multiple' => TRUE,
    ], FALSE);

    $this->setDefaultValueOnUpdateForm();

    // adds the HTML markup to the form
    $templatePath = ExtensionUtil::path() . '/templates';
    CRM_Core_Region::instance('page-body')->add([
      'template' => "{$templatePath}/CRM/Multicompanyaccounting/Hook/BuildForm/FinancialBatch.tpl",
    ]);
  }

  /**
   * Sets the owner organization field
   * default value in case it is the batch
   * update form.
   *
   * @return void
   */
  private function setDefaultValueOnUpdateForm() {
    if ($this->form->getAction() & CRM_Core_Action::UPDATE) {
      $batchId = $this->form->_id;
      $batchOwnerOrganisationIds = BatchOwnerOrganisation::getByBatchId($batchId);
      $defaults['multicompanyaccounting_owner_org_id'] = $batchOwnerOrganisationIds;
      $this->form->setDefaults($defaults);
    }
  }

}
