<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

</head>
<body>

<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<style type="text/css" media="all">     #lineItem tr {
        page-break-inside: avoid;
        page-break-after: auto;
    } </style>
<div style="">
    <div>
        <div style="display: table; width: 100%; line-height: 20px;">
            <div style=""><b style="color: rgb(51, 51, 51); font-family: &quot;Open Sans&quot;; line-height: 25px;">บริษัท อีอีซี ไลน์ จำกัด (สำนักงานใหญ่)</b></div>
            <div style=""><font size="1"><font color="#333333" face="Open Sans">47/9 Samnak Ai Ngon, Map Kha, Amphoe Nikhom Phatthana, Rayong 21180</font> </font></div>
            <div style="color: rgb(51, 51, 51); font-family: &quot;Open Sans&quot;;"><font size="1"><span id="tmp_org_address" style="white-space: pre-wrap;">Tel: 03 802 4508  Fax: 03 802 4508  Email: finance@eecl.co.th</span>
                </font></div>
            <div style="color: rgb(51, 51, 51); font-family: &quot;Open Sans&quot;;"><span style="white-space: pre-wrap;"><font size="1">เลขประจำตัวผู้เสียภาษีอากร:  0215560010314  </font></span>
            </div>
            <div style="color: rgb(51, 51, 51); font-family: &quot;Open Sans&quot;; font-size: 10pt;"><span style="white-space: pre-wrap;">           </span></div>
        </div>
        <div style="display: table; width: 100%; line-height: 25px;">
            <div style="float: left; width: 46%; border: black 1px solid; padding: 10px; font-size: 10px;">
                <div><font size="1"><label id="tmp_billing_address_label" style=""> <b style="">ชื่อลูกค้า</b> </label>&nbsp;</font><br>
                    <label>%CustomerName%</label><br>
                    <label><span id="tmp_billing_address">%CustomerBAddress% %CustomerBCity% %CustomerBCode%</span></label>
                    <br>
                    <label>Tax id: ${contact.cf_tax_id}</label>
                </div>
            </div>
            <div style="float: right; width: 46%; border: black 1px solid; padding: 10px;">
                <div><font size="1"><label id="tmp_billing_address_label" style=""> <b style="">ใบเรียกเก็บเงินทดรองจ่าย (Advance Payment)</b> </label>&nbsp;</font><br>
                    <span id="tmp_billing_address" style="white-space: pre-wrap;"><font size="1">No:  %InvoiceNumber%<br>Date:  %InvoiceDate%<br>Due Date:  %DueDate%<br></font></span>
                </div>
            </div>
        </div>
        <table cellspacing="0" cellpadding="0" border="0" class="pcs-itemtable"
               style="color: rgb(51, 51, 51); font-family: &quot;Open Sans&quot;; font-size: 9pt; width: 100%; margin-top: 40px; table-layout: fixed; border-spacing: 0px; border-collapse: collapse;">
            <thead style="border: black 1px solid;">
            <tr style="height:32px;">
                <td align="center" style="color: rgb(0, 0, 0); padding: 5px 10px 5px 5px; overflow-wrap: break-word; width: 9%;"><font size="1"><b style="">ลำดับที่</b>
                    </font></td>
                <td style="border-left: 1px solid black; color: rgb(0, 0, 0); padding: 5px 10px; overflow-wrap: break-word;"><b><font size="1">รายการ</font></b></td>
                <td align="center" style="border-left: 1px solid black; color: rgb(0, 0, 0); padding: 5px 10px 5px 5px; overflow-wrap: break-word; width: 13%;"><font
                        size="1"><b>หน่วย</b> </font></td>
                <td align="right" style="border-left: 1px solid black; color: rgb(0, 0, 0); padding: 5px 10px 5px 5px; overflow-wrap: break-word; width: 120px;"><font
                        size="1"><b>ราคาต่อหน่วย</b> </font></td>
                <td align="right" style="border-left: 1px solid black; color: rgb(0, 0, 0); padding: 5px 10px 5px 5px; overflow-wrap: break-word; width: 120px;"><font
                        size="1"><b>จำนวนเงิน</b> </font></td>
            </tr>
            </thead>
            <tbody id="lineitem">
            <tr>
                <td valign="top" id="tmp_item_discount"
                    style="border-left: 1px solid black; border-bottom: 1px solid rgb(227, 227, 227); background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); padding: 10px 10px 5px; text-align: center; overflow-wrap: break-word;">
                    <font size="1"> %ItemSerialNumber% <br> </font></td>
                <td valign="top"
                    style="border-left: 1px solid black; border-bottom: 1px solid rgb(227, 227, 227); background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); padding: 10px 0px 10px 10px;">
                    <font size="1"><span id="tmp_item_name" style="word-wrap: break-word;">%ItemDescription%</span> </font></td>
                <td valign="top"
                    style="border-left: 1px solid black; border-bottom: 1px solid rgb(227, 227, 227); background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); padding: 10px 10px 5px; text-align: center; overflow-wrap: break-word;">
                    <font size="1"><span id="tmp_item_qty">%ItemQty%</span> <br> </font></td>
                <td valign="top"
                    style="border-left: 1px solid black; border-bottom: 1px solid rgb(227, 227, 227); background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); padding: 10px 10px 5px; text-align: right; overflow-wrap: break-word;">
                    <font size="1"><span id="tmp_item_rate">%ItemRate%</span> <br> </font></td>
                <td valign="top"
                    style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid rgb(227, 227, 227); background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); text-align: right; padding: 10px 10px 10px 5px; overflow-wrap: break-word;">
                    <font size="1"> %ItemAmount% </font><br></td>
            </tr>
        </table>
        <div style="color: rgb(51, 51, 51); font-family: &quot;Open Sans&quot;; font-size: 10pt; width: 100%; margin-top: 1px;">
            <div style="width: 45%;padding: 3px 10px 3px 3px;font-size: 9pt;float: left;">
                <div style="white-space: pre-wrap;"></div>
            </div>
            <div style="width: 50%;float:right;">
                <table width="100%" cellspacing="0" border="0"
                       style="font-size: 9pt;color: #000000;background-color: #ffffff;border-spacing: 0;border-collapse: collapse;">
                    <tbody>
                    <tr>
                        <td valign="middle" align="right" style="padding: 5px 10px 5px 0;"></td>
                        <td valign="middle" align="right" style="width:120px;padding: 10px 10px 10px 5px;" id="tmp_subtotal"></td>
                    </tr>
                    </tbody>
                    <tbody id="taxItems"></tbody>
                    <tbody>
                    <tr>
                        <td valign="middle" align="right" style="padding: 5px 10px 5px 0;"><b>Total</b></td>
                        <td valign="middle" align="right" style="width:120px;;padding: 10px 10px 10px 5px;" id="tmp_total"><b>%InvoiceTotal%</b> <br></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div style="clear: both;"></div>
        </div>
        <div style="color: rgb(51, 51, 51); font-family: &quot;Open Sans&quot;; font-size: 10pt; clear: both; margin-top: 50px; width: 100%;"><label id="tmp_notes_label"
                                                                                                                                                     style="font-size: 10pt;color: #817d7d;">Notes</label>
            <br>
            <p style="margin-top:7px;white-space: pre-wrap;word-wrap: break-word;font-size: 8pt;">%Notes% <br></p></div>
        <div style="color: rgb(51, 51, 51); font-family: &quot;Open Sans&quot;; font-size: 10pt; clear: both; margin-top: 30px; width: 100%;"><br></div>
    </div>
</div>
</body>
</html>
