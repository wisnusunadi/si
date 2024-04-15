<script>
import pagination from './pagination.vue';
export default {
    components: { pagination },
    props: {
        headers: { type: Array, required: true },
        items: { type: Array, required: true },
        search: { type: String, default: '' },
    },
    data() {
        return {
            renderPaginate: [],
            sortColumn: 'no_urut',
            sortDirection: 'asc',
        };
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        sort({ value, sortable }) {
            if (sortable === false) return;
            if (this.sortColumn === value) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = value;
                this.sortDirection = 'asc';
            }
        },
        cekHeaderRowspan(header) {
            return this.cekHeaderDetectedChidlren ? (header.children ? 1 : 2) : 1;
        },
        cekHeaderColspan(header) {
            return this.cekHeaderDetectedChidlren ? (header.children ? header.children.length : 1) : 1;
        },
    },
    computed: {
        filteredDalamProses() {
            const includesSearch = (obj, search) => {
                if (!obj || typeof obj !== 'object') return false;
                return Object.keys(obj).some(key => {
                    if (typeof obj[key] === 'object') return includesSearch(obj[key], search);
                    return String(obj[key]).toLowerCase().includes(search.toLowerCase());
                });
            };
            return this.sortedDataTable.filter(data => includesSearch(data, this.search));
        },
        sortedDataTable() {
            const modifier = this.sortDirection === 'asc' ? 1 : -1;
            return this.items.slice().sort((a, b) => (a[this.sortColumn] < b[this.sortColumn] ? -1 * modifier : 1 * modifier));
        },
        cekHeaderDetectedChidlren() {
            return this.headers.some(header => header.children);
        },
        groupAllChildren() {
            return this.headers.flatMap(header => header.children || []);
        },
    },
};
</script>
<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <!-- Use array destructuring for v-for -->
                    <th v-for="({ text, align, value, children, sortable }) in headers" :key="text"
                        :rowspan="cekHeaderRowspan({ children })" :colspan="cekHeaderColspan({ children })"
                        @click="sort({ value, sortable })" style="cursor: pointer;"
                        :class="[align || 'text-center', { 'sortable': sortable !== false, 'sorting': sortColumn === value }]">
                        <slot :name="`header.${value}`">{{ text }}</slot>
                        <span v-if="sortColumn === value">
                            <i v-if="sortDirection === 'asc'" class="fas fa-arrow-up"></i>
                            <i v-else class="fas fa-arrow-down"></i>
                        </span>
                    </th>
                </tr>
                <!-- Render children headers in the same row -->
                <tr v-if="groupAllChildren.length > 0">
                    <th v-for="child in groupAllChildren" :key="child.text"
                        :class="[child.align || 'text-center', 'child-header']">
                        <slot :name="`header.${child.value}`">{{ child.text }}</slot>
                    </th>
                </tr>
            </thead>
            <tbody v-if="renderPaginate.length > 0">
                <tr v-for="(data, idx) in renderPaginate" :key="idx">
                    <!-- Use array destructuring for v-for -->
                    <template v-for="({ value, children, align }) in headers">
                        <td v-if="!children" :class="[align || 'text-center']">
                            <slot :name="`item.${value}`" :item="data" :index="idx">{{ data[value] }}</slot>
                        </td>
                        <template v-else>
                            <td v-for="child in children" :key="child.text" :class="[child.align || 'text-center']">
                                <slot :name="`item.${child.value}`" :item="data" :index="idx">{{ data[child.value] }}
                                </slot>
                            </td>
                        </template>
                    </template>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="100%" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
        <pagination :filteredDalamProses="filteredDalamProses" @updateFilteredDalamProses="updateFilteredDalamProses" />
    </div>
</template>
