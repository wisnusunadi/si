<script>
import axios from 'axios'
import Modal from './modalCreateEdit.vue'
export default {
    components: { Modal },
    props: ['part'],
    data() {
        return {
            search: '',
            header: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false
                },
                {
                    text: 'Kelom'
                }
            ]
        }
    },
}
</script>
<template>
    <div>

    </div>
</template>