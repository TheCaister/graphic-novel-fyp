<template>
    <!-- Adding fallback title if title isn't specified -->

    <Head title="Example">
        <title>Epic App</title>
        <!-- By using head-key, you can prevent duplicate meta data -->
        <meta type="description" content="Home page" head-key="description">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    </Head>

    <section class="p-6 bg-gray-200">
        <header class="flex">



            <Link :href="route('home')">
            <img src="/assets/black_page.jpg" alt="" width="50" height="50">
            </Link>

            <!-- Place a "publish" button -->
            <Link href="/publish" class="ml-auto">Publish</Link>

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
</template>

<script>
import Nav from './Nav.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';


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

