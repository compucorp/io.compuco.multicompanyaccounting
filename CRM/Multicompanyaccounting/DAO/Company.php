<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from io.compuco.multicompanyaccounting/xml/schema/CRM/Multicompanyaccounting/Company.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:017e405e5f18b5493c38bbb4760880be)
 */
use CRM_Multicompanyaccounting_ExtensionUtil as E;

/**
 * Database access object for the Company entity.
 */
class CRM_Multicompanyaccounting_DAO_Company extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'multicompanyaccounting_company';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique Company ID
   *
   * @var int
   */
  public $id;

  /**
   * FK to Contact
   *
   * @var int
   */
  public $contact_id;

  /**
   * FK to the message template.
   *
   * @var int
   */
  public $invoice_template_id;

  /**
   * @var string
   */
  public $invoice_prefix;

  /**
   * @var string
   */
  public $next_invoice_number;

  /**
   * @var string
   */
  public $creditnote_prefix;

  /**
   * @var string
   */
  public $next_creditnote_number;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'multicompanyaccounting_company';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Companies') : E::ts('Company');
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'contact_id', 'civicrm_contact', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'invoice_template_id', 'civicrm_msg_template', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('Unique Company ID'),
          'required' => TRUE,
          'where' => 'multicompanyaccounting_company.id',
          'table_name' => 'multicompanyaccounting_company',
          'entity' => 'Company',
          'bao' => 'CRM_Multicompanyaccounting_DAO_Company',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'contact_id' => [
          'name' => 'contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('FK to Contact'),
          'where' => 'multicompanyaccounting_company.contact_id',
          'table_name' => 'multicompanyaccounting_company',
          'entity' => 'Company',
          'bao' => 'CRM_Multicompanyaccounting_DAO_Company',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'add' => NULL,
        ],
        'invoice_template_id' => [
          'name' => 'invoice_template_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Invoice Template ID'),
          'description' => E::ts('FK to the message template.'),
          'where' => 'multicompanyaccounting_company.invoice_template_id',
          'table_name' => 'multicompanyaccounting_company',
          'entity' => 'Company',
          'bao' => 'CRM_Multicompanyaccounting_DAO_Company',
          'localizable' => 0,
          'FKClassName' => 'CRM_Core_DAO_MessageTemplate',
          'html' => [
            'label' => E::ts("Invoice Template"),
          ],
          'add' => NULL,
        ],
        'invoice_prefix' => [
          'name' => 'invoice_prefix',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Invoice Prefix'),
          'maxlength' => 11,
          'size' => CRM_Utils_Type::TWELVE,
          'where' => 'multicompanyaccounting_company.invoice_prefix',
          'table_name' => 'multicompanyaccounting_company',
          'entity' => 'Company',
          'bao' => 'CRM_Multicompanyaccounting_DAO_Company',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'next_invoice_number' => [
          'name' => 'next_invoice_number',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Next Invoice Number'),
          'maxlength' => 11,
          'size' => CRM_Utils_Type::TWELVE,
          'where' => 'multicompanyaccounting_company.next_invoice_number',
          'table_name' => 'multicompanyaccounting_company',
          'entity' => 'Company',
          'bao' => 'CRM_Multicompanyaccounting_DAO_Company',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'creditnote_prefix' => [
          'name' => 'creditnote_prefix',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Credit Note Prefix'),
          'maxlength' => 11,
          'size' => CRM_Utils_Type::TWELVE,
          'where' => 'multicompanyaccounting_company.creditnote_prefix',
          'table_name' => 'multicompanyaccounting_company',
          'entity' => 'Company',
          'bao' => 'CRM_Multicompanyaccounting_DAO_Company',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'next_creditnote_number' => [
          'name' => 'next_creditnote_number',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Next Credit Note Number'),
          'maxlength' => 11,
          'size' => CRM_Utils_Type::TWELVE,
          'where' => 'multicompanyaccounting_company.next_creditnote_number',
          'table_name' => 'multicompanyaccounting_company',
          'entity' => 'Company',
          'bao' => 'CRM_Multicompanyaccounting_DAO_Company',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'panyaccounting_company', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'panyaccounting_company', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
