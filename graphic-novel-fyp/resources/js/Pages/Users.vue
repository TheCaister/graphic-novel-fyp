<template>
    <h1>Users let's go!!</h1>

    <Layout></Layout>

    <div style="margin: 400px;">
        <p>The current time is {{ time }}.</p>

        <!-- You can preserve the scroll position like this -->
        <Link href="/users" preserve-scroll>Refresh</Link>
    </div>
</template>

<script>

import Layout from '../Shared/Layout.vue';
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Layout, Link,
    },

    props: {
        time: String,

    }}
</script>