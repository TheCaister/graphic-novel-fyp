<template>
    <div>
       
        <div v-if="resultsList.length > 0" class="flex gap-6 p-8 flex-wrap justify-center">

            <div v-for="element in props.resultsList" :key="element.element_id" class="shadow-lg w-96">
                <search-card :content="{
                    content_id: element.element_id,
                    content_name: element.element_name,
                    subheading: getElementTypeText(element.derived_element_type),
                    thumbnail: element.element_thumbnail,
                }" :link="route('elements.show', element.element_id)"
                    :selected="element.element_id === selectedElement.element_id" :show-description="false"
                    :drop-down-menu-options="dropDownMenuOptions" @menu-item-click="handleMenuItemClicked"
                    @switch-selected-content="switchSelectedContent" @click="emits('updateMouseClickPosition', $event)"  />
            </div>
        </div>
        <div v-else class="p-8">
            No elements found
        </div>
    </div>
</template>

<script setup>
import SearchCard from './SearchCard.vue';
import { ref } from 'vue'


const emits = defineEmits(['updateMouseClickPosition'])


const props = defineProps({
    resultsList: {
        type: Array,
        required: true
    }
})

const selectedElement = ref({
    element_id: 0,
    element_name: '',
    element_thumbnail: ''
})




const dropDownMenuOptions = [
    { id: 1, text: "Edit", eventName: "edit", needsAdmin: false },
    { id: 2, text: "Delete", eventName: "delete", needsAdmin: false },
]

function getElementTypeText(text) {
    switch (text) {
        case 'App\\Models\\SimpleTextElement':
            return 'Simple Text'
        case 'App\\Models\\MindmapElement':
            return 'Mindmap'
        case 'App\\Models\\PanelPlannerElement':
            return 'Panel Planner'
    }
}



function switchSelectedContent(contentId) {
    selectedElement.value = props.resultsList.find(element => element.element_id == contentId)
}

function handleMenuItemClicked(eventName) {
    console.log(eventName)
    // switch
    switch (eventName) {
        case "edit":
            break;
        case "viewElements":
            break;
        case "delete":
            break;
        default:
            break;
    }
}
</script>