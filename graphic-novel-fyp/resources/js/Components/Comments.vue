<template>
    <!-- Display every comment -->
    <div v-for="comment in comments" :key="comment.comment_id">
        <!-- Display profile photo -->
        <div class="flex-shrink-0">
            <img class="h-10 w-10 rounded-full" src="/assets/black_page.jpg" alt="">
        </div>
        <div class="ml-4">
            <div class="text-sm font-medium text-gray-900">
                {{ comment.commenter_id }}
            </div>
            <div class="text-sm text-gray-500">
                {{ comment.comment_content }}
            </div>
        </div>

        <!-- Indent and display every reply -->
        <!-- Only display replies if they exist -->
        <div v-if="comment.replies.length != 0">

            <!-- Display replies button -->
            <button @click="comment.display_replies = !comment.display_replies" class="text-sm text-gray-500">
                <span v-if="comment.display_replies">Hide Replies</span>
                <span v-else>Show Replies</span>
            </button>

            <TransitionGroup name="fade">
                <div v-if="comment.display_replies" v-for="reply in comment.replies" :key="reply.comment_id" class="px-20">
                    <div class="flex items-center">
                        <!-- Display profile photo -->
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="/assets/black_page.jpg" alt="">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ reply.commenter_id }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ reply.comment_content }}
                            </div>
                        </div>
                    </div>

                </div>

            </TransitionGroup>



        </div>

        <div v-else>
            No replies
        </div>



    </div>
</template>

<script>
export default {
    props: {
        comments: {
            type: Array,
        }
    },
    mounted() {
        // For each comment, attach a display_replies boolean
        this.comments.forEach(comment => {
            comment.display_replies = false
        })
    }

}
</script>

<style>
.fade-enter-from {
    /* Start from the top of the screen */
    opacity: 0;
    transform: translateY(-100px);
}

.fade-enter-to {
    opacity: 1;
}

.fade-enter-active {
    transition: all 0.5s ease;
}

.fade-leave-from {
    opacity: 1;
}

.fade-leave-to {
    opacity: 0;
}

.fade-leave-active {
    transition: all 0.5s ease;
}
</style>