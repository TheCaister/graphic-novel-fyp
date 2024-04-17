<template>
    <!-- Loop through the universes and display them in cards -->
    <div v-if="universeLoaded" class="w-full flex flex-wrap justify-center">
        <div v-for="universe in universes" :key="universe.universe_id" class=" w-96 mx-2 mb-4 ">
            <ContentCard :content="{
                content_id: universe.universe_id,
                content_name: universe.universe_name,
                thumbnail: universe.thumbnail
            }" :link="route('universes.show', universe.universe_id)"
                :selected="universe.universe_id === selectedUniverse.universe_id" :drop-down-menu-options="dropDownMenuOptions.filter(option =>
                    !option.needsAdmin || (option.needsAdmin && universe.can_edit)
                )" @switch-selected-content="switchSelectedContent" @menu-item-click="handleMenuItemClicked"
                @click="emits('updateMouseClickPosition', $event)" class="relative" >
            <div v-if="$page.props.auth.user.id !== universe.owner_id" class="absolute top-0 left-0 rounded-full bg-black p-2 mt-4 ml-4">
                Moderating
            </div>
            </ContentCard>
        </div>

        <add-button @click="isCreateModalOpen = true" label="Create Universe" class="w-96" />

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
                :universe="selectedUniverse"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>

        <Transition name="modal" class="z-50">
            <delete-modal v-if="isDeleteModalOpen" @closeModal="isDeleteModalOpen = false; updateContentList()" :content="{
                content_id: selectedUniverse.universe_id,
                content_name: selectedUniverse.universe_name,
            }" type="universes" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
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
import ContentCard from '../ContentCard.vue'
import AddButton from '../AddButton.vue'
import DeleteModal from '../DeleteModal.vue'

const page = usePage();

const universes = ref([]);

const isCreateModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)

const emits = defineEmits(['updateMouseClickPosition'])

const universeLoaded = ref(false)

const selectedUniverse = ref({ universe_id: 0, universe_name: "" })

const dropDownMenuOptions = [
    { id: 1, text: "Edit", eventName: "edit", needsAdmin: true },
    // { id: 2, text: "View Elements", eventName: "viewElements", needsAdmin: false },
    { id: 2, text: "Delete", eventName: "delete", needsAdmin: true },
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
        console.log(universes.value)
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
    selectedUniverse.value = universes.value.find(universe => universe.universe_id == contentId)
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