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
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    </Head>

    <div class="bg-gray-100 dark:bg-gray-900">

        <section class="p-6">
            <header class="flex">
                <div>
                    <PrimaryButton class="mr-4">Home</PrimaryButton>
                    <PrimaryButton class="mr-4">Projects</PrimaryButton>
                    <PrimaryButton class="mr-4">About Us</PrimaryButton>
                </div>

                <div>
                    <!-- Search bar -->
                    <div class="relative text-gray-600">
                        <input type="search" name="serch" placeholder="Search"
                            class="bg-white h-10 px-5 pr-10 rounded-full text-sm focus:outline-none">
                        <button type="submit" class="absolute right-0 top-0 mt-3 mr-4">
                            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" version="1.1" id="Capa_1"
                                x="0px" y="0px" viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;"
                                xml:space="preserve" width="512px" height="512px">
                                <path
                                    d="M55.146,51.408l-13.8-13.8c2.7-3.9,4.3-8.7,4.3-13.9c0-12.7-10.3-23-23-23s-23,10.3-23,23s10.3,23,23,23c5.2,0,10-1.7,13.9-4.3l13.8,13.8c0.4,0.4,1,0.4,1.4,0l3.9-3.9C55.5,52.408,55.5,51.808,55.146,51.408z M23.966,41.408c-8.8,0-16-7.2-16-16s7.2-16,16-16s16,7.2,16,16S32.766,41.408,23.966,41.408z" />
                            </svg>
                        </button>
                    </div>

                    <span class="material-symbols-outlined dark">&#xE87D;</span>

                    <!-- Settings button with gear icon -->
                    <button type="submit" class="absolute right-0 top-0 mt-3 mr-4 bg-gray-200">
                        <span class="material-symbols dark">
                            e8b8
                        </span>
                    </button>

                </div>





                <!-- <Link :href="route('home')">
            <img src="/assets/black_page.jpg" alt="" width="50" height="50">
            </Link>

            
            <Link href="/user/main" class="ml-auto">
            <div class="flex items-center ml-4">
                <img src="/assets/black_page.jpg" alt="" width="50" height="50">
                <span class="ml-2">{{ username }}</span>
            </div>
            </Link> -->

            </header>
        </section>

        <section class="p-6">
            <slot />
        </section>

    </div>
</template>

<style>
.dark {
  background: black;
  color: rgba(255, 255, 255, 1);
  font-variation-settings: 'GRAD' -25;
}
</style>

<script>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';


export default {
    components: {
        Head,
    },

    computed: {
        // Get stuff in shared by every page by accessing $page.props
        username() {
            return this.$page.props.auth.username;
        },
    },
}
</script>

