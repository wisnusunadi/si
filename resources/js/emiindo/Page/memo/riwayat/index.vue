<script>
import modalDetail from "./modalDetail.vue";
export default {
    components: {
        modalDetail,
    },
    data() {
        return {
            headers: [
                {
                    text: "Nomor Permintaan",
                    value: "no",
                },
                {
                    text: "Nomor Memo",
                    value: "noMemo",
                },
                {
                    text: "Tanggal Permintaan",
                    value: "dateRequest",
                },
                {
                    text: "Tanggal Kebutuhan",
                    value: "dateNeed",
                },
                {
                    text: "Divisi Peminta",
                    value: "division",
                },
                {
                    text: "Tujuan",
                    value: "tujuan",
                    align: "text-truncate text-center maxwidthTujuan",
                },
                {
                    text: "Aksi",
                    value: "action",
                },
            ],
            items: [
                {
                    no: "NSO-2021080002",
                    noMemo: "NSO-2021080002",
                    dateRequest: "02 Januari 2021",
                    dateNeed: "03 Januari 2021",
                    division: "Divisi A",
                    tujuan: "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                },
                {
                    no: "NSO-2021080003",
                    noMemo: "NSO-2021080003",
                    dateRequest: "03 Januari 2021",
                    dateNeed: "04 Januari 2021",
                    division: "Divisi B",
                    tujuan: "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                },
                {
                    no: "NSO-2021080004",
                    noMemo: "NSO-2021080004",
                    dateRequest: "04 Januari 2021",
                    dateNeed: "05 Januari 2021",
                    division: "Divisi C",
                    tujuan: "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                },
            ],
            search: "",
            detailSelected: {},
            modalSelected: false,
        };
    },
    methods: {
        openModalDetail(item) {
            this.detailSelected = item;
            this.modalSelected = true;
            this.$nextTick(() => {
                $(".modalDetail").modal("show");
            });
        },
    },
};
</script>
<template>
    <div>
        <modalDetail
            v-if="modalSelected"
            :item="detailSelected"
            @close="modalSelected = false"
        />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Search..."
                    v-model="search"
                />
            </div>
        </div>
        <data-table :headers="headers" :items="items" :search="search">
            <template #item.action="{ item }">
                <button
                    class="btn btn-outline-primary btn-sm"
                    @click="openModalDetail(item)"
                >
                    <i class="fa fa-eye"></i>
                    Detail
                </button>
            </template>
        </data-table>
    </div>
</template>
