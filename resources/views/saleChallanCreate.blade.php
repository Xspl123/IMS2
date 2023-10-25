<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Challan</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Page styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0.5in;
        }

        /* Header styles */
        header {
            text-align: center;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 24px;
            color: #333;
            text-transform: uppercase;
        }

        header address {
            font-size: 14px;
            margin-top: 10px;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }

        /* Table header styles */
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Table data styles */
        table td {
            background-color: #fff;
        }

        /* Aside styles */
        aside {
            margin-top: 20px;
        }

        aside h1 {
            font-size: 20px;
            color: #333;
            text-transform: uppercase;
            border-bottom: 1px solid #333;
            margin-bottom: 10px;
        }

        aside p {
            font-size: 14px;
            margin-top: 10px;
            line-height: 1.4;
        }
        .table-wd {
            width: 30% !important;
        }
    </style>
</head>
<body>
    
    
    <header>
        <h1 style=" background: #000; border-radius: 0.25em;color: #FFF;  margin: 0 0 1em;  padding: 0.5em 0;">Challan</h1>
    </header>
    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; vertical-align: top; border:none">
                <h4>From</h4><br>
                Xenottabyte Services Pvt.Ltd<br>
                86 B, 60 Sector Noida<br>
                Utter-Pradesh, Pin 201301<br>
                (+91) 7982748233
            </td>
            <td style="width: 50%; vertical-align: top; text-align:center; border:none">
                <h4>To</h4><br>
                <p>{{ $challanInvoice[0]->custmorData->full_name ?? 'N/A' }}</p>
            <p>{{ $challanInvoice[0]->custmorData->location ?? 'N/A' }}</p>
            <p>{{ $challanInvoice[0]->custmorData->city ?? 'N/A' }}</p>
            
            </td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; vertical-align: top; border:none"></td>
            <td style="width: 50%; vertical-align: top; text-align: center; border:none">
                <table style="border: 1px solid black;">
                    <tr>
                        <th class="table-wd">Challan No</th>
                        <td>#Xeno/Up23-24/@php echo date("d-m-Y-H-i-s") @endphp</td>
                    </tr>
                    <tr>
                        <th class="table-wd">Challan Date</th>
                        <td>{{ $challanInvoice[0]->created_at->format('d-M-Y H:i') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            {{-- <th>Customer Name</th>
            <th>Customer Company</th> --}}
            <th>Product Name</th>
            <th>Product Brand</th>
            <th>Product Category</th>
            <th>Product Serial Number</th>
        </tr>
        @foreach($challanInvoice as $item)
        <tr>
            {{-- <td>{{ $item->name }}</td>
            <td>{{ $item->custmorData->full_name }}</td> --}}
            <td>{{ $item->products->name ?? 'N/A' }}</td>
            <td>{{ $item->brand_name ?? 'N/A' }}</td>
            <td>{{ $item->category->cat_name ?? 'N/A' }}</td>
            <td>{{ $item->sn ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </table>

    <br><br>
    <aside>
        <h1></h1>
        <p style="text-align: right;">Signature </p>
    </aside>
</body>
</html>
