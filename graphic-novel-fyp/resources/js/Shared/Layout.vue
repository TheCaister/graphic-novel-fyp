<template>
    <!-- Adding fallback title if title isn't specified -->
    <Head title="Example">
        <title>Epic App</title>
        <!-- By using head-key, you can prevent duplicate meta data -->
        <meta type="description" content="Home page" head-key="description">
    </Head>

    <section class="p-6 bg-gray-200">
        <header class="flex">
            
            <button class="w-16"><img src="/assets/black_page.jpg" alt=""></button>
            <Nav/>
        </header>
    </section>

    <section class="p-6">
        <slot/>
    </section>

    <Footer/>
</template>

<script>
import Nav from './Nav.vue';
import { Head } from '@inertiajs/vue3';
import Footer from './Footer.vue';


export default {
    components: {
    Nav,
    Head,
    Footer
},

    computed: {
        // Get stuff in shared by every page by accessing $page.props
        username() {
            return this.$page.props.auth.username;
        },
    },
}
</script>

