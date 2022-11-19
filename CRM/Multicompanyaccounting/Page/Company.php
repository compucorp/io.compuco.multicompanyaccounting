<?php
use CRM_Multicompanyaccounting_ExtensionUtil as E;

class CRM_Multicompanyaccounting_Page_Company extends CRM_Core_Page {

  public function run() {
    $this->browse();

    parent::run();
  }

  public function browse() {
    $getQuery = 'SELECT mc.*, cc.display_name as company_name, mt.msg_title as invoice_template_name FROM multicompanyaccounting_company mc
                 LEFT JOIN civicrm_contact cc on cc.id = mc.contact_id
                 LEFT JOIN civicrm_msg_template mt ON mt.id = mc.invoice_template_id
                 ';
    $company = CRM_Core_DAO::executeQuery($getQuery);
    $rows = [];
    while ($company->fetch()) {
      $rows[$company->id] = $company->toArray();

      $rows[$company->id]['action'] = CRM_Core_Action::formLink(
        $this->generateActionLinks(),
        $this->calculateLinksMask(),
        ['id' => $company->id]
      );
    }

    $this->assign('rows', $rows);
  }

  private function generateActionLinks() {
    return [
      CRM_Core_Action::UPDATE  => [
        'name'  => ts('Edit'),
        'url'   => 'civicrm/admin/multicompanyaccounting/company/add',
        'qs'    => 'id=%%id%%&reset=1',
      ],
      CRM_Core_Action::DELETE => [
        'name' => ts('Delete'),
        'url' => 'civicrm/admin/multicompanyaccounting/company/delete',
        'qs' => 'id=%%id%%',
      ],
    ];
  }

  private function calculateLinksMask() {
    return array_sum(array_keys($this->generateActionLinks()));
  }

}
