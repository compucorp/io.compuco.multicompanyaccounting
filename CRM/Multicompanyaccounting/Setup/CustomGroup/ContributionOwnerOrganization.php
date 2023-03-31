<?php

/**
 * Managing 'Contribution Owner Organization' custom group and its fields.
 */
class CRM_Multicompanyaccounting_Setup_CustomGroup_ContributionOwnerOrganization {

  public function create() {
    // nothing to do here, the custom group will be created automatically
    // because it is defined in the extension XML files.
  }

  public function remove() {
    $customFields = [
      'owner_organization',
    ];
    foreach ($customFields as $customFieldName) {
      civicrm_api3('CustomField', 'get', [
        'name' => $customFieldName,
        'custom_group_id' => 'multicompanyaccounting_contribution_owner',
        'api.CustomField.delete' => ['id' => '$value.id'],
      ]);
    }

    civicrm_api3('CustomGroup', 'get', [
      'name' => 'multicompanyaccounting_contribution_owner',
      'api.CustomGroup.delete' => ['id' => '$value.id'],
    ]);
  }

  public function toggle($status) {
    civicrm_api3('CustomGroup', 'get', [
      'name' => 'multicompanyaccounting_contribution_owner',
      'api.CustomGroup.create' => ['id' => '$value.id', 'is_active' => $status],
    ]);
  }

}
