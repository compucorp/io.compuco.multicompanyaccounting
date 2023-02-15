<?php

class CRM_Multicompanyaccounting_Hook_BuildForm_FinancialAccount {

  private $form;

  public function __construct($form) {
    $this->form = $form;
  }

  public function run() {
    $this->restrictOwnerFieldToCompanyOrgnisations();
  }

  /**
   * Restrict the Owner field
   * for the finiancial account form,
   * only legal entity
   *
   * @return void
   * @throws CRM_Core_Exception
   */
  private function restrictOwnerFieldToCompanyOrgnisations() {
    $element = $this->form->getElement('contact_id');
    $element->setAttribute('data-api-entity', 'Company');
    $element->setAttribute('data-api-params', json_encode([
      'search_field' => 'contact_id.organization_name',
      'label_field' => 'contact_id.organization_name',
      'id_field' => 'contact_id',
    ]));
    $element->setAttribute('data-select-params', json_encode(['minimumInputLength' => 0]));
  }

}
