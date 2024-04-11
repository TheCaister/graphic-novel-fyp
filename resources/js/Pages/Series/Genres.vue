<template>
    <!-- Section to display all genres as flex once they are loaded -->
    <div v-if="genresLoaded" class="flex flex-wrap gap-2 md:gap-5 justify-center">
        <!-- Loop through all genres and center them -->
        <div v-for="genre in genres" :key="genre">
            <!-- Create a button for each genre -->
            <button @click="changeGenre(genre)" class="bg-gray-200 hover:bg-gray-300 rounded-full px-3 py-1">
                {{ genre }}
            </button>
        </div>
    </div>
    <div v-else>
        <p>Loading...</p>
    </div>

    <!-- Get selected genre -->
    <p>Selected Genre: {{ selectedGenre }}</p>

    <!-- Section to display all series as flex once they are loaded -->
    <div v-if="seriesLoaded" class="flex flex-wrap gap-2 md:gap-5 justify-center">

        <!-- If there are no series, say so -->
        <div v-if="series.length == 0">
            <p>No series found.</p>
        </div>

        <!-- Loop through all series and center them -->
        <div v-else v-for="series in series" :key="series.series_id">
            <!-- Create a small card for each series, using the series title, thumbnail and include a link -->
            <div class="bg-gray-200 hover:bg-gray-300 rounded-lg px-3 py-1">
                <Link :href="'/series/' + series.series_id">
                <div class="flex flex-col gap-1">
                    <div class="flex flex-row gap-1">
                        <!-- Show the series_thumbnail. If it doesn't exist, make it black -->
                        <img :src="`${series.series_thumbnail}`" alt="Series Thumbnail" class="w-10 h-10 rounded-full" />

                        <p class="font-bold">{{ series.series_title }}</p>
                    </div>
                    <p class="text-sm">{{ series.series_summary }}</p>
                </div>
                </Link>
            </div>

        </div>
    </div>
    <div v-else>
        <p>Loading series...</p>
    </div>
</template>

<script>
import APICalls from '@/Utilities/APICalls'

export default {
    data() {
        return {
            genresLoaded: false,
            genres: [],
            selectedGenre: '',
            seriesLoaded: false,
            series: [],
        }
    },
    methods: {
        // Get all series that match the selected genre, passing in the genre as a parameter
        getSeries(genre) {

            this.seriesLoaded = false;

            APICalls.getSeriesByGenre(this.selectedGenre).then(response => {
                this.series = response.data
                this.seriesLoaded = true
            }).catch(error => console.log(error))
        },

        changeGenre(genre) {
            this.selectedGenre = genre
            this.getSeries(genre)
        }
    },
    mounted() {
        APICalls.getAllGenres().then(response => {
            this.genres = response.data
            this.genresLoaded = true

            this.selectedGenre = this.genres[0]

            this.getSeries(this.selectedGenre)
        }).catch(error => console.log(error))
    },
}
</script>