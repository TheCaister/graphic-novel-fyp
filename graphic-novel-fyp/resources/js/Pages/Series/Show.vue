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

    <!-- Create a button to bring up a rating overlay -->
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
        Rate
    </button>

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

    <h1>Comments</h1>
    <!-- Say there are no comments if there are none -->
    <p v-if="comments.length === 0">There are no comments yet.</p>
    <Comments v-if="commentsLoaded" :comments="comments"></Comments>
    <div v-else>
        <p>Loading comments...</p>
    </div>
</template>

<script>
import Comments from '../../Components/Comments.vue'

export default {
    props: {
        series: Object,
        universe: Object,
        chapters: Array,
        author: Object,
    },
    components: {
        Comments,
    },
    data() {
        return {
            comments: [],
            commentsLoaded: false,
        }
    },
    mounted() {
        axios.get('/api/comments', {
            params: {
                commentable_id: this.series.series_id,
                commentable_type: 'series',
                attach_replies: true,
            }
        }).then(response => {
            this.comments = response.data
            this.commentsLoaded = true

            console.log(this.comments)
        }).catch(error => console.log(error))
    },

}


</script>