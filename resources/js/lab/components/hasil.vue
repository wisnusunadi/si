<script>
export default {
    props: ['hasil'],
    methods: {
        hasilIcon(value) {
            if (typeof value == 'string') {
                value = value.toLowerCase()
            }
            switch (value) {
                case 'ok':
                    return 'fa-check-circle text-success'
                    break;
                case 'not_ok':
                    return 'fa-times-circle text-danger'
                    break;
                case 'nok':
                    return 'fa-times-circle text-danger'
                    break;
                case 'lolos_pengujian':
                    return 'fa-check text-warning'
                    break;
                case 'tidak_lolos_pengujian':
                    return 'fa-times text-danger'
                    break;
                case 'lolos_kalibrasi':
                    return 'fa-check text-pink'
                    break;
                case 'tidak_lolos_kalibrasi':
                    return 'fa-times text-danger'
                    break;
                default:
                    return 'fa-question-circle text-warning'
            }
        }
    },
}
</script>
<template>
    <div>
        <i :class="'fa ' + hasilIcon(hasil)"></i>
    </div>
</template>