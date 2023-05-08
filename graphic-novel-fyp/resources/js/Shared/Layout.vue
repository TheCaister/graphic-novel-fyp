<template>
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

export default {
    components: {
        Nav,
    },

    computed: {
        // Get stuff in shared by every page by accessing $page.props
        username() {
            return this.$page.props.auth.username;
        },
    },
}
</script>

