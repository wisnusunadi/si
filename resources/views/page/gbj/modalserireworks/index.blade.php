<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="modal fade" id="modaldetailserisudahproses" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Nomor Seri</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table tableDetailNoSeri">
                    <thead>
                        <tr>
                            <th>No Seri</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('page/gbj/modalserireworks/detailnoseri')
<script>
    const dateIndo = (date) => {
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        const d = new Date(date);
        return `${d.getDate()} ${monthNames[d.getMonth()]} ${d.getFullYear()}`;
    }

    $(document).on('click', '.detailnoseriproduk', function() {
        var table = $('.tableDetailNoSeri').DataTable();
        var data = table.row($(this).closest('tr')).data();
        var index = table.row($(this).closest('tr')).index();

        $('#nomor-seri-reworks').html(data.noseri);
        $('#tgl-dibuat-reworks').html(dateIndo(data.tgl_dibuat));
        $('#packer-reworks').html(data.packer);
        $('.tableprodukreworks').DataTable().clear().destroy();

        let dataJson = data.item;
        if (data.item) {
            $('.tableprodukreworks').DataTable({
                data: dataJson,
                                destroy: true,
                processing: true,
                serverSide: false,
                ordering: false,
                autoWidth: false,
                columns: [
                    {
                        data: null,
                        // buat index
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return data.produk + ' ' + data.varian;
                        }
                    },
                    {
                        data: 'noseri',
                    }
                ]
            });
        }

        $('.modalDetailNoSeri').modal('show');
        // Do something with the data
    });
</script>
