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
    $(document).on('click', '.detailnoseriproduk', function() {
        var table = $('.tableDetailNoSeri').DataTable();
        var data = table.row($(this).closest('tr')).data();
        var index = table.row($(this).closest('tr')).index();
        console.log('Row data:', data);
        console.log('Row index:', index);

        
        // Do something with the data
    });
</script>
