<template>
    <!-- Display details about the universe -->
    <div class="flex items-center">
        <div class="text-sm text-gray-500">
            <span>{{ universe.universe_name }}</span>
        </div>
        <div class="flex-shrink-0">
            <!-- <img class="h-10 w-10 rounded-full" :src="comment.user.profile_photo_url" alt=""> -->
            <!-- Set profile photo to the black png -->
            <!-- <img class="h-10 w-10 rounded-full" src="/assets/black_page.jpg" alt=""> -->
        </div>
        <div class="ml-4">
            <div class="text-sm font-medium text-gray-900">
                {{ universe.universe_id }}
            </div>
        </div>
    </div>

    <!-- If the universe has any series, display them -->
    <div v-if="universe.series.length != 0">
        <div v-for="series in universe.series" :key="series.series_id" class="p-4">
            <h1 class="text-3xl font-bold mb-2">
                <Link :href="`/series/${series.series_id}`">{{ series.series_title }}</Link>
            </h1>
            <p>Summary: {{ series.series_summary }}</p>
            <p>Genre: {{ series.series_genre }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        universe: Object,
    }
}
</script>