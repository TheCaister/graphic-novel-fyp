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
                    @switch-selected-content="switchSelectedContent"
                    @click="emits('updateMouseClickPosition', $event)" />
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
import { router } from '@inertiajs/vue3'


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
    { id: 1, text: "Edit", eventName: "edit" },
    { id: 2, text: "View Assigned Content", eventName: "viewAssignedContent" },
    { id: 4, text: "Assign Element", eventName: "assignElement" },
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
    console.log('handleMenuItemClicked: ' + eventName)
    // switch
    switch (eventName) {
        case "edit":
            router.visit(route('elements.show', selectedElement.value.element_id))
            break;
        case "viewAssignedContent":
            router.post(route('search', {
                'searchType': 'content',
            }), {
                'advanced': {
                    'includedElements': [
                        {
                            'label': selectedElement.value.element_name,
                            'id': selectedElement.value.element_id
                        }
                    ]
                },
                'limit': 10
            });
            break;
        case "assignElement":
            router.post(route('elements.assign.get-parent.post', {
                type: selectedElement.value.pivot.elementable_type,
                content_id: selectedElement.value.pivot.elementable_id
            }), {
                preSelectedElements: [selectedElement.value]
            });
            break;
        default:
            break;
    }
}
</script>