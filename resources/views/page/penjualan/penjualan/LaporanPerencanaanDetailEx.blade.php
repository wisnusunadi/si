<?php
if ($detail->count() != 0) {


    $i = 0;

    foreach ($detail as $d) {
        $row[$i] = $d;
        $i++;
    }



    foreach ($row as $cell) {
        if (isset($total[$cell->detail_rencana_penjualan_id])) {
            $total[$cell->detail_rencana_penjualan_id]++;
        } else {
            $total[$cell->detail_rencana_penjualan_id] = 1;
        }
    }
    echo "<table border=\"1\">";
    foreach ($data->unique('customer_id') as $d) {
        echo "<tr>
    <th colspan=4 style=text-align:center>";
        echo $d->Customer->nama;
        echo "</th>
</tr>";
    }
    echo " <tr> 
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
    $n = count($row);
    $cekinstansi = "";
    $totalharga = 0;
    for ($i = 0; $i < $n; $i++) {
        $cell = $row[$i];
        echo '<tr>
    ';
        if ($cekinstansi != $cell->detail_rencana_penjualan_id) {
            echo '<td' . ($total[$cell->detail_rencana_penjualan_id] > 1 ? ' rowspan="' . ($total[$cell->detail_rencana_penjualan_id]) . '">' : '>') . $cell->DetailRencanaPenjualan->RencanaPenjualan->instansi . '</td>';
            echo '<td' . ($total[$cell->detail_rencana_penjualan_id] > 1 ? ' rowspan="' . ($total[$cell->detail_rencana_penjualan_id]) . '">' : '>') . str_replace("&", "dan", $cell->PenjualanProduk->nama_alias)  . '</td>';
            echo '<td' . ($total[$cell->detail_rencana_penjualan_id] > 1 ? ' rowspan="' . ($total[$cell->detail_rencana_penjualan_id]) . '">' : '>') . $cell->DetailRencanaPenjualan->jumlah . '</td>';
            echo '<td' . ($total[$cell->detail_rencana_penjualan_id] > 1 ? ' rowspan="' . ($total[$cell->detail_rencana_penjualan_id]) . '">' : '>') . $cell->DetailRencanaPenjualan->harga . '</td>';
            echo '<td' . ($total[$cell->detail_rencana_penjualan_id] > 1 ? ' rowspan="' . ($total[$cell->detail_rencana_penjualan_id]) . '">' : '>') . $cell->DetailRencanaPenjualan->jumlah * $cell->DetailRencanaPenjualan->harga . '</td>';
            $cekinstansi = $cell->detail_rencana_penjualan_id;
        }
        echo "
        <td>" . $cell->Pesanan->no_po . "</td>
        <td>" . $cell->Pesanan->tgl_po . "</td>
        <td>" . $cell->jumlah . "</td>
        <td>" . $cell->harga . "</td>
        <td>" . $cell->harga * $cell->jumlah . "</td>
    </tr>";
        $totalharga += $cell->harga * $cell->jumlah;
    }
    echo "
<tr> 
<td colspan=4 >'TOTAL'</td>
<td >" . $cell->pesanan->Ekatalog->Customer->sumSubtotal() . "</td>
<td colspan=4 ></td>
<td >" . $totalharga . "</td>
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
    $totalharga = 0;
    foreach ($data as $e) {
        foreach ($e->DetailRencanaPenjualan as $f) {
            echo "
        <tr> 
        <td>" . $e->instansi . "</td> 
        <td>" .  str_replace("&", "dan", $f->PenjualanProduk->nama_alias) . "</td> 
        <td>" . $f->jumlah . "</td> 
        <td>" . $f->harga . "</td> 
        <td>" . $f->jumlah * $f->harga . "</td> 
        <td>-</td> 
        <td>-</td> 
        <td>-</td> 
        <td>0</td> 
        <td>0</td>  
        </tr>       
        ";
            $totalharga += $f->harga * $f->jumlah;
        }
    }



    echo "
    <tr> 
    <td colspan=4 >TOTAL</td>
    <td >" . $totalharga . "</td>
    </tr> </table>";
}
