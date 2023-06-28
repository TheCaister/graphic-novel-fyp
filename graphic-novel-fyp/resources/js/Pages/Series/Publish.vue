<template>
    <!-- Cycle through each universe, and in each universe, cycle through each series -->
    <div class="flex-col">
        <!-- Create a button that creates a universe -->
        <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">
            <Link :href="`/universes/create/`">Create Universe</Link>
        </button>

        <div v-for="universe in universes" :key="universe.universe_id" class="p-4">
            <h1 class="text-3xl font-bold mb-2">
                <Link :href="`/universes/${universe.universe_id}`">{{ universe.universe_name }}</Link>
            </h1>

            <!-- Create a button that creates a series -->
            <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">
                <Link :href="`/series/create/`">Create Series</Link>
            </button>


            <div v-for="series in universe.series" :key="series.series_id" class="p-4">
                <h1 class="text-3xl font-bold mb-2">
                    <Link :href="`/series/${series.series_id}`">{{ series.series_title }}</Link>
                </h1>
                <p>Summary: {{ series.series_summary }}</p>
                <p>Genre: {{ series.series_genre }}</p>
                <!-- Create a button that adds an episode -->
                <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">
                    <Link :href="`/episodes/create/${series.series_id}`">Add Episode</Link>
                </button>

                <!-- <img src="/assets/black_page.jpg" alt=""> -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        universes: {
            type: Array,
        },
    },
}
</script>