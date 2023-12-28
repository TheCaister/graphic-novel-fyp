<template>
    <!-- Loop through the universes and display them in cards -->
    <div v-if="seriesLoaded" class="w-full flex">
        <div v-for="series in series" :key="series.series_id" class="bg-black rounded-lg shadow-md w-2/5 mx-8">
            <button @click="updateDashboard('ChapterView', series.series_id)" class="w-full">
                <div class="h-64 bg-pink-300 flex items-center justify-center rounded-lg relative">
                    <!-- Create a button on the top right corner -->
                    <button @click="test" class="absolute top-0 right-0 text-white text-2xl mt-4 mr-4">
                        <span class="material-symbols-outlined dark">
                            pending
                        </span>
                    </button>

                    <!-- <img v-if="universe.image" :src="universe.image" alt="Universe Image"
                        class="w-full h-full object-cover" />
                    <div v-else class="text-white text-xl">U{{ universe.universe_id }}</div> -->
                    <div class="text-white text-xl">S{{ series.series_id }}</div>
                </div>
                <p class="text-white pt-4">{{ series.series_title }}</p>
            </button>
        </div>

        <button @click="isOpen = true" class="bg-black rounded-lg shadow-md w-2/5 mx-8">
            <div class="w-full h-64 flex items-center justify-center rounded-lg">

                <span class="material-symbols-outlined dark"
                    style="font-size: 10rem; font-variation-settings: 'wght' 100; color: #f9a8d4;">
                    add_circle
                </span>
            </div>
            <p class="text-white pt-4 text-center">Create Series</p>
        </button>

    </div>
    <div v-else>
        <div class="flex justify-center">
            <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-900"></div>
        </div>
    </div>

    <Teleport to="body">
        <Transition name="modal">
            <create-series-modal v-if="isOpen" @closeModal="isOpen = false; updateContentList()" :parentContentIdNumber="props.parentContentId"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>

    </Teleport>
</template>

<script setup>

import { onActivated, onMounted } from 'vue';
import APICalls from '@/Utilities/APICalls';
import { defineEmits, ref, defineProps } from 'vue';
import CreateSeriesModal from './CreateSeriesModal.vue';

const emit = defineEmits(['updateDashboard'])

function updateDashboard(dashboardView, parentContentId) {
    emit('updateDashboard', dashboardView, parentContentId)
}

const series = ref([
    { series_title: "Series 1", series_genre: "Action", series_summary: "Summary of Series 1" },
    { series_title: "Series 2", series_genre: "Fantasy", series_summary: "Summary of Series 2" },
    { series_title: "Series 3", series_genre: "Sci-Fi", series_summary: "Summary of Series 3" },
]);

const props = defineProps({
    parentContentId: {
        type: Number,
        // required: true
    },
})

const isOpen = ref(false)

const seriesLoaded = ref(false)

onActivated(async () => {
    updateContentList()
})

onMounted(async () => {
    updateContentList()
})


function updateContentList() {

    APICalls.getSeriesByUniverseId(props.parentContentId).then(response => {
        series.value = response.data
        seriesLoaded.value = true
    }).catch(error => console.log(error))
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