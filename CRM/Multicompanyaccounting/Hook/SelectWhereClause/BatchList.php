<?php

class CRM_Multicompanyaccounting_Hook_SelectWhereClause_BatchList {

  private $whereClause;

  public function __construct(&$whereClause) {
    $this->whereClause = &$whereClause;
  }

  /**
   * Filters the batches in the batch list
   * page based on the selected owner organisations.
   *
   * @param $ownerOrganisationToFilterIds
   *   Comma seperated organisation ids
   */
  public function filterBasedOnOwnerOrganisations($ownerOrganisationToFilterIds) {
    $this->whereClause['id'][] = "IN (SELECT batch_id FROM multicompanyaccounting_batch_owner_org WHERE owner_org_id IN ($ownerOrganisationToFilterIds))";
  }

}
