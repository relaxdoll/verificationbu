<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

</head>
<body>
<style media="all" type="text/css">
    #lineItem tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
</style>


<div style="font-family: Open Sans;font-size: 10pt;color: black;">

    <div id="top" style="display: table; width: 100%; height: 100mm;">
        <div id="customer" style="float: left; width: 110mm; padding-top: 52mm">
            <div style="margin-left: 40mm">%ContactCompanyName%</div>
            <br>
            <div style="margin-left: 40mm">${contact.cf_tax_id}</div>
            <br>
            <div style="margin-left: 40mm">%CustomerBAddress%</div>
        </div>

        <div id="customer" style="float: left; width: 80mm; padding-top: 52mm">
            <div style="margin-left: 20mm">%InvoiceNumber%</div>
            <br>
            <div style="margin-left: 20mm">%InvoiceDate%</div>
        </div>
    </div>
    <div id="top" style="display: table; width: 100%; height: 35mm; padding-top: 15mm;">
        <div id="PO" style="float: left; width: 40mm;">
            <div style="text-align: center;">%P.O.Number%</div>
        </div>
        <div id="Sale" style="float: left; width: 40mm;">
            <div style="text-align: center;">%SalesPerson%</div>
        </div>
        <div id="Term" style="float: left; width: 40mm;">
            <div style="text-align: center;">%PaymentTerms%</div>
        </div>
        <div id="Due" style="float: left; width: 37mm;">
            <div style="text-align: center;">%DueDate%</div>
        </div>
        <div id="Tax" style="float: right; width: 36mm;">
            <div style="text-align: center;">-</div>
        </div>

    </div>
    <table style="height: 63mm; width:100%;table-layout:fixed;border-spacing: 0;border-collapse: collapse;"
           class="pcs-itemtable" border="0" cellpadding="0"
           cellspacing="0">
        <tbody id="lineitem">
        <tr>

            <td style="font-size: 9pt; color: #000000; width: 112mm;" valign="top">
                <span style="margin-left: 27mm; word-wrap: break-word;" id="tmp_item_name">%ItemName%</span>
                <br>
                <span style="margin-left: 27mm; white-space: pre-wrap;word-wrap: break-word;" id="tmp_item_description">%ItemDescription%</span>
            </td>
            <td style="font-size: 9pt;color: #000000; width: 25mm; text-align:center;word-wrap: break-word;"
                valign="top">
                <span id="tmp_item_qty">%ItemQty%</span>
                <br>
            </td>
            <td style="font-size: 9pt;color: #000000; width: 25mm; text-align:right; word-wrap: break-word;"
                valign="top">
                <span id="tmp_item_rate" style="margin-right: 2mm;">%ItemRate%</span>
                <br>
            </td>
            <td style="font-size: 9pt;color: #000000;text-align:right; width: 31mm; word-wrap: break-word;"
                valign="top">
                <span style="margin-right: 2mm;">%ItemAmount%</span>
                <br>
            </td>
        </tr>
        </tbody>
    </table>

    <div id="bot" style="display: table; width: 100%; height: 50mm;">
        <div id="customer" style="float: right; width: 30mm;">
            <div style="margin-right: 3mm; text-align: right;">%InvoiceSubTotal%</div>
            <br>
            <div style="text-align: center;">-</div>
            <br>
            <div style="margin-right: 3mm; text-align: right;">%InvoiceSubTotal%</div>
            <br>
        </div>
        <div id="customer" style="float: left; width: 100%;">
            <div style="margin-left: 30mm; text-align: left;">%TotalInWords%</div>
        </div>

    </div>

</div>

</body>
</html>
