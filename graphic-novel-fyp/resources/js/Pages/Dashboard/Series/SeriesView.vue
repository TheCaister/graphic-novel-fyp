<template>
    <!-- Loop through the universes and display them in cards -->
    <div v-if="seriesLoaded" class="w-full flex">
        <div v-for="series in series" :key="series.series_id" class=" w-2/5 mx-8 mb-4">
            <content-card-detailed :content="{
                content_id: series.series_id,
                content_name: series.series_title,
                thumbnail: series.series_thumbnail,
            }" :link="route('series.show', series.series_id)"
                :selected="series.series_id === selectedSeries.series_id"
                :drop-down-menu-options="dropDownMenuOptions" @switch-selected-content="switchSelectedContent"
                @menu-item-click="handleMenuItemClicked" />
        </div>

        <add-button @click="isCreateModalOpen = true" label="Create Series"/>

    </div>
    <div v-else>
        <div class="flex justify-center">
            <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-900"></div>
        </div>
    </div>

    <Teleport to="body">
        <Transition name="modal">
            <create-series-modal v-if="isCreateModalOpen" @closeModal="isCreateModalOpen = false; updateContentList()"
                :parentContentIdNumber="props.parentContentId"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>

        <Transition name="modal" class="z-50">
            <edit-series-modal v-if="isEditModalOpen" @closeModal="isEditModalOpen = false; updateContentList()"
                :series="selectedSeries"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>

        <Transition name="modal" class="z-50">
            <delete-modal v-if="isDeleteModalOpen" @closeModal="isDeleteModalOpen = false; updateContentList()" :content="{
                content_id: selectedSeries.series_id,
                content_name: selectedSeries.series_title,
            }" type="series" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>

    </Teleport>
</template>

<script setup>

import { onActivated, onMounted } from 'vue';
import APICalls from '@/Utilities/APICalls';
import { ref, defineProps } from 'vue';
import CreateSeriesModal from './CreateSeriesModal.vue';
import AddButton from '../AddButton.vue'
import ContentCardDetailed from '../ContentCardDetailed.vue';
import DeleteModal from '../DeleteModal.vue'
import EditSeriesModal from './EditSeriesModal.vue';

const series = ref([]);

const props = defineProps({
    parentContentId: {
        type: Number,
    },
})

const isCreateModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)

const dropDownMenuOptions = [
    { id: 1, text: "Edit", eventName: "edit" },
    { id: 2, text: "View Elements", eventName: "viewElements" },
    { id: 3, text: "Assign Elements", eventName: "assignElements" },
    { id: 3, text: "Delete", eventName: "delete" },
]

const seriesLoaded = ref(false)

const selectedSeries = ref({ series_id: 0, series_title: "" })

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

function handleMenuItemClicked(eventName) {
    console.log(eventName)
    // switch
    switch (eventName) {
        case "edit":
            isEditModalOpen.value = true
            break;
        case "viewElements":
            break;
        case "assignElements":
            break;
        case "delete":
            isDeleteModalOpen.value = true
            break;
        default:
            break;
    }
}

function switchSelectedContent(contentId) {
    selectedSeries.value = series.value.find(series => series.series_id == contentId)
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