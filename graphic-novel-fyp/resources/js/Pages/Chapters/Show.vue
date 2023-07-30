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

    <!-- Page component with pages[pageNumber] passed as prop -->
    <Pages v-if="pageLoaded" :page="pages[pageNumber - 1]" @previousPage="changePage(-1)" @nextPage="changePage(1)" />

    <!-- Tailwind pagination component -->
    <!-- <TailwindPagination
        :data="laravelData"
        @pagination-change-page="getResults"
    /> -->

    <div class="w-full">
        <CreateComment @comment-created="updateComments()" commentable-type="App\Models\Chapter"
            :commentable-id="chapter.chapter_id" />
    </div>


    <!-- Comments component with comments passed as prop -->
    <p v-if="comments.length === 0">There are no comments yet.</p>
    <Comments @comment-deleted="updateComments()" v-if="commentsLoaded" :comments="comments"></Comments>
    <div v-else>
        <p>Loading comments...</p>
    </div>
</template>

<script>
import Pages from './Components/Pages.vue'
import Comments from '../../Components/Comments.vue'
import CreateComment from '../../Components/Comments/CreateComment.vue'
import APICalls from '../../Utilities/APICalls.js'


export default {
    props: {
        chapter: Object,
        series: Object,
    },

    components: {
        Pages,
        Comments,
        CreateComment
    },

    // Set the page number to the page number of the first page
    data() {
        return {
            pages: [],
            pageNumber: 1,
            pageLoaded: false,
            comments: [],
            commentsLoaded: false,
        }
    },

    // Find the page object with the page number
    methods: {

        // Method to change the page number
        changePage(pageNumber) {
            this.pageNumber = this.pageNumber + pageNumber;

            // If the page number is less than 1, set it to the last page
            if (this.pageNumber < 1) {
                this.pageNumber = this.pages.length;
            }
            // If the page number is greater than the last page, set it to the first page
            else if (this.pageNumber > this.pages.length) {
                this.pageNumber = 1;
            }
        },

        updateComments() {
            APICalls.getComments(this.chapter.chapter_id, 'chapter', true).then(response => {
                this.comments = response.data.comments;
                this.commentsLoaded = true;
            }).catch(error => {
                console.log(error);
            })
        }
    },

    mounted() {
        APICalls.getPagesByChapterId(this.chapter.chapter_id).then(response => {
            this.pages = response.data;
            this.pageNumber = this.pages[0].page_number;
            this.pageLoaded = true;
        }).catch(error => {
            console.log(error);
        })

        APICalls.getComments(this.chapter.chapter_id, 'chapter', true).then(response => {
            this.comments = response.data.comments;
            this.commentsLoaded = true;
        }).catch(error => {
            console.log(error);
        })
    }
}
</script>
