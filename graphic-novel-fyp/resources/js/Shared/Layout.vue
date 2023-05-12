<template>
    <!-- Adding fallback title if title isn't specified -->
    <Head title="Example">
        <title>Epic App</title>
        <!-- By using head-key, you can prevent duplicate meta data -->
        <meta type="description" content="Home page" head-key="description">
    </Head>

    <section class="p-6 bg-gray-200">
        <header class="flex">
            <h1 class="font-bold text-lg">
                My Epic App
            </h1>

            <p class="text-sm ml-4">
                <!-- Get stuff in shared by every page by accessing $page.props -->
                Welcome back, {{ username }}
            </p>

            <Nav/>
        </header>
    </section>

    <section class="p-6">
        <slot/>
    </section>

</template>

<script>
import Nav from './Nav.vue';
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        Nav,
        Head
    },

    computed: {
        // Get stuff in shared by every page by accessing $page.props
        username() {
            return this.$page.props.auth.username;
        },
    },
}
</script>

