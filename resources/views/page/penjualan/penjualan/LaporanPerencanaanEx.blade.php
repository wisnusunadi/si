<?php

$i = 0;

foreach ($detail as $d) {
    $row[$i] = $d;
    $i++;
}



foreach ($row as $cell) {
    if (isset($total[$cell['rencana_penjualan_id']])) {
        $total[$cell['rencana_penjualan_id']]++;
    } else {
        $total[$cell['rencana_penjualan_id']] = 1;
    }
}
echo "<table border=\"1\">";
foreach ($data->unique('customer_id') as $d) {
    echo "<tr>
    <th colspan=4 style=text-align:center>";
    echo $d->Customer->nama;
    echo "</th>
<tr>";
}
echo "
        <tr> 
            <th>Instansi</th> 
            <th>Produk</th> 
            <th>Jumlah</th> 
            <th>Harga</th> 
            <th>Sub Total</th> 
        </tr>";
$n = count($row);
$cekinstansi = "";
$totalharga = 0;
for ($i = 0; $i < $n; $i++) {
    $cell = $row[$i];
    echo '<tr>
    ';
    if ($cekinstansi != $cell['rencana_penjualan_id']) {
        echo '<td' . ($total[$cell['rencana_penjualan_id']] > 1 ? ' rowspan="' . ($total[$cell['rencana_penjualan_id']]) . '">' : '>') . $cell->RencanaPenjualan->instansi . '</td>';
        $cekinstansi = $cell['rencana_penjualan_id'];
    }
    echo "<td>" . str_replace("&", "dan", $cell->PenjualanProduk->nama_alias) . "</td>
        <td>" . $cell->jumlah . "</td>
        <td>" . $cell->harga . "</td>
        <td>" . $cell->harga * $cell->jumlah . "</td>
    </tr>";
    $totalharga += $cell->harga * $cell->jumlah;
}
echo "
<tr> 
<td colspan=4 >TOTAL</td>
<td>" . $totalharga . "</td>
</tr> 
</table>";
