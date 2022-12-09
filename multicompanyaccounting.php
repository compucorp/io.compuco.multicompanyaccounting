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
