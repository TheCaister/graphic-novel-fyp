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
    </div>
</template>

<script>
export default {
    props: {
        comments: {
            type: Array,
        }
    }
}
</script>