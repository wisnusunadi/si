@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    .nomor-so {
        background-color: #717FE1;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .nomor-akn {
        background-color: #DF7458;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .nomor-po {
        background-color: #85D296;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .instansi {
        background-color: #36425E;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .belum_diterima {
        float: left;
        width: auto;
        padding: 5px;
        margin-top: 5px;
        border: 1px solid #FFE2E5;
        background-color: #FFE2E5;
        color: #F7616B;
        font-size: 14px;
        border-radius: 6px;
    }

    .sudah_diterima {
        float: left;
        width: auto;
        padding: 5px;
        margin-top: 5px;
        border: 1px solid #C9F7F5;
        background-color: #C9F7F5;
        color: #1CC7CD;
        font-size: 14px;
        border-radius: 6px;
    }

</style>
<input type="hidden" name="" id="auth" value="{{ Auth::user()->divisi_id }}">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    @foreach ($header as $h)
                    <input type="hidden" name="" id="id" value="{{ $h->id }}">
                    <div class="col-sm">
                        <div class="row row-cols-2">
                            {{-- col --}}
                            <div class="col"> <label for="">Kode Sparepart</label>
                                <div class="card nomor-so">
                                    <div class="card-body">
                                        {{ $h->spare->kode }}
                                    </div>
                                </div>
                            </div>
                            {{-- col --}}
                            <div class="col"> <label for="">Nama Sparepart</label>
                                <div class="card nomor-akn">
                                    <div class="card-body">
                                        {{ $h->nama}}
                                    </div>
                                </div>
                            </div>
                            {{-- col --}}
                            <div class="col"> <label for="">Unit</label>
                                <div class="card nomor-po">
                                    <div class="card-body">
                                        -
                                    </div>
                                </div>
                            </div>
                            {{-- col --}}
                            <div class="col"> <label for="">Jumlah</label>
                                <div class="card instansi">
                                    <div class="card-body">
                                       {{ $h->stok }} pcs
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table_edit_sparepart">
                    <thead class="thead-light">
                        <tr>
                            <th colspan="2" class="text-center">Tanggal</th>
                            <th colspan="2" class="text-center">Tujuan</th>
                            <th rowspan="2">No Seri</th>
                            <th rowspan="2">Layout</th>
                            <th rowspan="2">Kerusakan</th>
                            <th rowspan="2">Tingkat Kerusakan</th>
                            {{-- Status Akan Berubah secara otomatis Jika dia sudah mengubah data kerusakan atau tingkat kerusakan --}}
                            <th rowspan="2">Status</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Keluar</th>
                            <th>Dari</th>
                            <th>Ke</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- <tr>
                            <td>10-04-2021</td>
                            <td>23-04-2021</td>
                            <td><span class="badge badge-success">Divisi IT</span></td>
                            <td><span class="badge badge-info">Divisi QC</span></td>
                            <td>65846464586</td>
                            <td>Layout 1</td>
                            <td>Kerusakan Sparepart</td>
                            <td>Level 1</td>
                            <td><span class="sudah_diterima">Sudah Diperbaiki</span></td>
                            <td><button class="btn btn-outline-info" onclick="changeStatus()"><i
                                        class="far fa-edit"></i></button></td>
                        </tr>
                        <tr>
                            <td>10-04-2021</td>
                            <td>23-04-2021</td>
                            <td><span class="badge badge-success">Divisi IT</span></td>
                            <td><span class="badge badge-info">Divisi QC</span></td>
                            <td>65846464586</td>
                            <td>Layout 1</td>
                            <td>Kerusakan Sparepart</td>
                            <td>Level 1</td>
                            <td><span class="belum_diterima">Belum Diperbaiki</span></td>
                            <td><button class="btn btn-outline-info" onclick="changeStatus()"><i
                                        class="far fa-edit"></i></button></td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade changeStatus" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col"> <label for="">No Seri</label>
                                <div class="card nomor-so">
                                    <div class="card-body" id="nose">
                                        89798797856456
                                    </div>
                                </div>
                            </div>
                            {{-- col --}}
                            <div class="col"> <label for="">Tanggal Masuk & Tanggal Keluar</label>
                                <div class="card-group">
                                    <div class="card nomor-akn">
                                        <div class="card-body">
                                            <p class="card-text" id="in">10-04-2022</p>
                                        </div>
                                    </div>
                                    <div class="card nomor-akn">
                                        <div class="card-body">
                                            <p class="card-text" id="out">23-09-2022</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Layout</label>
                                <select name="" id="" class="form-control layout_edit">
                                    <option value="">Layout 1</option>
                                    <option value="">Layout 2</option>
                                    <option value="">Layout 3</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Tingkat Kerusakan</label>
                                <select name="" id="" class="form-control kerusakan_edit">
                                    <option value="">Level 1</option>
                                    <option value="">Level 2</option>
                                    <option value="">Level 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                          <label for="">Kerusakan</label>
                          <textarea name="" id="" cols="5" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
@stop
@section('adminlte_js')
<script>
    function changeStatus() {
        $('.changeStatus').modal('show');
    }
    $('.layout_edit').select2({
        dropdownParent: $('.changeStatus')
    });
    $('.kerusakan_edit').select2({
        dropdownParent: $('.changeStatus')
    });
    var id = $('#id').val();
    console.log(id);
    $('.table_edit_sparepart').dataTable({
        destroy: true,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "/api/gk/his-spr/" + id,
        },
        columns: [
            {data: 'inn'},
            {data: 'out'},
            {data: 'from'},
            {data: 'to'},
            {data: 'noser'},
            {data: 'layout'},
            {data: 'remarks'},
            {data: 'tingkat'},
            {data: 'status'},
            {data: 'action'},
        ],
        "language": {
        "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        },
        "columnDefs": [
            {
                "targets": [9],
                "visible": document.getElementById('auth').value == '2' ? false : true
            }
        ]
    });

    $(document).on('click', '.detailModal', function() {
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: "/api/gk/noseri/" + id,
            type: "get",
            dataType: "json",
            success: function(res) {
                $('div#nose').text(res.noser);
                $('p#in').text(res.in);
                $('p#out').text(res.out);
            }
        })

        changeStatus();
    })
</script>
@stop
