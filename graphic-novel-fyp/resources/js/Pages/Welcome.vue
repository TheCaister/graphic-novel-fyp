<script setup>
import { Head, Link } from '@inertiajs/vue3';
import RecentlyAdded from '@/Components/Home/RecentlyAdded.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    name: {
        type: String,
    },
    series: {
        type: Array,
    }
}
);
</script>

<template>
    <Head title="Welcome" />

    <!-- Vue for -->
    <!-- <div>
        <ul>
            <li v-for="framework of frameworks" v-text="framework"></li>
        </ul>
    </div> -->

    <div class="bg-red-500">

        <RecentlyAdded :series="this.series" />

        <!--  class="sm:fixed sm:top-0 sm:right-0 p-6 text-right" -->

        <div v-if="canLogin">

            <template v-if="!$page.props.auth.user">
                <Link :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Log in</Link>

                <Link v-if="canRegister" :href="route('register')"
                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Register</Link>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            series: [],
        };
    },
    mounted() {
        axios
            .get('/api/series/recent')
            .then((response) => (this.series = response.data))
            .catch((error) => console.log(error));
    },
};
</script>