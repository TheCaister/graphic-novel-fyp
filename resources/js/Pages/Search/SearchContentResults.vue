<template>
    Results of content...
    <button @click="console.log(props.resultsList)">
        Show List
    </button>

    <!-- <div class="flex">
        <div v-for="result in props.resultsList" :key="result.id" class="shadow-lg ring-2 ring-blue-500">
            <Link :href="result.link">
                <div>
                    {{ result.title }}
                </div>
            </Link>
        </div>
    </div> -->

    <div class="flex space-x-8 p-8 flex-wrap">
        <div v-for="content in props.resultsList" :key="content.id" class="shadow-lg w-96">
            <search-card :content="{
            content_id: content.id,
            content_name: content.title,
            subheading: content.type.charAt(0).toUpperCase() + content.type.slice(1),
            thumbnail: content.thumbnail,
        }" :link="content.link"
        :selected="false" :show-description="false"/>
        </div>
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