<template>
    <!-- Adding fallback title if title isn't specified -->

    <Head title="Example">
        <title>Epic App</title>
        <!-- By using head-key, you can prevent duplicate meta data -->
        <meta type="description" content="Home page" head-key="description">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" /> -->

        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    </Head>

    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen">

        <section class="p-6">
            <header class="flex justify-between">
                <div class="flex">
                    <Link :href="route('home')">
                    <PrimaryButton class="mr-4">Home</PrimaryButton>
                    </Link>

                    <PrimaryButton class="mr-4">Projects</PrimaryButton>
                    <PrimaryButton class="mr-4">About Us</PrimaryButton>
                </div>

                <div class="flex items-center ">
                    <!-- Search bar -->
                    <!-- <form action=""> -->
                    <div class="relative text-gray-600 h-10 flex items-center">
                        <select v-model="form.searchType" class="h-full">
                            <option value="users" selected>Users</option>
                            <option value="content">Content</option>
                            <option value="elements">Elements</option>
                        </select>
                        <input v-model="form.search" type="search" name="search" placeholder="Search"
                            class="bg-white h-full text-sm focus:outline-none">
                        <button @click="search" type="submit"
                            class="absolute right-0 top-0 mt-3 mr-4 flex items-center justify-center">
                            <span class="material-symbols-outlined">
                                search
                            </span>
                        </button>
                    </div>
                    <!-- </form> -->


                    <!-- Settings button with gear icon -->
                    <button type="submit">
                        <span class="material-symbols-outlined dark">settings</span>
                    </button>

                    <Link :href="route('logout')" method="post" as="button"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    Log Out</Link>

                        <Avatar
                        src='https://t4.ftcdn.net/jpg/03/64/21/11/360_F_364211147_1qgLVxv1Tcq0Ohz3FawUfrtONzz8nq3e.jpg' :link="route('profile.edit')" />
                 
                </div>

            </header>
        </section>

        <section>
            <slot />
        </section>

    </div>
</template>

<style>
.dark {
    /* background: black; */
    color: rgb(230, 230, 230);
    font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 40
}
</style>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    search: '',
    searchType: 'users',
});

function search() {
    console.log(form.search)

    if (form.search !== '') {

        form.get(route('search'), {
            onFinish: () => {
                console.log('success')
                form.search = ''
            }
        })
    }
}
</script>

<script>
import { Head } from '@inertiajs/vue3';
import Avatar from './Avatar.vue';
import { Link } from '@inertiajs/vue3';




export default {
    components: {
        Head,
        Avatar,
    },

    computed: {
        //  Get stuff in shared by every page by accessing $page.props
        username() {
            return this.$page.props.auth.username;
        },
    },
}
</script>

