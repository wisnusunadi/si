<script>
export default {
    data() {
        return {
            dataText: '',
        }
    },
    props: ['openDialog'],
    methods: {
        simpan() {
            this.$emit('simpan', this.dataText)
            this.$emit('close')
        },
    },    
}
</script>
<template>
    <v-dialog v-model="openDialog" persistent max-width="500">
        <v-card>
            <v-card-text>
                <v-textarea solo v-model="dataText"></v-textarea>
            </v-card-text>
            <v-card-actions>
                <v-btn color="primary" @click="simpan">Simpan</v-btn>
                <v-btn color="error" @click="$emit('close')">Batal</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>