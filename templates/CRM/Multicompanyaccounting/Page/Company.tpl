<div class="action-link">
    {crmButton p='civicrm/admin/multicompanyaccounting/company/add?rest=1' icon="plus-circle"}{ts}Add Company{/ts}{/crmButton}
</div>

{if $rows}
  <div id="multicompanyaccounting_company_block" class="crm-content-block crm-block">
      {strip}
        <table id="options" class="row-highlight">
          <thead>
          <tr>
            <th>{ts}Name{/ts}</th>
            <th>{ts}Invoice Template{/ts}</th>
            <th>{ts}Invoice Prefix{/ts}</th>
            <th>{ts}Next Invoice Number{/ts}</th>
            <th>{ts}Credit Note Prefix{/ts}</th>
            <th>{ts}Next Credit Note Number{/ts}</th>
            <th></th>
          </tr>
          </thead>
            {foreach from=$rows item=row}
              <tr id="multicompanyaccounting_company_row-{$row.id}" class="crm-entity {cycle values='odd-row,even-row'} {$row.class} multicompanyaccounting-company-row">
                <td>{$row.company_name}</td>
                <td>{$row.invoice_template_name}</td>
                <td>{$row.invoice_prefix}</td>
                <td>{$row.next_invoice_number}</td>
                <td>{$row.creditnote_prefix}</td>
                <td>{$row.next_creditnote_number}</td>
                <td>{$row.action|replace:'xx':$row.id}</td>
              </tr>
            {/foreach}
        </table>
      {/strip}
  </div>
{else}
  <span></span>
{/if}
