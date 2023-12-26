<template>
    <!-- Loop through the universes and display them in cards -->
    <div v-if="universeLoaded" class="w-full flex">
        <div v-for="universe in universes" :key="universe.universe_id" class="bg-black rounded-lg shadow-md w-2/5 mx-8">
            <button @click="updateDashboard('SeriesView', universe.universe_id)" class="w-full">
                <div class="h-64 bg-pink-300 flex items-center justify-center rounded-lg relative">
                    <!-- Create a button on the top right corner -->
                    <button @click="test" class="absolute top-0 right-0 text-white text-2xl mt-4 mr-4">
                        <span class="material-symbols-outlined dark">
                            pending
                        </span>
                    </button>

                    <img v-if="universe.image" :src="universe.image" alt="Universe Image"
                        class="w-full h-full object-cover" />
                    <div v-else class="text-white text-xl">U{{ universe.universe_id }}</div>
                </div>
                <p class="text-white pt-4">{{ universe.universe_name }}</p>
            </button>
        </div>

        <button @click="isOpen = true" class="bg-black rounded-lg shadow-md w-2/5 mx-8">
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
        <Transition name="modal">
            <create-universe-modal v-if="isOpen" @closeModal="isOpen = false; updateContentList()"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>

    </Teleport>
</template>

<script setup>

import { onActivated, onMounted } from 'vue';
import APICalls from '@/Utilities/APICalls';
import { usePage } from '@inertiajs/vue3';
import { defineEmits, ref } from 'vue';
// import CreateUniverseModal from '../CreateUniverseModal.vue';

const emit = defineEmits(['updateDashboard'])

function updateDashboard(dashboardView, parentContentId) {
    emit('updateDashboard', dashboardView, parentContentId)
}

const page = usePage();

const universes = ref([
    { universe_id: 1, universe_name: "Universe 1" },
    { universe_id: 2, universe_name: "Universe 2" },
    { universe_id: 3, universe_name: "Universe 3" },

    // Add more universes as needed
]);

const isOpen = ref(false)

const universeLoaded = ref(false)

onActivated(async () => {
    updateContentList()
})

onMounted(async () => {
    updateContentList()
})


function updateContentList() {
    console.log('updateContentList')
    console.log(universes)

    APICalls.getUniversesByUserId(page.props.auth.user.id, true).then(response => {
        universes.value = response.data
        universeLoaded.value = true
    }).catch(error => console.log(error))
}

function test(){
    console.log('test')
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