<template>
    <div v-if="elementsLoaded" class="w-full flex flex-wrap">
        <div v-for="element in elements" :key="element.element_id" class="bg-black rounded-lg shadow-md w-2/5 mx-8">

            <div class="relative">
                <!-- <Link :href='route("universes.show", .universe_id)' class="h-full flex items-center"> -->
                <div class="h-64 w-full bg-pink-300 flex justify-center rounded-lg">
                    <img v-if="element.element_thumbnail" :src="element.element_thumbnail" alt="Element Image"
                        class="w-full h-full rounded-lg" />
                    <div v-else class="text-white text-xl flex items-center">E{{ element.element_id }}</div>
                </div>
                <!-- </Link> -->

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
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import DashboardDropdownMenu from '../DashboardDropdownMenu.vue'
import CreateElementModal from './CreateElementModal.vue'

const page = usePage();

const elements = ref([
    {
        element_id: 0,
        element_name: "",
        element_thumbnail: ""
    
    },
    {
        element_id: 1,
        element_name: "",
        element_thumbnail: ""
    },

]);

const isCreateModalOpen = ref(false)
const isDeleteModalOpen = ref(false)

const elementsLoaded = ref(true)

const selectedElement = ref({ element_id: 0, element_name: "" })

const dropDownMenuOptions = [
    { id: 1, text: "Edit", eventName: "edit" },
    { id: 2, text: "View Assigned Content", eventName: "viewElements" },
    { id: 3, text: "Related Elements", eventName: "relatedElements" },
    { id: 4, text: "Assign Element", eventName: "assignElement" },
    { id: 5, text: "Delete", eventName: "delete" },
]

onActivated(async () => {
    updateContentList()
})

onMounted(async () => {
    updateContentList()
})


function updateContentList() {
    APICalls.getElements('type', 2).then(response => {
        elements.value = response.data
        elementsLoaded.value = true
    }).catch(error => console.log(error))
}

function test() {
    console.log('test')
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