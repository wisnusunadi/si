<script>
import pagination from './pagination.vue';
export default {
    components: {
        pagination,
    },
    props: {
        headers: {
            type: Array,
            required: true,
        },
        items: {
            type: Array,
            required: true,
        },
        search: {
            type: String,
            required: false,
            default: '',
        },
    },
    data() {
        return {
            renderPaginate: [],
            sortColumn: 'no_urut',
            sortDirection: 'asc',
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        sort(header) {
            if (header.sortable == false) return;
            const column = header.value
            if (column === this.sortColumn) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc'
            } else {
                this.sortColumn = column
                this.sortDirection = 'asc'
            }
        },
        cekHeaderRowspan(header) {
            if (this.cekHeaderDetectedChidlren) {
                if (header.children) {
                    return 1
                } else {
                    return 2
                }
            } else {
                return 1
            }
        },
        cekHeaderColspan(header) {
            if (this.cekHeaderDetectedChidlren) {
                if (header.children) {
                    return header.children.length
                } else {
                    return 1
                }
            } else {
                return 1
            }
        }
    },
    computed: {
        filteredDalamProses() {
            const includesSearch = (obj, search) => {
                if (obj && typeof obj === 'object') {
                    return Object.keys(obj).some(key => {
                        if (typeof obj[key] === 'object') {
                            return includesSearch(obj[key], search);
                        }
                        return String(obj[key]).toLowerCase().includes(search.toLowerCase());
                    });
                }
                return false;
            };

            return this.sortedDataTable.filter(data => includesSearch(data, this.search));
        },
        sortedDataTable() {
            const sorted = this.items.sort((a, b) => {
                const modifier = this.sortDirection === 'asc' ? 1 : -1
                if (a[this.sortColumn] < b[this.sortColumn]) return -1 * modifier
                if (a[this.sortColumn] > b[this.sortColumn]) return 1 * modifier
                return 0
            })
            return sorted
        },
        cekHeaderDetectedChidlren() {
            return this.headers.some(header => header?.children)
        }
    },
}
</script>
<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <template v-for="header in headers">
                        <th scope="col" :key="header.text" :rowspan="cekHeaderRowspan(header)"
                            :colspan="cekHeaderColspan(header)" :class="header.align ? header.align : 'text-center'"
                            @click=" sort(header)" :sortable="header.sortable == false ? false : true">
                            <slot :name="`header.${header.value}`">
                                {{ header.text }}
                            </slot>
                            <span v-if="sortColumn === header.value">
                                <i v-if="sortDirection === 'asc'" class="fas fa-arrow-up"></i>
                                <i v-else class="fas fa-arrow-down"></i>
                            </span>
                        </th>
                    </template>
                </tr>
                <tr v-for="header in headers" :key="header.text" v-if="header?.children">
                    <th v-for="child in header.children" :key="child.text"
                        :class="child.align ? child.align : 'text-center'">
                        <slot :name="`header.${child.value}`">
                            {{ child.text }}
                        </slot>
                    </th>
                </tr>
            </thead>
            <tbody v-if="renderPaginate.length > 0">
                <!-- sesuaikan dengan header -->
                <tr v-for="(data, idx) in renderPaginate" :key="idx">
                    <template v-for="header in headers">
                        <td :class="header.align ? header.align : 'text-center'" v-if="!header?.children">
                            <template v-if="!header?.children">
                                <slot :name="`item.${header.value}`" :item="data" :index="idx">
                                    {{ data[header.value] }}
                                </slot>
                            </template>
                        </td>
                        <template v-else>
                            <td v-for="child in header.children" :key="child.text"
                                :class="child.align ? child.align : 'text-center'">
                                <slot :name="`item.${child.value}`" :item="data" :index="idx">
                                    {{ data[child.value] }}
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