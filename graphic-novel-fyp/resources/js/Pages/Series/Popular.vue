<template>
    <PopularList v-if="seriesLoaded" :series="this.series" title='Popular Series' />
    <div v-else>
        <p>Loading series...</p>
    </div>

    <!-- Display all genres -->
    <div v-if="genresLoaded" class="flex flex-wrap gap-2 md:gap-5 justify-center">
        <div v-for="genre in genres" :key="genre">
            <button @click="changeGenre(genre)" class="bg-gray-200 hover:bg-gray-300 rounded-full px-3 py-1">
                {{ genre }}
            </button>
        </div>
    </div>
    <div v-else>
        <p>Loading genres...</p>
    </div>

    <PopularList v-if="genreSeriesLoaded" :series="this.genreSeries" title='Popular by Genre' />
    <div v-else>
        <p>Loading series...</p>
    </div>
</template>

<script>
import PopularList from './Components/PopularList.vue'

export default {
    components: {
        PopularList
    },
    data() {
        return {
            genresLoaded: false,
            genres: [],
            selectedGenre: '',
            seriesLoaded: false,
            series: [],
            genreSeries: [],
            genreSeriesLoaded: false,
        }
    },
    methods: {
        // Function to change the genre
        changeGenre(genre) {
            this.selectedGenre = genre
            this.getSeries(genre)
        },
        // Function to get all series of a genre
        getSeries(genre) {
            this.genreSeriesLoaded = false
            axios.get('/api/series/popular', {
                params: {
                    genre: this.selectedGenre,
                }
            }).then(response => {
                this.genreSeries = response.data
                this.genreSeriesLoaded = true

                // console.log(this.genreSeriesLoaded)
            }).catch(error => console.log(error))
        }
    },
    mounted() {
        axios.get('/api/series/popular').then(response => {
            this.series = response.data
            this.seriesLoaded = true
        }).catch(error => console.log(error))

        axios.get('/api/genres').then(response => {
            this.genres = response.data
            this.genresLoaded = true

            this.selectedGenre = this.genres[0]

            this.getSeries(this.selectedGenre)

  
        }).catch(error => console.log(error))
    }
}
</script>