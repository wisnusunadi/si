<script>
import finish_goods from './finish_goods.vue';
import rework from './rework.vue';
export default {
    components: {
        finish_goods,
        rework
    },
    data() {
        return {
            tabs: 'finishgoods'
        }
    },
}
</script>
<template>
    <div>
        <h1 class="title">Data Perakitan</h1>
        <div class="tabs is-centered">
            <ul>
                <li :class="{ 'is-active': tabs == 'finishgoods' }" @click="tabs = 'finishgoods'">
                    <a><span>Finish Goods</span></a>
                </li>
                <li :class="{ 'is-active': tabs == 'rework' }" @click="tabs = 'rework'">
                    <a><span>Rework</span></a>
                </li>
            </ul>
        </div>
        <finish_goods v-show="tabs == 'finishgoods'" />
        <rework v-show="tabs == 'rework'" />
    </div>
</template>