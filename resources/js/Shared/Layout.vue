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

        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    </Head>

    <div class="bg-gray-900 min-h-screen">

        <section class="p-6">
            <header class="flex justify-between">
                <div class="flex">
                    <Link :href="route('home')">
                    <PrimaryButton class="mr-4">Home</PrimaryButton>
                    </Link>

                    <!-- <PrimaryButton class="mr-4">Projects</PrimaryButton> -->
                    <DropdownSubtle :options="universes" @option-selected="goToUniverse" />
                    <Link :href="route('about-us')"
                        class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    About Us
                    </Link>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Search bar -->
                    <!-- <form action=""> -->
                    <form @submit.prevent="search" class="relative text-gray-600  h-10 flex items-center text-white">
                        <div class="relative">
                            <select v-model="form.searchType"
                                class="h-10 bg-gray-900 rounded-l-lg border-r-0 border-gray-600">
                                <option value="users" selected>Users</option>
                                <option value="content">Content</option>
                                <option value="elements">Elements</option>
                            </select>
                        </div>
                        <input v-model="form.search" type="search" name="search" placeholder="Search"
                            class="h-full text-sm focus:outline-none bg-gray-900 placeholder-gray-300 border-gray-600 rounded-r-lg w-96" />
                        <button @click="search" type="submit"
                            class="absolute right-0 top-0 mt-2 mr-12 flex items-center justify-center">
                            <span class="material-symbols-outlined">
                                search
                            </span>
                        </button>
                    </form>

                    <!-- Settings button with gear icon -->
                    <Link :href="route('profile.edit')">
                    <span class="material-symbols-outlined dark">settings</span>
                    </Link>

                    <Avatar
                        :src='page.props.auth.user.profile_picture ? page.props.auth.user.profile_picture : "/assets/black_pixel.png"'
                        :link="route('profile.show', page.props.auth.user.id)" class="border-2 rounded-full" />

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

input[type="search"]::-webkit-search-cancel-button {
    -webkit-appearance: none;
    height: 16px;
    width: 16px;
    background: url('/assets/white_x.png') no-repeat center center;
    cursor: pointer;
}
</style>

<script setup>
import { useForm, usePage, router } from '@inertiajs/vue3';
import DropdownSubtle from '@/Components/DropdownSubtle.vue';
import APICalls from '@/Utilities/APICalls';
import { ref } from 'vue'

const page = usePage();

const universes = ref([]);

const form = useForm({
    search: '',
    searchType: 'users',
});

function search() {

    if (form.search !== '') {

        form.post(route('search',
            {
                'searchType': form.searchType,
                'search': form.search
            }), {
            onFinish: () => {
                form.search = ''
            }
        })
    }
}

APICalls.getUniversesByUserId(page.props.auth.user.id, false).then(response => {
    universes.value = response.data.map(item => ({
        name: item.universe_name,
        id: item.universe_id
    }));

    //console.log('Universes')
    // console.log(universes.value)
}).catch(error => console.log(error))

function goToUniverse(option) {
    // console.log(option)
    router.visit(route('universes.show', option.id))
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
