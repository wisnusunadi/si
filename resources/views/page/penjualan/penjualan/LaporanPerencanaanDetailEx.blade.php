<?php
if ($detail->count() != 0) {


    // foreach ($detail as $d) {
    //     $row[$i] = $d;
    //     $i++;
    // }

    // foreach ($row as $cell) {
    //     if (isset($total[$cell->DetailRencanaPenjualan->rencana_penjualan_id])) {
    //         $total[$cell->DetailRencanaPenjualan->rencana_penjualan_id]++;
    //     } else {
    //         $total[$cell->DetailRencanaPenjualan->rencana_penjualan_id] = 1;
    //     }
    // }
    // echo "<table border=\"1\">";
    // foreach ($data->unique('customer_id') as $d) {
    //     echo "<tr>
    //     <th colspan=4 style=text-align:center>";
    //     echo $d->Customer->nama;
    //     echo "</th>
    // </tr>";
    // }
    // echo " <tr>
    // <th  rowspan=2  style=text-align:center>Instansi</th>
    // <th  rowspan=2  style=text-align:center>Produk</th>
    // <th  colspan=3  style=text-align:center>Rencana</th>
    // <th  colspan=5  style=text-align:center>Realisasi</th>
    // </tr>
    //         <tr>
    //             <th style=text-align:center>Jumlah</th>
    //             <th style=text-align:center>Harga</th>
    //             <th style=text-align:center>Sub Total</th>
    //             <th style=text-align:center>PO</th>
    //             <th style=text-align:center>Tgl PO</th>
    //             <th style=text-align:center>Jumlah</th>
    //             <th style=text-align:center>Harga</th>
    //             <th  style=text-align:center>Sub Total</th>
    //         </tr>";
    // $n = count($row);
    // $cekinstansi = "";
    // $totalharga = 0;
    // for ($i = 0; $i < $n; $i++) {
    //     $cell = $row[$i];
    //     echo '<tr>
    //     ';
    //     if ($cekinstansi != $cell->DetailRencanaPenjualan->rencana_penjualan_id) {
    //         echo '<td' . ($total[$cell->DetailRencanaPenjualan->rencana_penjualan_id] > 1 ? ' rowspan="' . ($total[$cell->DetailRencanaPenjualan->rencana_penjualan_id]) . '">' : '>') . $cell->DetailRencanaPenjualan->RencanaPenjualan->instansi . '</td>';
    //         $cekinstansi = $cell->DetailRencanaPenjualan->rencana_penjualan_id;
    //     }
    //     echo "
    //     <td rowspan".$cell->pesanan->getJumlahPaketPesanan().">" . $cell->PenjualanProduk->nama . "</td>
    //     <td>" . $cell->DetailRencanaPenjualan->jumlah . "</td>
    //     <td>" . $cell->DetailRencanaPenjualan->harga . "</td>
    //     <td>" . $cell->DetailRencanaPenjualan->jumlah * $cell->DetailRencanaPenjualan->harga . "</td>
    //         <td>" . $cell->Pesanan->no_po . "</td>
    //         <td>" . $cell->Pesanan->tgl_po . "</td>
    //         <td>" . $cell->jumlah . "</td>
    //         <td>" . $cell->harga . "</td>
    //         <td>" . $cell->harga * $cell->jumlah . "</td>
    //     </tr>";
    //     $totalharga += $cell->harga * $cell->jumlah;
    // }
    // echo "
    // <tr>
    // <td colspan=4 >TOTAL</td>
    // <td >" . $cell->pesanan->Ekatalog->Customer->sumSubtotal() . "</td>
    // <td colspan=4 ></td>
    // <td >" . $totalharga . "</td>
    // </tr>
    // </table>";
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
        }
        $totalrencana = $totalrencana + ($j->jumlah * $j->harga);
        $cdrp++;
    }
    echo "
<tr>
<td colspan=4 style=text-align:center>TOTAL</td>
<td >" . $totalrencana . "</td>
<td colspan=4 ></td>
<td >" . $totalrealisasi . "</td>
</tr>
</table>";
} else {
    echo "<table border=\"1\">";
    foreach ($data->unique('customer_id') as $d) {
        echo "<tr>
    <th colspan=4 style=text-align:center>";
        echo $d->Customer->nama;
        echo "</th>
</tr>";
    }
    echo " 
    <tr> 
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
        $rowpesananc = 0;
        foreach ($i->DetailRencanaPenjualan as $j) {
            $rowpesananc++;
        }

        $cdrp = 0;
        echo "<tr>
        <td rowspan=" . $rowpesananc . ">" . $i->instansi . "</td>
        ";

        foreach ($i->DetailRencanaPenjualan as $j) {
            if ($cdrp <= 0) {
                echo "
                <tr>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        <td>8</td>
        <td>9</td>
       
        ";
            } else {
                echo "
                <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
                </tr>";
            }
        }
        $cdrp++;
    }

    echo "
    </tr>
  </table>";
}
