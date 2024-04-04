<script>
export default {
    props: ['pengajuan'],
    data() {
        return {
            headers: [
                {
                    text: 'No.',
                    value: 'no'
                },
                {
                    text: 'Versi Pengajuan',
                    value: 'versi'
                },
                {
                    text: 'Umpan Balik',
                    value: 'feedback'
                },
                {
                    text: 'Tanggal Pengajuan',
                    value: 'tgl_pengajuan'
                },
                {
                    text: 'Diterima',
                    value: 'diterima'
                },
                {
                    text: 'Alasan Ditolak',
                    value: 'alasan'
                }
            ],
            search: '',
        }
    },
}
</script>
<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <data-table :headers="headers" :items="pengajuan" :search="search">
                    <template #item.feedback="{ item }">
                        <!-- make list -->
                        <ul>
                            <li v-for="feedback in item.feedback" :key="feedback">
                                {{ feedback }}
                            </li>
                        </ul>
                    </template>
                    <template #item.tgl_pengajuan="{ item }">
                        <div>
                            {{ dateFormat(item.tgl_pengajuan) }}
                        </div>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>