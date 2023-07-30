<template>
    <RateSeriesModal :isOpen="this.isOpen" :series="this.series" @close="this.isOpen = false" />
    <!-- Show the series_thumbnail. If it doesn't exist, show black -->

    <!-- <div class="w-full h-96 bg-black">
        <img :src="`${series.series_thumbnail}`" alt="Series Thumbnail" class="w-full h-full object-cover" />
    </div> -->

    <p>
        {{ series.series_id }}
    </p>

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
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" @click="isOpen = true">
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

    <!-- CreateComment with the entire width of the screen -->
    <div class="w-full">
        <CreateComment @comment-created="updateComments()" commentable-type="App\Models\Series"
            :commentable-id="series.series_id" />
    </div>


    <!-- Say there are no comments if there are none -->
    <p v-if="comments.length === 0">There are no comments yet.</p>
    <!-- <Comments @comment-deleted="updateComments()" v-if="commentsLoaded" :comments="comments"></Comments> -->

    <Comments v-if="commentsLoaded" :comments="comments"></Comments>
    <div v-else>
        <p>Loading comments...</p>
    </div>
</template>

<script>
import Comments from '../../Components/Comments.vue'
import CreateComment from '../../Components/Comments/CreateComment.vue'
import RateSeriesModal from '../../Components/Modals/RateSeriesModal.vue'
import APICalls from '@/Utilities/APICalls'

export default {
    props: {
        series: Object,
        universe: Object,
        chapters: Array,
        author: Object,
    },
    components: {
        Comments,
        CreateComment,
        RateSeriesModal,
    },
    data() {
        return {
            comments: [],
            commentsLoaded: false,
            isOpen: false,
        }
    },
    mounted() {

        APICalls.getComments(this.series.series_id, 'series', true).then(response => {
            this.comments = response.data.comments;
            // console.log(this.comments);
            this.commentsLoaded = true;
        }).catch(error => {
            console.log(error);
        })
    },
    methods: {
        updateComments() {
            APICalls.getComments(this.series.series_id, 'series', true).then(response => {
                this.comments = response.data.comments;
                this.commentsLoaded = true;
            }).catch(error => {
                console.log(error);
            })
        },
    }

}


</script>