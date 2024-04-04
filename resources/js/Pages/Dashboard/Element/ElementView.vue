<template>
    <div v-if="elementsLoaded" class="w-full flex flex-wrap  justify-center">
        <div v-for="element in elements" :key="element.element_id" class=" w-96 mx-2 mb-4">

            <content-card-detailed :content="{
        content_id: element.element_id,
        content_name: element.element_name,
        subheading: getElementTypeText(element.derived_element_type),
        thumbnail: element.element_thumbnail,
    }" :link='route("elements.edit", { element: element.element_id, contentType: getParentContentType(), content_id: parentContentId })'
                :selected="element.element_id === selectedElement.element_id"
                :drop-down-menu-options="dropDownMenuOptions" :show-description="false"
                @switch-selected-content="switchSelectedContent" @menu-item-click="handleMenuItemClicked"
                @click="emits('updateMouseClickPosition', $event)" />
        </div>

        <add-button @click="isCreateModalOpen = true" label="Create Element" class="w-96" />

    </div>
    <div v-else>
        <div class="flex justify-center">
            <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-900"></div>
        </div>
    </div>

    <Teleport to="body">
        <Transition name="modal" class="z-50">
            <create-element-modal v-if="isCreateModalOpen" @closeModal="isCreateModalOpen = false; updateContentList()"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>

        <Transition name="modal" class="z-50">
            <delete-modal v-if="isDeleteModalOpen" @closeModal="isDeleteModalOpen = false; updateContentList()"
                :content="{
        content_id: selectedElement.element_id,
        content_name: selectedElement.element_name,
    }" type="elements" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>

    </Teleport>
</template>

<script setup>

import { onActivated, onMounted } from 'vue';
import APICalls from '@/Utilities/APICalls';
import { ref, inject, defineEmits } from 'vue';
import ContentCardDetailed from '../ContentCardDetailed.vue';
import CreateElementModal from './CreateElementModal.vue'
import DeleteModal from '../DeleteModal.vue'
import AddButton from '../AddButton.vue'
import { router } from '@inertiajs/vue3'

const dashboardView = inject('dashboardView')
const parentContentId = inject('parentContentId')

const elements = ref([]);

const isCreateModalOpen = ref(false)
const isDeleteModalOpen = ref(false)

const elementsLoaded = ref(true)

const selectedElement = ref({ element_id: 0, element_name: "" })

const dropDownMenuOptions = [
    { id: 1, text: "Edit", eventName: "edit" },
    { id: 2, text: "View Assigned Content", eventName: "viewAssignedContent" },
    { id: 3, text: "Related Elements", eventName: "relatedElements" },
    { id: 4, text: "Assign Element", eventName: "assignElement" },
    { id: 5, text: "Delete", eventName: "delete" },]

const emits = defineEmits(['updateSize', 'updateMouseClickPosition'])

onActivated(async () => {
    updateContentList()
})

onMounted(async () => {
    updateContentList()
})


function updateContentList() {
    APICalls.getElements(getParentContentType(), parentContentId).then(response => {
        elements.value = response.data
        elementsLoaded.value = true

        emits('updateSize', elements.value.length)
    }).catch(error => console.log(error))
}

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

function getParentContentType() {
    switch (dashboardView) {
        case 'UniverseView':
            return ''
        case 'SeriesView':
            return 'Universe'
        case 'ChapterView':
            return 'Series'
        case 'PageView':
            return 'Chapter'
        default:
            return ''
    }

}

function handleMenuItemClicked(eventName) {
    console.log('handleMenuItemClicked: ' + eventName)
    // switch
    switch (eventName) {
        case "edit":

            console.log('going to edit...')
            router.visit(route("elements.edit", { element: selectedElement.value.element_id, contentype: getParentContentType(), content_id: parentContentId }))
            break;
        case "viewAssignedContent":
            router.post(route('search',
            {
                'searchType': 'content',
            }))
            break;
        // case "relatedElements":
        //     break;
        case "assignElement":
            break;
        case "delete":
            isDeleteModalOpen.value = true
            break;
        default:
            break;
    }
}

function switchSelectedContent(contentId) {

    selectedElement.value = elements.value.find(element => element.element_id == contentId)
}
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: all 0.25s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: scale(1.1);
}
</style>