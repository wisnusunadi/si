<?php
$i = 0;

foreach ($detail as $d) {
    $row[$i] = $d;
    $i++;
}



foreach ($row as $cell) {
    if (isset($total[$cell->pesanan_id])) {
        $total[$cell->pesanan_id]++;
    } else {
        $total[$cell->pesanan_id] = 1;
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
    if ($cekinstansi != $cell->pesanan_id) {
        echo '<td' . ($total[$cell->pesanan_id] > 1 ? ' rowspan="' . ($total[$cell->pesanan_id]) . '">' : '>') . $cell->Pesanan->Ekatalog->satuan . '</td>';
        $cekinstansi = $cell->pesanan_id;
    }
    echo "<td>";
        if(!empty($cell->PenjualanProduk->nama_alias)){
            echo str_replace("&", "dan", $cell->PenjualanProduk->nama_alias);
        }else{
            echo str_replace("&", "dan", $cell->PenjualanProduk->nama);
        }
        echo "</td>
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
