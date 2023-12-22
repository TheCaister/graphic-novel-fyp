<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    name: {
        type: String,
    },
}
);
</script>

<template>
    <Head title="Dashboard" />
</template>

<script>
export default {
    data() {
        // return {
        //     series: [],
        // };
    },
    mounted() {
        // axios
        //     .get('/api/series/recent')
        //     .then((response) => (this.series = response.data))
        //     .catch((error) => console.log(error));
    },
};
</script>