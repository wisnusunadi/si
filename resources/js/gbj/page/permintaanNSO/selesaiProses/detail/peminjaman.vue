<script>
export default {
    props: ['peminjaman']
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Tgl Peminjaman</th>
                        <th>Tgl Pengambilan</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, index) in peminjaman">
                        <tr>

                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>