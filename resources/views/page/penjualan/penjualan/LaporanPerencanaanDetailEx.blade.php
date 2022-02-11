<?php
$i = 0;

$totalrencana = 0;
$totalrealisasi = 0;

echo "<table border=\"1\">";
foreach ($data->unique('customer_id') as $d) {
    echo "<tr>
    <th colspan=10 style=text-align:center>";
    echo $d->Customer->nama;
    echo "</th>
</tr>";
}


echo   "<tr>
            <th  rowspan=2  style=text-align:center>Instansi</th>
            <th  rowspan=2  style=text-align:center>Produk</th>
            <th  colspan=3  style=text-align:center>Rencana</th>
            <th  colspan=5  style=text-align:center>Realisasi</th>
        </tr>
        <tr>
            <th style=text-align:center>Jumlah</th>
            <th style=text-align:center>Harga</th>
            <th style=text-align:center>Sub Total</th>
            <th style=text-align:center>PO</th>
            <th style=text-align:center>Tgl PO</th>
            <th style=text-align:center>Jumlah</th>
            <th style=text-align:center>Harga</th>
            <th  style=text-align:center>Sub Total</th>
        </tr>";

foreach ($data as $i) {
    $rowpesanan = 0;
    $rowpesananc = 0;
    foreach ($i->DetailRencanaPenjualan as $j) {
        foreach ($j->DetailPesanan as $k) {
            $rowpesananc++;
        }
    }
    if ($rowpesananc >= count($i->DetailRencanaPenjualan)) {
        $rowpesanan = $rowpesananc;
    } else {
        $rowpesanan = count($i->DetailRencanaPenjualan);
    }
    $cdrp = 0;
    echo "<tr>
            <td rowspan=" . $rowpesanan . ">" . $i->instansi . "</td>
            ";
    foreach ($i->DetailRencanaPenjualan as $j) {
        $rowdetailpesanan = 1;
        if (count($j->DetailPesanan) > 0) {
            $rowdetailpesanan = count($j->DetailPesanan);
        }
        if ($cdrp <= 0) {
            echo "<td rowspan=" . $rowdetailpesanan . ">" . $j->PenjualanProduk->nama . "</td>
                        <td rowspan=" . $rowdetailpesanan . ">" . $j->jumlah . "</td>
                        <td rowspan=" . $rowdetailpesanan . ">" . $j->harga . "</td>
                        <td rowspan=" . $rowdetailpesanan . ">" . $j->jumlah * $j->harga . "</td>";
            $cdp = 0;
            foreach ($j->DetailPesanan as $k) {
                if ($cdp <= 0) {
                    echo "<td>" . $k->Pesanan->no_po . "</td>
                                <td>" . date_format(date_create($k->Pesanan->tgl_po), "d-m-Y") . "</td>
                                <td>" . $k->jumlah . "</td>
                                <td>" . $k->harga . "</td>
                                <td>" . $k->harga * $k->jumlah . "</td>
                                </tr>";
                } else {
                    echo "<tr>
                                <td>" . $k->Pesanan->no_po . "</td>
                                <td>" . date_format(date_create($k->Pesanan->tgl_po), "d-m-Y") . "</td>
                                <td>" . $k->jumlah . "</td>
                                <td>" . $k->harga . "</td>
                                <td>" . $k->harga * $k->jumlah . "</td>
                                </tr>";
                }
                $totalrealisasi = $totalrealisasi + ($k->jumlah * $k->harga);
                $cdp++;
            }
        } else {
            echo "<tr>
                        <td rowspan=" . $rowdetailpesanan . ">" . $j->PenjualanProduk->nama . "</td>
                        <td rowspan=" . $rowdetailpesanan . ">" . $j->jumlah . "</td>
                        <td rowspan=" . $rowdetailpesanan . ">" . $j->harga . "</td>
                        <td rowspan=" . $rowdetailpesanan . ">" . $j->jumlah * $j->harga . "</td>";
            $cdp = 0;
            foreach ($j->DetailPesanan as $k) {
                if ($cdp <= 0) {
                    echo "<td>" . $k->Pesanan->no_po . "</td>
                                <td>" . date_format(date_create($k->Pesanan->tgl_po), "d-m-Y") . "</td>
                                <td>" . $k->jumlah . "</td>
                                <td>" . $k->harga . "</td>
                                <td>" . $k->harga * $k->jumlah . "</td>
                                </tr>";
                } else {
                    echo "<tr>
                                <td>" . $k->Pesanan->no_po . "</td>
                                <td>" . date_format(date_create($k->Pesanan->tgl_po), "d-m-Y") . "</td>
                                <td>" . $k->jumlah . "</td>
                                <td>" . $k->harga . "</td>
                                <td>" . $k->harga * $k->jumlah . "</td>
                                </tr>";
                }
                $totalrealisasi = $totalrealisasi + ($k->jumlah * $k->harga);
                $cdp++;
            }
        }
        $totalrencana = $totalrencana + ($j->jumlah * $j->harga);
        $cdrp++;
    }
}
echo "
<tr>
<td colspan=4 style=text-align:center>TOTAL</td>
<td >" . $totalrencana . "</td>
<td colspan=4 ></td>
<td >" . $totalrealisasi . "</td>
</tr>
</table>";
