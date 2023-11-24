<template>
    <!-- Display every comment -->
    <div v-for="comment in comments.slice().reverse()" :key="comment.comment_id">

        <CommentSingle @deleteComment="deleteComment" :comment="comment" />

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
                    <CommentSingle @deleteComment="deleteComment" :comment="reply" />
                </div>

            </TransitionGroup>
        </div>
    </div>
</template>

<script>
import CommentSingle from './CommentSingle.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    components: {
        CommentSingle,
    },
    props: {
        comments: {
            type: Array,
        }
    },
    emits: ['commentDeleted'],

    methods: {
        deleteComment(comment_id) {

            // Use Inertia.delete to delete the comment, preserving the scroll position
            Inertia.delete(route('comments.destroy', comment_id))
        },
    }
}
</script>

<style>
.fade-enter-from {
    /* Start from the top of the screen */
    opacity: 0;
    transform: rotate3d(1, 43, 23, 64deg);

}

.fade-enter-to {
    opacity: 1;
}

.fade-enter-active {
    transition: all 1s ease;
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