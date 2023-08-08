<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="modal fade" id="modalautocheckbox" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetNomorSeri()">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <label for="" class="col-form-label">Nomor Seri</label>
                <textarea cols="30" rows="10" class="form-control" id="nomor_seri"></textarea>
                <div class="text-muted">
                    <small>
                        <ul>
                            <li>Gunakan koma (,) atau enter untuk memisahkan nomor seri</li>
                            <li>Contoh: 123456, 123457, 123458</li>
                        </ul>
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetNomorSeri()">Keluar</button>
                <button type="button" id="simpandata" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script>
    const resetNomorSeri = () => {
        $('#nomor_seri').val('');
    }

    function validasi_checked_produk() {
        var rows = $('#belumkirimtable').DataTable().rows().nodes();
        if ($('.check_detail:checked', rows).length <= 0) {
            $('#check_all').prop('checked', false);
            $('#kirim_produk').attr('disabled', true);
        } else {
            $('#kirim_produk').attr('disabled', false);
        }
    }

    const nomorSeri = (noseriditemukan) => {
        $('input[name="check_all_noseri"]:checked').prop('checked', false);
        var rows = $('#noseritable').DataTable().rows().nodes();
        var text = $('#belumkirimtable > tbody > tr.bgcolor').find('div[name="array_check[]"]').text(noseriditemukan);
        $('#belumkirimtable > tbody > tr.bgcolor').find('.jumlah_kirim').val($(
                        '.check_noseri:checked', rows)
                    .length);

        if(noseriditemukan.length > 0) {
            $('#belumkirimtable > tbody > tr.bgcolor').find('.jumlah_kirim').removeClass(
                        'is-invalid');
            $('#belumkirimtable > tbody > tr.bgcolor').find('.check_detail').attr('disabled',
            false);
        }

        validasi_checked_produk();
    }

    $(document).on('click', '#simpandata', function () {
        // split nomor seri berdasarkan enter atau koma
        let nomor_seri = $('#nomor_seri').val().split(/[\n,]+/);
        // remove spasi tiap nomor seri
        nomor_seri = nomor_seri.map(function (item) {
            return item.trim();
        });
        // remove spasi atau karakter kosong
        nomor_seri = nomor_seri.filter(function (el) {
            return el != '';
        });
        // remove duplikat
        nomor_seri = [...new Set(nomor_seri)];
        let nomorseriditemukan = [];

        // checkbox nomor seri yang sesuai di datatable id noseritable menggunakan datatable dan trigger event change
        let table = $('#noseritable').DataTable();
        table.rows().every(function (rowIdx, tableLoop, rowLoop) {
            let data = this.data();
            let nomor_seri_checkbox = data.no_seri
            if (nomor_seri.includes(nomor_seri_checkbox)) {
                $(this.node()).find('input[type="checkbox"]').prop('checked', true)
                nomorseriditemukan.push(data.id);
            }
        });
        nomorSeri(nomorseriditemukan);
        resetNomorSeri();
        $('#modalautocheckbox').modal('hide');
    });
</script>