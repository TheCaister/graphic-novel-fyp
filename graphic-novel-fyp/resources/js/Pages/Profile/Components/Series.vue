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

                <!-- Delete and edit buttons -->
                <div>
                    <button @click="editUniverse(universe.universe_id)" class="text-sm text-gray-500">
                        Edit
                    </button>
                    <button @click="deleteUniverse(universe.universe_id)" class="text-sm text-gray-500">
                        Delete
                    </button>
                </div>

                <!-- Button to manage elements -->
                <div>
                    <button @click="manageElements('universe', universe.universe_id)" class="text-sm text-gray-500">
                        Manage Elements
                    </button>
                </div>
            </div>

            <!-- Button to create new series -->
            <div class="flex items-center">
                <div class="text-sm text-gray-500">
                    <Link :href="`/${universe.universe_id}/series/create/`" class="text-blue-500 hover:text-blue-700"> |
                    Create New Series</Link>
                </div>
            </div>


            <!-- If the universe has any series, display them -->
            <div v-if="universe.series.length != 0">
                <div v-for="series in universe.series" :key="series.series_id" class="p-4  dark:text-white">
                    <h1 class="text-3xl font-bold mb-2">
                        <Link :href="`/series/${series.series_id}`">{{ series.series_title }}</Link>
                    </h1>
                    <p>Summary: {{ series.series_summary }}</p>
                    <p>Genre: {{ series.series_genre }}</p>

                    <!-- Create a delete button -->
                    <button type="button" @click="destroy(series.series_id)"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>

                    <!-- Create a button to edit the series -->
                    <button type="button" @click="editSeries(series.series_id)"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</button>

                    <!-- Create button to add chapter -->
                    <button type="button" @click="addChapter(series.series_id)"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add Chapter</button>

                    <!-- Create button to manage chapters -->
                    <button type="button" @click="manageChapters(series.series_id)"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Manage
                        Chapters</button>

                    <!-- Create button to manage elements -->
                    <button type="button" @click="manageElements('series', series.series_id)"
                        class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Manage
                        Elements</button>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import APICalls from '@/Utilities/APICalls'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/vue3'

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
        const page = usePage()

        APICalls.getUniversesByUserId(page.props.auth.user.id, true).then(response => {
            this.universes = response.data
            this.universeLoaded = true
        }).catch(error => console.log(error))
    },
    methods: {
        editSeries(series_id) {
        },
        addChapter(series_id) {
            // Inertia.get(route('chapters.create', series_id))
            Inertia.get(`/${series_id}/chapters/create/`)
        },
        manageChapters(series_id) {
        },
        editUniverse(universe_id) {
        },
        deleteUniverse(universe_id) {
            if (confirm('Are you sure you want to delete this universe?')) {
                Inertia.delete(route('universes.destroy', universe_id))
            }
        },
        manageElements(type, id) {
        }
    }
}
</script>