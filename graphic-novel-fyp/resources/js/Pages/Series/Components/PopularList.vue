<template>
    <!-- Have a div that contains a left and a right div. Have the first series be loaded by itself on the left div while putting the others in the right div -->
    <div>
        <div class="flex">
            <!-- Large text to indicate popular series -->
            <h1 class="text-2xl font-bold mb-4">
                <!-- Display title -->
                {{ title }}
            </h1>

            <!-- Only display if series is not empty -->
            <div v-if="series.length > 0">
                <div class="w-full lg:w-1/2">
                    <div>
                        <h1 class="text-2xl font-bold mb-4">{{ series[0].series_title }}</h1>
                        <p class="mb-4">{{ series[0].series_summary }}</p>
                        <p class="mb-4">Genre: {{ series[0].series_genre }}</p>


                        <p class="mb-4">Universe: {{ series[0].universe.universe_name }}</p>


                        <Link :href="'/series/' + series[0].series_id" class="text-white bg-gray-800 px-4 py-2 rounded">View
                        Series</Link>
                    </div>
                </div>

                <div class="w-full lg:w-1/2">
                    <div>
                        <div v-for="(series, index) in series" :key="index">
                            <Link :href="'/series/' + series.series_id">
                            <h1 class="text-2xl font-bold mb-4">{{ series.series_title }}</h1>
                            <p class="mb-4">Genre: {{ series.series_genre }}</p>

                            <p class="mb-4">Universe: {{ series.universe.universe_name }}</p>

                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else>
                <p>No series found.</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        series: Array,
    }
}
</script>