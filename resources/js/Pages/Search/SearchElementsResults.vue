<template>
    <div class="flex gap-6 p-8 flex-wrap justify-center">
        <div v-for="element in props.resultsList" :key="element.element_id" class="shadow-lg w-96">
            <search-card :content="{
            content_id: element.element_id,
            content_name: element.element_name,
            subheading: getElementTypeText(element.derived_element_type),
            thumbnail: element.element_thumbnail,
        }" :link="route('elements.show', element.element_id)"
        :selected="false" :show-description="false" />
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

function getElementTypeText(text){
    switch(text){
        case 'App\\Models\\SimpleTextElement':
            return 'Simple Text'
        case 'App\\Models\\MindmapElement':
            return 'Mindmap'
        case 'App\\Models\\PanelPlannerElement':
            return 'Panel Planner'
    }
}
</script>