<template>
    <h1>Users let's go!!</h1>

    <!-- <Layout></Layout> -->

    <ul>
        <!-- Good practice to set the loop key -->
        <li v-for="user in users" :key="user.id" v-text="user.name"></li>
    </ul>

    <div style="margin: 400px;">
        <p>The current time is {{ time }}.</p>

        <!-- You can preserve the scroll position like this -->
        <Link href="/users" preserve-scroll>Refresh</Link>
    </div>
</template>

<script>

import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link,
    },

    // Be careful when propping. More info 
    props: {
        time: String,
        users: Array,
    },

}

</script>

<!-- If you use script setup, imported components will automatically be registered -->
<!-- <script setup>
    import {Layout} from '../Shared/Layout.vue';

import { Link } from '@inertiajs/vue3';

// You can also define props like this
defineProps({
    time: String,
})
</script> -->