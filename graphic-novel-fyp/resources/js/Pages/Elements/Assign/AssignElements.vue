<template>
    <div class="p-8 border border-white rounded-lg text-white">
        <!-- Content title here, with option  to go back... -->
        <div class="flex">
            <button>
                <span class="material-symbols-outlined dark">
                    chevron_left
                </span>
            </button>


            <div>
                <!-- Content Title -->
                {{ parentContent.content_name }}
            </div>
        </div>

        <!-- Two lists, one for content, one for elements -->
        <div class="flex justify-around">
            <div class="flex flex-col items-center">
                <SearchBar />
                <ContentList :subContentList="subContentList" @content-checked="(event) => updateSelectedContentList(event)" />



                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Back
                </button>
            </div>
            <div class="flex flex-col items-center">
                <SearchBar />
                <ElementsList :elementList="elementList" :preSelectedElementIds="preSelectedElementIds" />


                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Save
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import ElementsList from './ElementsList.vue';
import ContentList from './ContentList.vue';
import SearchBar from './SearchBar.vue';
import { useForm, } from '@inertiajs/vue3';

const props = defineProps({
    parentContent: {
        type: Object,
        required: true
    },
    subContentList: {
        type: Array,
        required: true
    },
    elementList:
    {
        type: Array,
        required: true
    },
    preSelectedElementIds: {
        type: Array,
        required: true
    }
})

const form = useForm({
    selectedContentList: [],
    selectedElementList: [],
})

function updateSelectedContentList(event) {
    // First, check if the content is already in the selectedContentList by comparing the contentId. If it is, remove it from the list. If it isn't, add it to the list.
    const selectedContent = form.selectedContentList.find(content => content.type === event.type && content.content_id === event.content_id)

    if (selectedContent) {
        // Remove the content from the list
        form.selectedContentList = form.selectedContentList.filter(content => content.content_id !== event.content_id)
    } else {
        // Add the content to the list
        form.selectedContentList.push({
            content_id: event.content_id,
            type: event.type,
            checked: event.checked
        })
    }

    // console.log(form.selectedContentList)
}

</script>