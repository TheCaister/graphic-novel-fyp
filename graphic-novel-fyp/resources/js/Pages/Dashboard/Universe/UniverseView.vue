<template>
    <!-- Loop through the universes and display them in cards -->
    <div v-if="universeLoaded" class="w-full flex flex-wrap">
        <!-- <div v-for="universe in universes" :key="universe.universe_id" class="bg-black rounded-lg shadow-md w-2/5 mx-8">
            <div class="relative">
                <Link :href='route("universes.show", universe.universe_id)' class="h-full flex items-center">
                <div class="h-64 w-full bg-pink-300 flex justify-center rounded-lg">
                    <img v-if="universe.thumbnail" :src="universe.thumbnail" alt="Universe Image"
                        class="w-full h-full rounded-lg" />
                    <div v-else class="text-white text-xl flex items-center">U{{ universe.universe_id }}</div>
                </div>
                </Link>
                <button class="absolute top-0 right-0 text-white text-2xl mt-4 mr-4">
                    <span @click="switchSelectedContent(universe.universe_id);" class="material-symbols-outlined dark">
                        pending
                    </span>
                    <Transition name="fade">
                        <DashboardDropdownMenu
                            v-if="selectedUniverse.universe_id === universe.universe_id && isCardMenuOpen"
                            class="absolute z-40" :events="dropDownMenuOptions" @menuItemClick="handleMenuItemClicked"
                            @closeMenu="isCardMenuOpen = false" />
                    </Transition>

                </button>
            </div>
            <p class="text-white pt-4">{{ universe.universe_name }}</p>
        </div> -->

        <div v-for="universe in universes" :key="universe.universe_id" class="bg-black rounded-lg shadow-md w-2/5 mx-8">
            <ContentCard :content="{
                content_id: universe.universe_id,
                content_name: universe.universe_name,
                thumbnail: universe.thumbnail
            }" :link="route('universes.show', universe.universe_id)" :selected="universe.universe_id === selectedUniverse.universe_id"/>
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

        <Transition name="modal" class="z-50">
            <delete-universe-modal v-if="isDeleteModalOpen" @closeModal="isDeleteModalOpen = false; updateContentList()"
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
import { onClickOutside } from '@vueuse/core'
import CreateUniverseModal from './CreateUniverseModal.vue';
import EditUniverseModal from './EditUniverseModal.vue';
import DashboardDropdownMenu from '../DashboardDropdownMenu.vue'
import DeleteUniverseModal from './DeleteUniverseModal.vue'
import ContentCard from '../ContentCard.vue'

const page = usePage();

const universes = ref([]);

const isCreateModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const isCardMenuOpen = ref(false)

const universeLoaded = ref(false)

const selectedUniverse = ref({ universe_id: 0, universe_name: "" })

const dropDownMenuOptions = [
    { id: 1, text: "Edit", eventName: "edit" },
    { id: 2, text: "View Elements", eventName: "viewElements" },
    { id: 3, text: "Delete", eventName: "delete" },
]

const props = defineProps({
    parentContentId: {
        type: Number,
    },
})

onActivated(async () => {
    updateContentList()
})

onMounted(async () => {
    updateContentList()
})


function updateContentList() {

    APICalls.getUniversesByUserId(page.props.auth.user.id, false).then(response => {
        universes.value = response.data
        universeLoaded.value = true
    }).catch(error => console.log(error))
}

function handleMenuItemClicked(eventName) {
    console.log(eventName)
    // switch
    switch (eventName) {
        case "edit":
            isEditModalOpen.value = true
            break;
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
    console.log(contentId)
    selectedUniverse.value = universes.value.find(universe => universe.universe_id == contentId)
    isCardMenuOpen.value = true
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

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s, transform 0.2s;
}

.fade-enter-from {
    opacity: 0;
    transform: translateY(-20px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

.fade-enter-to {
    opacity: 1;
    transform: translateY(0);
}
</style>