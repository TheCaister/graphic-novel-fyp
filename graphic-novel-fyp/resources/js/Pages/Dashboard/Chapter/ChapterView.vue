<template>
    <!-- Loop through the universes and display them in cards -->
    <div v-if="chaptersLoaded" class="w-full flex">

        <div v-for="chapter in chapters" :key="chapter.chapter_id" class=" w-2/5 mx-8 mb-4">
            <content-card-detailed :content="{
                content_id: series.series_id,
                content_name: series.series_title,
                subheading: series.series_genre,
                description: series.series_summary,
                thumbnail: series.series_thumbnail,
            }" :link="route('series.show', series.series_id)"
                :selected="series.series_id === selectedSeries.series_id"
                :drop-down-menu-options="dropDownMenuOptions" @switch-selected-content="switchSelectedContent"
                @menu-item-click="handleMenuItemClicked" />
        </div>

        <add-button @click="isCreateModalOpen = true" label="Create Chapter"/>

    </div>
    <div v-else>
        <div class="flex justify-center">
            <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-900"></div>
        </div>
    </div>

    <Teleport to="body">
        <Transition name="modal">
            <create-chapter-modal v-if="isCreateModalOpen" @closeModal="isCreateModalOpen = false; updateContentList()"
                :parentContentIdNumber="props.parentContentId"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>

    </Teleport>
</template>

<script setup>

import { onActivated, onMounted } from 'vue';
import APICalls from '@/Utilities/APICalls';
import { usePage } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';
import CreateChapterModal from './CreateChapterModal.vue';
import ContentCardDetailed from '../ContentCardDetailed.vue';
import AddButton from '../AddButton.vue'

const page = usePage();

const chapters = ref([]);

const isCreateModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)

const chaptersLoaded = ref(false)

const props = defineProps({
    parentContentId: {
        type: Number,
        required: true
    },
})

onActivated(async () => {
    updateContentList()
})

onMounted(async () => {
    updateContentList()
})


function updateContentList() {
    APICalls.getChaptersBySeriesId(props.parentContentId).then(response => {
        chapters.value = response.data
        chaptersLoaded.value = true
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