<html>

<head>
    <meta charset="utf-8">
    <title>Challan</title>
    <link rel="stylesheet" href="style.css">
    <link rel="license" href="https://www.opensource.org/licenses/mit-license/">
    <script src="script.js"></script>
</head>
<style>
    /* reset */

    * {
        border: 0;
        box-sizing: content-box;
        color: inherit;
        font-family: inherit;
        font-size: inherit;
        font-style: inherit;
        font-weight: inherit;
        line-height: inherit;
        list-style: none;
        margin: 0;
        padding: 0;
        text-decoration: none;
        vertical-align: top;
    }

    /* content editable */

    *[contenteditable] {
        border-radius: 0.25em;
        min-width: 1em;
        outline: 0;
    }

    *[contenteditable] {
        cursor: pointer;
    }

    *[contenteditable]:hover,
    *[contenteditable]:focus,
    td:hover *[contenteditable],
    td:focus *[contenteditable],
    img.hover {
        background: #DEF;
        box-shadow: 0 0 1em 0.5em #DEF;
    }

    span[contenteditable] {
        display: inline-block;
    }

    /* heading */

    h1 {
        font: bold 100% sans-serif;
        letter-spacing: 0.5em;
        text-align: center;
        text-transform: uppercase;
    }

    /* table */

    table {
        font-size: 75%;
        table-layout: fixed;
        width: 100%;
    }

    table {
        border-collapse: separate;
        border-spacing: 2px;
    }

    th,
    td {
        border-width: 1px;
        padding: 0.5em;
        position: relative;
        text-align: left;
    }

    th,
    td {
        border-radius: 0.25em;
        border-style: solid;
    }

    th {
        background: #EEE;
        border-color: #BBB;
    }

    td {
        border-color: #DDD;
    }

    /* page */

    html {
        font: 16px/1 'Open Sans', sans-serif;
        overflow: auto;
        padding: 0.5in;
    }

    html {
        background: #999;
        cursor: default;
    }

    body {
        box-sizing: border-box;
        height: 11in;
        margin: 0 auto;
        overflow: hidden;
        padding: 0.5in;
        width: 8.5in;
    }

    body {
        background: #FFF;
        border-radius: 1px;
        box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
    }

    /* header */

    header {
        margin: 0 0 3em;
    }

    header:after {
        clear: both;
        content: "";
        display: table;
    }

    header h1 {
        background: #000;
        border-radius: 0.25em;
        color: #FFF;
        margin: 0 0 1em;
        padding: 0.5em 0;
    }

    header address {
        float: left;
        font-size: 75%;
        font-style: normal;
        line-height: 1.25;
        margin: 0 1em 1em 0;
    }

    header address p {
        margin: 0 0 0.25em;
    }

    header span,
    header img {
        display: block;
        float: right;
    }

    header span {
        margin: 0 0 1em 1em;
        max-height: 25%;
        max-width: 60%;
        position: relative;
    }

    header img {
        max-height: 100%;
        max-width: 100%;
    }

    header input {
        cursor: pointer;
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
        height: 100%;
        left: 0;
        opacity: 0;
        position: absolute;
        top: 0;
        width: 100%;
    }

    /* article */

    article,
    article address,
    table.meta,
    table.inventory {
        margin: 0 0 3em;
    }

    article:after {
        clear: both;
        content: "";
        display: table;
    }

    article h1 {
        clip: rect(0 0 0 0);
        position: absolute;
    }

    article address {
        float: left;
        font-size: 125%;
        font-weight: bold;
    }

    /* table meta & balance */

    table.meta,
    table.balance {
        float: right;
        width: 36%;
    }

    table.meta:after,
    table.balance:after {
        clear: both;
        content: "";
        display: table;
    }

    /* table meta */

    table.meta th {
        width: 40%;
    }

    table.meta td {
        width: 60%;
    }

    /* table items */
    /* Remove borders from all table cells */
    table.inventory {
        clear: both;
        width: 100%;
        border-collapse: collapse;
        /* Remove cell spacing and borders */
        border: none;
        /* Remove table border */
    }

    table.inventory th,
    table.inventory td {
        text-align: left;
        padding: 0.5em 0.3em;
        vertical-align: top;
    }

    table.inventory th {
        font-weight: bold;
        background-color: #EEE;
    }

    /* Create vertical lines */
    table.inventory td {
        border-left: 1px solid #DDD;
        border-right: 1px solid #DDD;
    }

    /* Remove the top border of the first row and bottom border of the last row */
    table.inventory tr:first-child td {
        border-top: none;
    }

    table.inventory tr:last-child td {
        border-bottom: none;
    }

    /* table balance */
    table.balance {
        float: right;
        width: 36%;
        border-collapse: collapse;
        /* Remove cell spacing and borders */
        border: none;
        /* Remove table border */
    }

    table.balance th,
    table.balance td {
        text-align: right;
        padding: 0.5em 1em;
        vertical-align: top;
    }

    /* Create vertical lines */
    table.balance td {
        border-left: 1px solid #DDD;
        border-right: 1px solid #DDD;
    }

    /* Remove the top border of the first row and bottom border of the last row */
    table.balance tr:first-child td {
        border-top: none;
    }

    table.balance tr:last-child td {
        border-bottom: none;
    }

    /* Remove borders from all table cells */
    table.inventory td,
    table.balance td {
        border: none;
    }


    /* aside */

    aside h1 {
        border: none;
        border-width: 0 0 1px;
        margin: 0 0 1em;
    }

    aside h1 {
        border-color: #999;
        border-bottom-style: solid;
    }

    /* javascript */

    .add,
    .cut {
        border-width: 1px;
        display: block;
        font-size: .8rem;
        padding: 0.25em 0.5em;
        float: left;
        text-align: center;
        width: 0.6em;
    }

    .add,
    .cut {
        background: #9AF;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        background-image: -moz-linear-gradient(#00ADEE 5%, #0078A5 100%);
        background-image: -webkit-linear-gradient(#00ADEE 5%, #0078A5 100%);
        border-radius: 0.5em;
        border-color: #0076A3;
        color: #FFF;
        cursor: pointer;
        font-weight: bold;
        text-shadow: 0 -1px 2px rgba(0, 0, 0, 0.333);
    }

    .add {
        margin: -2.5em 0 0;
    }

    .add:hover {
        background: #00ADEE;
    }

    .cut {
        opacity: 0;
        position: absolute;
        top: 0;
        left: -1.5em;
    }

    .cut {
        -webkit-transition: opacity 100ms ease-in;
    }

    tr:hover .cut {
        opacity: 1;
    }

    @media print {
        * {
            -webkit-print-color-adjust: exact;
        }

        html {
            background: none;
            padding: 0;
        }

        body {
            box-shadow: none;
            margin: 0;
        }

        span:empty {
            display: none;
        }

        .add,
        .cut {
            display: none;
        }
    }

    @page {
        margin: 0;
    }
</style>

<body>
    <header>
        <h1>Challan</h1>
        <address>
            <p>Xenottabyte Services Pvt.Ltd</p>
            <p>86 B ,60 Sector Noida<br>Utter-Pradesh, Pin 201301</p>
            <p>(+91) 7982748233</p>
        </address>
    </header>
    <h2>To</h2><br><br>
    <article>
        <h1>Recipient</h1>
        <address>

            <p>{{ $challanInvoice->custmorData->full_name ?? 'N/A' }}<br>{{ $challanInvoice->custmorData->location ?? 'N/A' }}<br>{{ $challanInvoice->custmorData->city ?? 'N/A' }}
            </p>
        </address>
        <table class="meta " style="border: 1px solid #000;">
            <tr>
                <th style="border: none"><span>Challan No</span></th>
                <td style="border: none">
                    <s3pan id="prefix"></s3pan><span>{{ $challanInvoice->challan_no }}</span>
                </td>
            </tr>
            <tr>
                <th style="border: none"><span>Date</span></th>
                <td style="border: none"><span>{{ $challanInvoice->created_at->format('d-M-Y H:i') }}
                    </span></td>
            </tr>
            <tr>
                <th style="border: none"><span>Approved By</span></th>
                <td style="border: none">
                    <s3pan id="prefix"></s3pan><span>{{ $challanInvoice->approved_By }}</span>
                </td>
            </tr>
        </table>
        <table class="inventory" style="border: 1px solid #000">
            <thead style="border: 1px solid #000">
                <tr>
                    <th><span>FT Item </span></th>
                    <th><span>FT Item SN </span></th>
                    <th><span>FT Item Vendor </span></th>
                    <th><span>FT Item Remark </span></th>
                    <th><span>RMT Item </span></th>
                    <th><span>RMT Item SN </span></th>
                    <th><span>RMT Item Vendor </span></th>
                    <th><span>RMT Item Customer </span></th>
                    <th><span>RMT Item Remark</span></th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td style="border-right: 1px solid #000;"><span>{{ $challanInvoice->defulty_product_name }}</span>
                    </td>
                    <td style="border-right: 1px solid #000;"><span>{{ $challanInvoice->defulty_product_sn }}</span>
                    </td>
                    <td style="border-right: 1px solid #000;"><span
                            data-prefix></span><span>{{ $challanInvoice->DefultyvendorData->name ?? 'N/A' }}</span></td>
                    <td style="border-right: 1px solid #000;"><span
                            data-prefix></span><span>{{ $challanInvoice->defulty_product_remark ?? 'N/A' }}</span></td>
                    <td style="border-right: 1px solid #000;">
                        <span>{{ $challanInvoice->products->name ?? 'N/A' }}</span>
                    </td>
                    <td style="border-right: 1px solid #000;">
                        <span>{{ $challanInvoice->replacement_product_serial ?? 'N/A' }}</span>
                    </td>
                    <td style="border-right: 1px solid #000;"><span
                            data-prefix></span><span>{{ $challanInvoice->vendorData->name ?? 'N/A' }}</span></td>
                    <td style="border-right: 1px solid #000;"><span
                            data-prefix></span><span>{{ $challanInvoice->custmorData->full_name ?? 'N/A' }}</span></td>
                    <td style="border-right: 1px solid #000;"><span
                            data-prefix></span><span>{{ $challanInvoice->replacement_Remark ?? 'N/A' }}</span></td>
                </tr>

            </tbody>
        </table>
        {{-- <a class="add">+</a> --}}

        <table class="balance" style="border: 1px solid #000;">
            <tr>
                <th style="border: none"><span>Customer Name</span></th>
                <td><span data-prefix></span><span>{{ $challanInvoice->custmorData->full_name ?? 'N/A' }}</span></td>
            </tr>
            <tr style="border: none">
                <th style="border: none"><span>Signature</span></th>
                <td><span data-prefix></span><span></span></td>
            </tr>

            <tr style="border: none">
                <th style="border: none"><span>Vendor Name</span></th>
                <td><span data-prefix></span><span>{{ $challanInvoice->vendorData->name ?? 'N/A' }}</span></td>
            </tr>
            <tr style="border: none">
                <th style="border: none"><span>Signature</span></th>
                <td><span data-prefix></span><span></span></td>
            </tr>
        </table>
    </article>
    <aside>
        <h1><span contenteditable>Additional Notes</span></h1>
        <div contenteditable class="text-centor">
            <p>Write Something Hear</p>
        </div>
    </aside>


</body>

</html>
