<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    name: {
        type: String,
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-wrap">
        <!-- Loop through the universes and display them in cards -->
        <Link v-for="universe in universes" :key="universe.id" class="bg-black rounded-lg shadow-md w-2/5 mx-8">
            <div class="w-full h-64 bg-pink-300 flex items-center justify-center rounded-lg">
                <img v-if="universe.image" :src="universe.image" alt="Universe Image" class="w-full h-full object-cover" />
                <div v-else class="text-white text-xl">U{{ universe.id }}</div>
            </div>
            <p class="text-white pt-4">{{ universe.name }}</p>
        </Link>

        <Link class="bg-black rounded-lg shadow-md w-2/5 mx-8">
                <div class="w-full h-64 flex items-center justify-center rounded-lg">

                    <span class="material-symbols-outlined dark"
                        style="font-size: 10rem; font-variation-settings: 'wght' 100; color: #f9a8d4;">
                        add_circle
                    </span>
                </div>
                <p class="text-white pt-4 text-center">Create Universe</p>
        </Link>

    </div>
</template>

<script>
export default {
    data() {
        return {
            // universes: [],
            universes: [
                { id: 1, name: "Universe 1", description: "Description of Universe 1" },
                { id: 2, name: "Universe 2", description: "Description of Universe 2" },
                { id: 3, name: "Universe 3", description: "Description of Universe 3" },
                // Add more universes as needed
            ],
        };
    },
    mounted() {
        // axios
        //     .get('/api/series/recent')
        //     .then((response) => (this.series = response.data))
        //     .catch((error) => console.log(error));
    },
};
</script>