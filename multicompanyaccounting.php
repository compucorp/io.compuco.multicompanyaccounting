<?php

require_once 'multicompanyaccounting.civix.php';
// phpcs:disable
use CRM_Multicompanyaccounting_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function multicompanyaccounting_civicrm_config(&$config) {
  _multicompanyaccounting_civix_civicrm_config($config);

  Civi::dispatcher()->addListener('civi.api.prepare', ['CRM_Multicompanyaccounting_Hook_Config_APIWrapper_BatchListPage', 'preApiCall']);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function multicompanyaccounting_civicrm_xmlMenu(&$files) {
  _multicompanyaccounting_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function multicompanyaccounting_civicrm_install() {
  _multicompanyaccounting_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function multicompanyaccounting_civicrm_postInstall() {
  _multicompanyaccounting_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function multicompanyaccounting_civicrm_uninstall() {
  _multicompanyaccounting_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function multicompanyaccounting_civicrm_enable() {
  _multicompanyaccounting_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function multicompanyaccounting_civicrm_disable() {
  _multicompanyaccounting_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function multicompanyaccounting_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _multicompanyaccounting_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function multicompanyaccounting_civicrm_managed(&$entities) {
  _multicompanyaccounting_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Add CiviCase types provided by this extension.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function multicompanyaccounting_civicrm_caseTypes(&$caseTypes) {
  _multicompanyaccounting_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Add Angular modules provided by this extension.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function multicompanyaccounting_civicrm_angularModules(&$angularModules) {
  // Auto-add module files from ./ang/*.ang.php
  _multicompanyaccounting_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function multicompanyaccounting_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _multicompanyaccounting_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function multicompanyaccounting_civicrm_entityTypes(&$entityTypes) {
  _multicompanyaccounting_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_themes().
 */
function multicompanyaccounting_civicrm_themes(&$themes) {
  _multicompanyaccounting_civix_civicrm_themes($themes);
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu/
 */
function multicompanyaccounting_civicrm_navigationMenu(&$menu) {
  $companyMenuItem = [
    'name' => 'multicompanyaccounting_company',
    'label' => ts('Companies (Multi-company accounting)'),
    'url' => 'civicrm/admin/multicompanyaccounting/company',
    'permission' => 'administer CiviCRM',
    'separator' => 2,
  ];

  _membershipextras_civix_insert_navigation_menu($menu, 'Administer/CiviContribute', $companyMenuItem);
}

/**
 * Implements hook_civicrm_alterMailParams().
 */
function multicompanyaccounting_civicrm_alterMailParams(&$params, $context) {
  // 'contribution_invoice_receipt' is CiviCRM standard invoice template
  if (empty($params['valueName']) || $params['valueName'] != 'contribution_invoice_receipt') {
    return;
  }

  $hook = new CRM_Multicompanyaccounting_Hook_AlterMailParams_InvoiceTemplate($params);
  $hook->run();
}

/**
 * Implements hook_civicrm_post().
 */
function multicompanyaccounting_civicrm_post(string $op, string $objectName, int $objectId, &$objectRef) {
  if ($objectName === 'Contribution' && $op === 'create') {
    $hook = new CRM_Multicompanyaccounting_Hook_Post_ContributionCreation($objectId);
    $hook->run();
  }
}

/**
 * Implements hook_civicrm__buildForm().
 */
function multicompanyaccounting_civicrm_buildForm($formName, &$form) {
  $addOrUpdate = ($form->getAction() & CRM_Core_Action::ADD) || ($form->getAction() & CRM_Core_Action::UPDATE);
  if ($formName == 'CRM_Financial_Form_FinancialBatch' && $addOrUpdate) {
    $hook = new CRM_Multicompanyaccounting_Hook_BuildForm_FinancialBatch($form);
    $hook->run();
  }

  if ($formName == 'CRM_Financial_Form_BatchTransaction') {
    $hook = new CRM_Multicompanyaccounting_Hook_BuildForm_BatchTransaction($form);
    $hook->run();
  }

  if ($formName == 'CRM_Financial_Form_Search') {
    $hook = new CRM_Multicompanyaccounting_Hook_BuildForm_FinancialBatchSearch($form);
    $hook->run();
  }

  if ($formName == 'CRM_Financial_Form_FinancialAccount') {
    $hook = new CRM_Multicompanyaccounting_Hook_BuildForm_FinancialAccount($form);
    $hook->run();
  }
}

/**
 * Implements hook_civicrm_postProcess().
 */
function multicompanyaccounting_civicrm_postProcess($formName, $form) {
  if ($formName == 'CRM_Financial_Form_FinancialBatch') {
    $hook = new CRM_Multicompanyaccounting_Hook_PostProcess_FinancialBatch($form);
    $hook->run();
  }
}

/**
 * Implements hook_civicrm_alterContent().
 */
function multicompanyaccounting_civicrm_alterContent(&$content, $context, $tplName, &$object) {
  if ($tplName == 'CRM/Financial/Page/BatchTransaction.tpl') {
    $hook = new CRM_Multicompanyaccounting_Hook_AlterContent_BatchTransaction($content);
    $hook->run();
  }
}

/**
 * Implements hook_civicrm_selectWhereClause().
 */
function multicompanyaccounting_civicrm_selectWhereClause($entity, &$clauses) {
  $ownerOrganisationToFilterIds = CRM_Utils_Request::retrieve('multicompanyaccounting_owner_org_id', 'CommaSeparatedIntegers');
  if ($entity == 'Batch' && !empty($ownerOrganisationToFilterIds)) {
    $hook = new CRM_Multicompanyaccounting_Hook_SelectWhereClause_BatchList($clauses);
    $hook->filterBasedOnOwnerOrganisations($ownerOrganisationToFilterIds);
  }
}

/**
 * Implements hook_civicrm_validateForm().
 */
function multicompanyaccounting_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  if (in_array($formName, ['CRM_Price_Form_Field', 'CRM_Price_Form_Option'])) {
    $parentPriceSetId = CRM_Utils_Request::retrieve('sid', 'Positive');
    $membershipType = new CRM_Multicompanyaccounting_Hook_ValidateForm_PriceField($fields, $errors, $parentPriceSetId);
    $membershipType->validate();
  }

  if ($formName == 'CRM_Event_Form_ManageEvent_Fee') {
    if (!empty($fields['price_set_id'])) {
      $membershipType = new CRM_Multicompanyaccounting_Hook_ValidateForm_PriceField($fields, $errors, $fields['price_set_id']);
      $membershipType->validate();
    }
  }
}
