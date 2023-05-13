<template>
    <h1>Users let's go!!</h1>

    <!-- v-model - 2-way binding -->
    <input v-model="search" type="text" placeholder="Search...">

    <!-- <Layout></Layout> -->

    <ul>
        <!-- Good practice to set the loop key -->
        <li v-for="user in users.data" :key="user.id" v-text="user.name + ' id: ' + user.id"></li>
    </ul>

    <div>
        <Pagination :links="users.links" />
    </div>

    <div style="margin: 400px;">
        <p>The current time is {{ time }}.</p>

        <!-- You can preserve the scroll position like this -->
        <Link href="/users" preserve-scroll>Refresh</Link>
    </div>
</template>

<script setup>

import { Link } from '@inertiajs/vue3';
import Pagination from '../Shared/Pagination.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';


let props = defineProps({
    time: String,
    users: Object,
    filters: Object,
});


// 2-way binding.
// Whatever's in ref is what's in the input field
let search = ref(props.filters.search);

// Watch for changes in search
watch(search, (value) => {
    console.log('Changed: ' + value);
    
    // Make a GET request to /users
    router.get('/users', {
        search: value,
    },
    {
        // Preserve the scroll position
        // preserveScroll: true,

        // Preserve the data
        // This means that the page won't be reloaded
        preserveState: true,

        // Replace the current URL. This means that the URL won't be added to the browser history
        replace: true,
    });
});

</script>

<!-- export default {
    components: {
        Link,
    },

    // Be careful when propping. More info 
    props: {
        time: String,
        users: Object,
    },

} -->

<!-- If you use script setup, imported components will automatically be registered -->
<!-- <script setup>
    import {Layout} from '../Shared/Layout.vue';

import { Link } from '@inertiajs/vue3';

// You can also define props like this
defineProps({
    time: String,
})
</script> -->