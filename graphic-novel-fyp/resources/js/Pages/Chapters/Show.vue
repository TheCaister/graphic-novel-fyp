<template>
    <!-- Show the chapter title -->
    <h1 class="text-3xl font-bold mb-2">{{ chapter.chapter_title }}</h1>

    <!-- Show the series title, with a link going to it -->
    <Link :href="`/series/${series.series_id}`">
        <p class="text-gray-700 text-base mb-4">Series: {{ series.series_title }}</p>
    </Link>

    <div class="flex">
        <!-- Show the chapter number -->
        <p class="text-gray-700 text-base mb-4">Chapter {{ chapter.chapter_number }}</p>
    </div>

    <!-- Page component with findPage(pageNumber) passed as prop -->
    <Pages :page="findPage(pageNumber)" />
</template>

<script>

import Pages from './Components/Pages.vue'

let pageNumber = 0;

export default {
    props: {
        chapter: Object,
        series: Object,
        universe: Object,
        pages: Array,

    },

    components: {
        Pages,
    },

    // Set the page number to the page number of the first page
    data() {
        return {
            pageNumber: 1,
        }
    },

    // Find the page object with the page number
    methods: {
        findPage(pageNumber) {
            return this.pages.find(page => page.page_number === pageNumber);

            
        }
    },
}
</script>
