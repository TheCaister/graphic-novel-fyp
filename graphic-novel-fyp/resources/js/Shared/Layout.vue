<template>
    <!-- Adding fallback title if title isn't specified -->

    <Head title="Example">
        <title>Epic App</title>
        <!-- By using head-key, you can prevent duplicate meta data -->
        <meta type="description" content="Home page" head-key="description">
    </Head>

    <section class="p-6 bg-gray-200">
        <header class="flex">



            <Link :href="route('home')">
            <img src="/assets/black_page.jpg" alt="" width="50" height="50">
            </Link>

            <Link>

            </Link>
            <Nav />

            <!-- Place a "publish" button -->
            <Link href="/series/create" class="ml-auto">Publish</Link>

            <!-- Place a "User" button with the user's profile picture and username -->
            <Link href="/user/main" class="ml-auto">
            <div class="flex items-center ml-4">
                <img src="/assets/black_page.jpg" alt="" width="50" height="50">
                <span class="ml-2">{{ username }}</span>
            </div>
            </Link>


            <!-- Place a "search" button that expands to a searchbar when clicked -->
            <button class="ml-4">Search</button>

        </header>
    </section>

    <section class="p-6">
        <slot />
    </section>

    <Footer />
</template>

<script>
import Nav from './Nav.vue';
import { Head } from '@inertiajs/vue3';
import Footer from './Footer.vue';
import { Link } from '@inertiajs/vue3';


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

