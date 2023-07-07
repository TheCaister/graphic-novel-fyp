<template>
    <!-- Load all universes once they are loaded -->
    <div v-if="universeLoaded">
        <div v-for="universe in universes" :key="universe.universe_id">
            <div class="flex items-center">
                <div class="text-sm text-gray-500">
                    <span>My Universe</span>
                    <Link :href="'/universes/' + universe.universe_id" class="text-blue-500 hover:text-blue-700"> | {{
                        universe.universe_name }}</Link>
                </div>
                <div class="flex-shrink-0">
                    <!-- <img class="h-10 w-10 rounded-full" :src="comment.user.profile_photo_url" alt=""> -->
                    <!-- Set profile photo to the black png -->
                    <img class="h-10 w-10 rounded-full" src="/assets/black_page.jpg" alt="">
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                        {{ universe.universe_id }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ universe.universe_description }}
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

                    <!-- Create a delete button -->
                    <button type="button" @click="destroy(series.series_id)"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'

export default {
    data() {
        return {
            universes: [],
            universeLoaded: false,
        }
    },
    setup() {
        const destroy = (series_id) => {
            if (confirm('Are you sure you want to delete this series?')) {
                Inertia.delete(route('series.destroy', series_id))
            }

        }
        return {
            destroy,
        }
    },
    mounted() {
        axios.get('/api/universes/', {
            params: {
                user_id: this.$parent.$parent.$attrs.auth.user.id,
                with_series: true,
            }
        }).then(response => {
            this.universes = response.data
            this.universeLoaded = true
        }).catch(error => console.log(error))
    },
}
</script>