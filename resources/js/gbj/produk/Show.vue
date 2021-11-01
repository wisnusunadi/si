<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Produk Gudang</h3>
                    <!-- <router-link :to=""></router-link> -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered" id="gudang-barang">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nomor Seri</th>
                                <th>Produk</th>
                                <th>Stok</th>
                                <th>Kelompok</th>
                                <th>Satuan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css"
    export default {
        mounted: function () {
            this.allproduk();
        },
        methods: {
            allproduk: function () {
                $("#gudang-barang").DataTable({
                    ajax: {
                        url: "/api/gbj/data",
                        error: function (xhr, status, err) {
                            console.log(xhr);
                            alert(status);
                            alert(err);
                        }
                    },
                    processing: true,
                    serverSide: true,
                    columns: [{
                            data: "DT_RowIndex",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: "nama",
                        },
                        {
                            data: "produk.nama"
                        },
                        {
                            data: "stok",
                        },
                        {
                            data: "kelompok",
                        },
                        {
                            data: "satuan",
                        },
                        {
                            data: null,
                            render: function (data) {
                                return (
                                    `<td><router-link :to="{name: 'view', params: {id:data.id}}" class="btn btn-success"><i class="fas fa-eye"></i></router-link>&nbsp;<router-link :to="{name: 'edit', params: {id:data.id}}" class="btn btn-warning"><i class="far fa-edit"></i></router-link></td>`
                                );
                            },
                            orderable: false,
                            searchable: false,
                        }
                    ]
                })
            }
        },
    }

</script>
