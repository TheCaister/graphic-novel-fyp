<template>
    <!-- Loop through the universes and display them in cards -->
    <div v-if="chaptersLoaded" class="w-full flex flex-wrap  justify-center">

        <div v-for="chapter in chapters" :key="chapter.chapter_id" class="w-96 mx-2 mb-4">
            <content-card-detailed :content="{
        content_id: chapter.chapter_id,
        content_name: chapter.chapter_title,
        subheading: 'Chapter ' + chapter.chapter_number,
        description: chapter.chapter_notes,
        thumbnail: chapter.chapter_thumbnail,
    }" :link="route('chapters.show', chapter.chapter_id)"
                :selected="chapter.chapter_id === selectedChapter.chapter_id"
                :drop-down-menu-options="dropDownMenuOptions" @switch-selected-content="switchSelectedContent"
                @menu-item-click="handleMenuItemClicked" @click="emits('updateMouseClickPosition', $event)" />
        </div>

        <add-button @click="isCreateModalOpen = true" label="Create Chapter" class="w-96" />

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

        <Transition name="modal" class="z-50">
            <edit-chapter-modal v-if="isEditModalOpen" @closeModal="isEditModalOpen = false; updateContentList()"
                :chapter="selectedChapter"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>

        <Transition name="modal" class="z-50">
            <delete-modal v-if="isDeleteModalOpen" @closeModal="isDeleteModalOpen = false; updateContentList()"
                :content="{
        content_id: selectedChapter.chapter_id,
        content_name: selectedChapter.chapter_title,
    }" type="chapters" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>

    </Teleport>
</template>

<script setup>

import { onActivated, onMounted } from 'vue';
import APICalls from '@/Utilities/APICalls';
import { usePage, router } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';
import CreateChapterModal from './CreateChapterModal.vue';
import EditChapterModal from './EditChapterModal.vue';
import DeleteModal from '../DeleteModal.vue';
import ContentCardDetailed from '../ContentCardDetailed.vue';
import AddButton from '../AddButton.vue'


const page = usePage();

const chapters = ref([]);

const isCreateModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)

const chaptersLoaded = ref(false)

const emits = defineEmits(['updateMouseClickPosition'])

const selectedChapter = ref({
    chapter_id: null,
    chapter_title: null,
    chapter_notes: null,
    chapter_thumbnail: null,
})

const dropDownMenuOptions = [
    { id: 1, text: "Edit", eventName: "edit" },
    { id: 2, text: "Assign Elements", eventName: "assignElements" },
    { id: 3, text: "Delete", eventName: "delete" },
]
    // { id: 2, text: "View Elements", eventName: "viewElements" },

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
            router.visit(route('elements.assign.get-parent', { type: 'Chapter', content_id: selectedChapter.value.chapter_id }))
            break;
        case "delete":
            isDeleteModalOpen.value = true
            break;
        default:
            break;
    }
}

function switchSelectedContent(contentId) {
    selectedChapter.value = chapters.value.find(chapter => chapter.chapter_id == contentId)
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