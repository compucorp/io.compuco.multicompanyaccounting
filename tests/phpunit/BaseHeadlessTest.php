<?php

use Civi\Test\HeadlessInterface;
use Civi\Test\TransactionalInterface;
use Civi\Test\CiviEnvBuilder;

abstract class BaseHeadlessTest extends \PHPUnit\Framework\TestCase implements
    HeadlessInterface,
    TransactionalInterface {

  public function setUpHeadless(): CiviEnvBuilder {
    return \Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }

  public function createOrganization($orgName) {
    return civicrm_api3('Contact', 'create', [
      'sequential' => 1,
      'contact_type' => 'Organization',
      'organization_name' => $orgName,
    ])['values'][0];
  }

}
