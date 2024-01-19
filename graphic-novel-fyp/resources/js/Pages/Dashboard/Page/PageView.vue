<template>
    <!-- Loop through the universes and display them in cards -->
<<<<<<< Updated upstream
    <div v-if="pagesLoaded" class="w-full flex flex-wrap">
        <div v-for="page in pages" :key="page.page_id" class="bg-black rounded-lg shadow-md w-2/5 mx-8">
            <button @click="isPageManageOpen = true" class="w-full">
                <div class="h-64 bg-pink-300 flex items-center justify-center rounded-lg relative">
                    <!-- Create a button on the top right corner -->
                    <button @click="test" class="absolute top-0 right-0 text-white text-2xl mt-4 mr-4">
                        <span class="material-symbols-outlined dark">
                            pending
                        </span>
                    </button>
=======
    <div v-if="pagesLoaded" class="w-full flex flex-wrap justify-center">
        <div v-for="page in pages" :key="page.page_id" class=" w-64 mx-8 mb-4">
            <button @click="selectedPage = page; isPageManageOpen = true" class="w-full">
                <div class="rounded-lg shadow-lg hover:shadow-white transition-all duration-200">
                    <div class="relative rounded-lg ">
                        <div class="h-full flex items-center">
                            <div class="h-96 w-full bg-pink-300 flex justify-center rounded-lg">
                                <img v-if="page.page_image" :src="page.page_image" alt="Content Image"
                                    class="rounded-lg object-cover w-full" />
                                <div v-else class="text-white text-xl flex items-center">P{{ page.page_id }}</div>
                            </div>
                        </div>
                        <button class="absolute top-0 right-0 text-white text-2xl mt-4 mr-4">
                            <span @click.stop="switchSelectedContent(page.page_id);"
                                class="material-symbols-outlined text-pink-300 drop-shadow-[-2px_2px_0_rgba(0,0,0,1)]">
                                pending
                            </span>
                            <Transition name="fade">
                                <DashboardDropdownMenu
                                    v-if="selectedPage.page_id === page.page_id && isCardMenuOpen === true"
                                    class="absolute z-50" :events="dropDownMenuOptions"
                                    @menuItemClick="handleMenuItemClicked" @closeMenu="isCardMenuOpen = false"
                                    @click.stop />
                            </Transition>
                        </button>
                    </div>
                </div>
>>>>>>> Stashed changes

                    <img v-if="page.page_image" :src="page.page_image" alt="Page Image"
                        class="w-full h-full rounded-lg" />
                    <div v-else class="text-white text-xl">P{{ page.page_number }}</div>
                </div>
                <p class="text-white pt-4">{{ page.page_number }}</p>
            </button>
        </div>

        <button @click="isCreatePageOpen = true" class="bg-black rounded-lg shadow-md w-2/5 mx-8">
            <div class="w-full h-64 flex items-center justify-center rounded-lg">

                <span class="material-symbols-outlined dark"
                    style="font-size: 10rem; font-variation-settings: 'wght' 100; color: #f9a8d4;">
                    add_circle
                </span>
            </div>
            <p class="text-white pt-4 text-center">Create Page</p>
        </button>

    </div>
    <div v-else>
        <div class="flex justify-center">
            <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-900"></div>
        </div>
    </div>

    <Teleport to="body">
        <Transition name="modal">
            <page-manage-modal  v-if="isPageManageOpen" @closeModal="isPageManageOpen = false; updateContentList()"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60"/>
        </Transition>
    </Teleport>

    <Teleport to="body">
        <Transition name="modal">
            <create-page-modal  v-if="isCreatePageOpen" @closeModal="isCreatePageOpen = false; updateContentList()" :parentContentIdNumber="props.parentContentId"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60"/>
        </Transition>
    </Teleport>
</template>

<script setup>

import { onActivated, onMounted } from 'vue';
import APICalls from '@/Utilities/APICalls';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import PageManageModal from './PageManageModal.vue'
import CreatePageModal from './CreatePageModal.vue'

const props = defineProps({
    parentContentId: {
        type: Number,
        required: true
    },
})

const page = usePage();

const pages = ref([]);

const isPageManageOpen = ref(false)

const isCreatePageOpen = ref(false)

const pagesLoaded = ref(false)

onActivated(async () => {
    updateContentList()
})

onMounted(async () => {
    updateContentList()
})


function updateContentList() {
    APICalls.getPagesByChapterId(props.parentContentId).then(response => {
        pages.value = response.data
        pagesLoaded.value = true
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