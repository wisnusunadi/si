<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title" v-html="nama_produk"></p>
                <button class="delete" aria-label="close" @click="showModal = false"></button>
            </header>
            <section class="modal-card-body">
                <table class="table is-fullwidth" id="detailtable">
                    <thead>
                        <tr>
                            <th>SO</th>
                            <th>PO</th>
                            <th>AKN</th>
                            <th>Tanggal order</th>
                            <th>Tanggal pengiriman</th>
                            <th>Jumlah</th>
                            <th>Pelanggan</th>
                            <th>Jenis</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in detail" :key="item.id">
                            <td v-html="item.so"></td>
                            <td>{{ item.po }}</td>
                            <td>{{ item.akn }}</td>
                            <td v-html="item.tgl_order"></td>
                            <td v-html="item.tgl_delivery"></td>
                            <td v-html="item.jumlah_pesanan"></td>
                            <td>{{ item.customer }}</td>
                            <td>{{ item.jenis }}</td>
                            <td>{{ item.status }}</td>
                        </tr>
                    </tbody>
                </table>
            </section>
            <footer class="modal-card-foot"></footer>
        </div>
    </div>
</template>