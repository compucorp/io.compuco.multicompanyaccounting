<?php

/**
 * Collection of upgrade steps.
 */
class CRM_Multicompanyaccounting_Upgrader extends CRM_Multicompanyaccounting_Upgrader_Base {

  public function enable() {
    $steps = [
      new CRM_Multicompanyaccounting_Setup_CustomGroup_ContributionOwnerOrganization(),
    ];
    foreach ($steps as $step) {
      $step->toggle(TRUE);
    }
  }

  public function disable() {
    $steps = [
      new CRM_Multicompanyaccounting_Setup_CustomGroup_ContributionOwnerOrganization(),
    ];
    foreach ($steps as $step) {
      $step->toggle(FALSE);
    }
  }

  public function uninstall() {
    $removalSteps = [
      new CRM_Multicompanyaccounting_Setup_CustomGroup_ContributionOwnerOrganization(),
    ];
    foreach ($removalSteps as $step) {
      $step->remove();
    }
  }

}
