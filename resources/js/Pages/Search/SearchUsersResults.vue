
<template>
    <!-- Loop through resultsList -->
    <div v-if="resultsList.length > 0" class="flex gap-6 p-8 flex-wrap justify-center">
        <div v-for="user in props.resultsList" :key="user.id" class="shadow-lg w-96">
                <search-card :content="{
                content_id: user.id,
                content_name: user.username,
                subheading: 'User',
                description: user.bio,
                thumbnail: user.profile_picture,
            }" :link="route('profile.show', user.id)"
        :selected="false" :show-tag="false" />
        </div>
    </div>
    <div v-else  class="p-8">
        No users found
    </div>
</template>

<script setup>
import SearchCard from './SearchCard.vue';


const props = defineProps({
    resultsList: {
        type: Array,
        required: true
    }
})
</script>