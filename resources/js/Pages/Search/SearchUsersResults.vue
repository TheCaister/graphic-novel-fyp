<template>
    Results of users...

    <!-- Loop through resultsList -->
    <div class="flex space-x-8 p-8 flex-wrap">
        <div v-for="user in props.resultsList" :key="user.id" class="shadow-lg w-96">
            <content-card-detailed :content="{
                content_id: user.id,
                content_name: user.username,
                subheading: 'User',
                description: user.bio,
                thumbnail: user.profile_picture,
            }" :link="route('profile.show', user.id)"
                :selected="false" :drop-down-menu-options="[]"
                @switch-selected-content="" @menu-item-click="" />
        </div>
    </div>
</template>

<script setup>
import ContentCardDetailed from '../Dashboard/ContentCardDetailed.vue';

const props = defineProps({
    resultsList: {
        type: Array,
        required: true
    }
})
</script>