export default {
    methods: {
        status(status){
            switch (status) {
                case 'batal':
                    return `<span class="tag is-danger is-light">${status}</span>`
                case 'penjualan':
                    return `<span class="tag is-danger is-light">${status}</span>`
                default:
                    return `
                    <progress class="progress is-success" value="${status}" max="100">${status}%</progress>
                    <span><b>${status}%</b> Selesai</span>
                    `
            }
        },

        checkdata(data){
            if(data == null){
                return `<span class="tag is-danger is-light">Belum ada</span>`
            }else{
                return data
            }
        },
        async detail(id, status){
            this.$router.push({ name: 'PenjualanDetail', params: { id: id, jenis: 'ekatalog', status } });
        }
    },
}