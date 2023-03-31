<?php

use CRM_Multicompanyaccounting_ExtensionUtil as ExtensionUtil;

class CRM_Multicompanyaccounting_Hook_BuildForm_FinancialBatchSearch {

  private $form;

  public function __construct($form) {
    $this->form = $form;
  }

  public function run() {
    $this->addOwnerOrganisationFilterField();
  }

  private function addOwnerOrganisationFilterField() {
    // adds the owner org field element to the QuickForm instance
    $this->form->addEntityRef('multicompanyaccounting_owner_org_id', ts('Owner Organisation(s)'), [
      'api' => ['params' => ['contact_type' => 'Organization']],
      'select' => ['minimumInputLength' => 0],
      'placeholder' => ts('Select Owner Organisation(s)'),
      'multiple' => TRUE,
    ], FALSE);

    // adds the HTML markup to the form
    $templatePath = ExtensionUtil::path() . '/templates';
    CRM_Core_Region::instance('page-body')->add([
      'template' => "{$templatePath}/CRM/Multicompanyaccounting/Hook/BuildForm/FinancialBatchSearch.tpl",
    ]);
  }

}
