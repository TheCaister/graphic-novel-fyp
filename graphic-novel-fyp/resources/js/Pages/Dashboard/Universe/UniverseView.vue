<template>
        <!-- Loop through the universes and display them in cards -->
        <Link v-for="universe in universes" :key="universe.id" class="bg-black rounded-lg shadow-md w-2/5 mx-8">
        <div class="w-full h-64 bg-pink-300 flex items-center justify-center rounded-lg">
            <img v-if="universe.image" :src="universe.image" alt="Universe Image" class="w-full h-full object-cover" />
            <div v-else class="text-white text-xl">U{{ universe.id }}</div>
        </div>
        <p class="text-white pt-4">{{ universe.name }}</p>
        </Link>
</template>

<script setup>
const universes = [
    { id: 1, name: "Universe 1", description: "Description of Universe 1" },
    { id: 2, name: "Universe 2", description: "Description of Universe 2" },
    { id: 3, name: "Universe 3", description: "Description of Universe 3" },
    // Add more universes as needed
];
</script>