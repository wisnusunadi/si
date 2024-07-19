<script>
export default {
    props: ["noseri"],
    data() {
        return {
            headers: [
                {
                    text: "No",
                    value: "no",
                },
                {
                    text: "No Seri",
                    value: "noseri",
                },
            ],
            noseridsb: [],
        };
    },
    methods: {
        closeModal() {
            $(".modalDetailNoSeriDsb").modal("hide");
            this.$nextTick(() => {
                this.$emit("close");
            });
        },
    },
    created() {
        this.noseridsb = this.noseri.map((item, index) => {
            return {
                no: index + 1,
                noseri: item,
            };
        });
    },
};
</script>

<template>
    <div
        class="modal fade modalDetailNoSeriDsb"
        id="staticBackdrop"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        Detail Nomor Seri
                    </h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <data-table :headers="headers" :items="noseridsb" />
                </div>
            </div>
        </div>
    </div>
</template>
