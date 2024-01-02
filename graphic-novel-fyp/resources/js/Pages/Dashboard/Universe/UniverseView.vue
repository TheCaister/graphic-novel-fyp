<template>
    <!-- Loop through the universes and display them in cards -->
    <div v-if="universeLoaded" class="w-full flex flex-wrap">
        <div v-for="universe in universes" :key="universe.universe_id" class="bg-black rounded-lg shadow-md w-2/5 mx-8">

            <div class="relative">
                <Link :href='route("universes.show", universe.universe_id)' class="h-full flex items-center">
                <div class="h-64 w-full bg-pink-300 flex justify-center rounded-lg">
                    <img v-if="universe.thumbnail" :src="universe.thumbnail" alt="Universe Image"
                        class="w-full h-full rounded-lg" />
                    <div v-else class="text-white text-xl flex items-center">U{{ universe.universe_id }}</div>
                </div>
                </Link>

                <!-- Create a button on the top right corner -->
                <button class="absolute top-0 right-0 text-white text-2xl mt-4 mr-4">
                    <span class="material-symbols-outlined dark">
                        pending


                    </span>
                    <DashboardDropdownMenu class="absolute z-40" :events="dropDownMenuOptions"
                        @click="switchSelectedContent(universe.universe_id);" @menuItemClick="handleMenuItemClicked" />
                </button>
            </div>

            <p class="text-white pt-4">{{ universe.universe_name }}</p>



        </div>

        <button @click="isCreateModalOpen = true" class="bg-black rounded-lg shadow-md w-2/5 mx-8">
            <div class="w-full h-64 flex items-center justify-center rounded-lg">

                <span class="material-symbols-outlined dark"
                    style="font-size: 10rem; font-variation-settings: 'wght' 100; color: #f9a8d4;">
                    add_circle


                </span>
            </div>
            <p class="text-white pt-4 text-center">Create Universe</p>
        </button>

    </div>
    <div v-else>
        <div class="flex justify-center">
            <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-900"></div>
        </div>
    </div>

    <Teleport to="body">
        <Transition name="modal" class="z-50">
            <create-universe-modal v-if="isCreateModalOpen" @closeModal="isCreateModalOpen = false; updateContentList()"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>

        <Transition name="modal" class="z-50">
            <edit-universe-modal v-if="isEditModalOpen" @closeModal="isEditModalOpen = false; updateContentList()"
                :universe="selectedUniverse" :key="selectedUniverse.universe_id"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>

    </Teleport>
</template>

<script setup>

import { onActivated, onMounted } from 'vue';
import APICalls from '@/Utilities/APICalls';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import CreateUniverseModal from './CreateUniverseModal.vue';
import EditUniverseModal from './EditUniverseModal.vue';
import DashboardDropdownMenu from '../DashboardDropdownMenu.vue'

const page = usePage();

const universes = ref([
    { universe_id: 1, universe_name: "Universe 1" },
    { universe_id: 2, universe_name: "Universe 2" },
    { universe_id: 3, universe_name: "Universe 3" },

    // Add more universes as needed
]);

const isCreateModalOpen = ref(false)
const isEditModalOpen = ref(false)

const universeLoaded = ref(false)

const selectedUniverse = ref({ universe_id: 0, universe_name: "" })

const dropDownMenuOptions = [
    { id: 1, text: "Edit", eventName: "edit" },
    { id: 2, text: "View Elements", eventName: "viewElements" },
    { id: 3, text: "Delete", eventName: "delete" },
]

onActivated(async () => {
    updateContentList()
})

onMounted(async () => {
    updateContentList()
})


function updateContentList() {
    console.log('updateContentList')
    console.log(universes)

    APICalls.getUniversesByUserId(page.props.auth.user.id, false).then(response => {
        universes.value = response.data
        universeLoaded.value = true
    }).catch(error => console.log(error))
}

function test() {
    console.log('test')
}

function handleMenuItemClicked(eventName) {
    // switch
    switch (eventName) {
        case "edit":
            isEditModalOpen.value = true
            break;
        case "viewElements":
            break;
        case "delete":
            break;
        default:
            break;
    }
}

function switchSelectedContent(contentId) {

    selectedUniverse.value = universes.value.find(universe => universe.universe_id == contentId)
    console.log(selectedUniverse.value)
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