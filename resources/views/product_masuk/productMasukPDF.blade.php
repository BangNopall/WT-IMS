<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>A simple, clean, and responsive HTML invoice template</title>


</head>

<style>
    #table-data {
        border-collapse: collapse;
        padding: 3px;
    }

    #table-data td, #table-data th {
        border: 1px solid black;
    }
</style>

<body>
<div class="invoice-box">
    <!-- <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <img src="https://www.sparksuite.com/images/logo.png" style="width:100%; max-width:300px;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table> -->


    <table border="0" id="table-data" width="100%">
        <tr>
            <td width="70px"><b>Invoice</b></td>
            <td width="">: {{ $product_masuk->id }}</td>
            <td width="30px"><b>Created</b></td>
            <td>: {{ $product_masuk->tanggal }}</td>
        </tr>

        <tr>
            <td><b>Anggota</b></td>
            <td>: {{ $product_masuk->customer->nama }}</td>
            <td><b>Posisi</b></td>
            <td>: {{ $product_masuk->customer->posisi }}</td>
        </tr>

        <tr>
            <td><b>DP Barang</b></td>
            <td >: {{ $product_masuk->product->nama }}</td>
            <td><b>Quantity</b></td>
            <td >: {{ $product_masuk->qty }}</td>
        </tr>

    </table>

    {{--<hr  size="2px" color="black" align="left" width="45%">--}}


    <table border="0" width="80%">
        <tr align="right">
            <td>Best Regard</td>
        </tr>
    </table>

    <!-- <table border="0" width="80%">
        <tr align="right">
            <td><img src="https://upload.wikimedia.org/wikipedia/en/f/f4/Timothy_Spall_Signature.png" width="100px" height="100px"></td>
        </tr>

    </table> -->
    <table border="0" width="80%">
        <tr align="right">
            <td>WT - Kevin Melvern</td>
        </tr>
    </table>
</div>
