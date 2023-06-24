<!-- Vue boilerplate -->
<template>
    <div class="flex">
        <!-- Loop through each series, creating a div -->
        <div v-for="series in series" :key="series.series_id" class="p-4">
            <!-- Display the series title and link it to the series, make the text big and bold -->
            <h1 class="text-3xl font-bold mb-2"><Link :href="`/series/${series.series_id}`">{{ series.series_title
            }}</Link></h1>

            <!-- Display the series description -->
            <p>Summary: {{ series.series_summary }}</p>
            <!-- Display genre -->
            <p>Genre: {{ series.series_genre }}</p>
            <!-- Display the series cover -->
            <!-- <img :src="series.series_thumbnail" alt="Series cover"> -->
            <img src="/assets/black_page.jpg" alt="">
        </div>
    </div>
</template>

<script>
export default {
    props: {
        series: {
            type: Array,
        }
    },
}
</script>