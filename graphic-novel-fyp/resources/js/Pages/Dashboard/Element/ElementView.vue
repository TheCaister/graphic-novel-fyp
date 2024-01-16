<template>
    <div v-if="elementsLoaded" class="w-full flex flex-wrap">
        <div v-for="element in elements" :key="element.element_id" class="bg-black rounded-lg shadow-md w-2/5 mx-8">

            <div class="relative">
                <Link
                    :href='route("elements.edit", { element: element.element_id, content_type: getParentContentType(), content_id: parentContentId })'
                    class="h-full flex items-center">
                <div class="h-64 w-full bg-pink-300 flex justify-center rounded-lg">
                    <img v-if="element.element_thumbnail" :src="element.element_thumbnail" alt="Element Image"
                        class="w-full h-full rounded-lg" />
                    <div v-else class="text-white text-xl flex items-center">E{{ element.element_id }}</div>
                </div>
                </Link>

                <!-- Create a button on the top right corner -->
                <button class="absolute top-0 right-0 text-white text-2xl mt-4 mr-4">
                    <span class="material-symbols-outlined dark">
                        pending


                    </span>
                    <DashboardDropdownMenu class="absolute z-40" :events="dropDownMenuOptions"
                        @click="switchSelectedContent(element.element_id);" @menuItemClick="handleMenuItemClicked" />
                </button>
            </div>

            <p class="text-white pt-4">{{ element.element_name }}</p>
        </div>

        <button @click="isCreateModalOpen = true" class="bg-black rounded-lg shadow-md w-2/5 mx-8">
            <div class="w-full h-64 flex items-center justify-center rounded-lg">

                <span class="material-symbols-outlined dark"
                    style="font-size: 10rem; font-variation-settings: 'wght' 100; color: #f9a8d4;">
                    add_circle


                </span>
            </div>
            <p class="text-white pt-4 text-center">Create Element</p>
        </button>

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

    </Teleport>
</template>

<script setup>

import { onActivated, onMounted } from 'vue';
import APICalls from '@/Utilities/APICalls';
import { ref, inject, defineEmits } from 'vue';
import DashboardDropdownMenu from '../DashboardDropdownMenu.vue'
import CreateElementModal from './CreateElementModal.vue'

const dashboardView = inject('dashboardView')
const parentContentId = inject('parentContentId')

const elements = ref([]);

const isCreateModalOpen = ref(false)
const isDeleteModalOpen = ref(false)

const elementsLoaded = ref(true)

const selectedElement = ref({ element_id: 0, element_name: "" })

const dropDownMenuOptions = [
    { id: 1, text: "Edit", eventName: "edit" },
    { id: 2, text: "View Assigned Content", eventName: "viewElements" },
    { id: 3, text: "Related Elements", eventName: "relatedElements" },
    { id: 4, text: "Assign Element", eventName: "assignElement" },
    { id: 5, text: "Delete", eventName: "delete" },]

const emits = defineEmits(['updateSize'])

onActivated(async () => {
    updateContentList()
})

onMounted(async () => {
    updateContentList()
})


function updateContentList() {
    APICalls.getElements(getParentContentType(), parentContentId).then(response => {
        elements.value = response.data.elements
        elementsLoaded.value = true

        emits('updateSize', elements.value.length)
    }).catch(error => console.log(error))
}

function getParentContentType() {
    switch (dashboardView) {
        case 'UniverseView':
            return ''
        case 'SeriesView':
            return 'universes'
        case 'ChapterView':
            return 'series'
        case 'PageView':
            return 'chapters'
        default:
            return ''
    }

}

function handleMenuItemClicked(eventName) {
    // switch
    switch (eventName) {
        case "viewElements":
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