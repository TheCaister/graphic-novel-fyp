<template>
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

        <!-- Edit and delete buttons -->
        <div v-if="this.user_id == comment.commenter_id">
            <button @click="editComment(comment.comment_id)" class="text-sm text-gray-500">
                Edit
            </button>
            <button @click="deleteComment(comment.comment_id)" class="text-sm text-gray-500">
                Delete
            </button>
        </div>
    </div>
</template>

<script>
import { usePage } from '@inertiajs/vue3';

export default {
    props: {
        comment: {
            type: Object,
        }
    },

    data() {
        return {
            user_id: null,
        }
    },
    emits: ['deleteComment', 'editComment'],

    mounted() {
        const page = usePage();

        this.user_id = page.props.auth.user.id;
    },

    methods: {
            deleteComment(comment_id) {
                this.$emit('deleteComment', comment_id)
            },

            editComment(comment_id) {
                // this.$inertia.put(`/comments/${comment_id}`)
            }
        }
}
</script>