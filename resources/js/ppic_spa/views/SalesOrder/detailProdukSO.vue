<script>
export default {
    props: ['showModal'],
    methods: {
        closeModal() {
            this.$emit('close');
        }
    },
}
</script>
<template>
    <div class="modal" :class="{'is-active': showModal}">
        <div class="modal-background"></div>
        <div class="modal-card">
            <div class="modal-card-head">
                <p class="moda-card-title"></p>
                <button class="delete" @click="show"></button>
            </div>
        </div>
    </div>
</template>