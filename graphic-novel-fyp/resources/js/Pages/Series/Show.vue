<template>
    <div>
        <!-- Series title -->
        <h1 class="text-3xl font-bold mb-2">{{ series.series_title }}</h1>

        <!-- Series genre -->
        <p class="text-gray-700 text-base mb-4">Genre: {{ series.series_genre }}</p>

        <!-- Series author -->
        <p class="text-gray-700 text-base mb-4">Author: {{ author.username }}</p>
    </div>

    <!-- Series rating -->
    <p class="text-gray-700 text-base mb-4">Rating: {{ series.rating }}</p>

    <!-- Series description -->
    <p class="text-gray-700 text-base mb-4">{{ series.series_summary }}</p>

    <!-- Button to go to the first chapter -->
    <Link :href="`${chapters[0].url}`" class="text-blue-500 hover:text-blue-700">First chapter</Link>


    <!-- <a :href="`route('chapters.show', {{ chapters[0] }})`" class="text-blue-500 hover:text-blue-700">First chapter</a> -->

    <!-- Loop through and display every chapter -->
    <div class="grid grid-cols-3 gap-4">
        <div v-for="chapter in chapters" :key="chapter.chapter_id">
            <div class="bg-white shadow-md rounded-md p-4">
                <!-- Chapter number -->
                <p class="text-gray-700 text-base mb-4">Chapter {{ chapter.chapter_number }}</p>
                <!-- Display number of likes -->
                <p class="text-gray-700 text-base mb-4">Likes: {{ chapter.likes }}</p>
                <h2 class="text-xl font-bold mb-2">{{ chapter.chapter_title }}</h2>
                <p class="text-gray-700 text-base">{{ chapter.chapter_notes }}</p>
                <Link :href="`${chapter.url}`" class="text-blue-500 hover:text-blue-700">Read</Link>
            </div>
        </div>
    </div>
</template>

<script>


    export default {
        props: {
            series: Object,
            universe: Object,
            chapters: Array,
            author: Object,
        },
    }
</script>