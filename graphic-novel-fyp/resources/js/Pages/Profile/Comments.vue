<template>
    <!-- Cycle through each comment once they are loaded -->
    <div v-for="comment in comments" :key="comment.comment_id">
        <div class="flex items-center">
            <!-- If is_reply is true, say My Reply on. Otherwise, say My Comments on, followed by commmentable_type: commentable_name. Include the link to that commentable -->
            <div class="text-sm text-gray-500">
                <span v-if="comment.is_reply == 1">My Reply on</span>
                <span v-else>My Comment on</span>
                <Link :href="comment.commentable_link" class="text-blue-500 hover:text-blue-700"> | {{ comment.commentable_type }}: {{ comment.commentable_name }}</Link>
            </div>

       


            <div class="flex-shrink-0">
                <!-- <img class="h-10 w-10 rounded-full" :src="comment.user.profile_photo_url" alt=""> -->
                <!-- Set profile photo to the black png -->
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
        </div>
    </div>
</template>

<script>
import APICalls from '@/Utilities/APICalls'
import { usePage } from '@inertiajs/vue3'

export default {
    data() {
        return {
            comments: [],
            commentsLoaded: false,
        }
    },
    mounted() {
        const page = usePage()

        APICalls.getUserComments(page.props.auth.user.id).then(response => {
            this.comments = response.data
            this.commentsLoaded = true

            // console.log(this.comments)
        }).catch(error => console.log(error))
    }
}
</script>