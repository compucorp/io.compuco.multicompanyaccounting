<table id="multicompanyaccounting_owner_org_table" class="form-layout">
  <tbody>
  <tr id="multicompanyaccounting_owner_org_row">
    <td class="label">
        {$form.multicompanyaccounting_owner_org_id.label}
    </td>
    <td>
        {$form.multicompanyaccounting_owner_org_id.html}
    </td>
  </tr>
  </tbody>
</table>

{literal}
  <script type="text/javascript">
    CRM.$(function ($) {
      $('#multicompanyaccounting_owner_org_table').insertAfter('#FinancialBatch fieldset.crm-collapsible');
    });
  </script>
{/literal}
