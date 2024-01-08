<template>
    <div class="p-8 border border-white rounded-lg text-white">
        <!-- Content title here, with option  to go back... -->
        <div>
            <!-- Content Title -->
            {{ parentContent.content_name }}
        </div>

        <!-- Two lists, one for content, one for elements -->
        <div class="flex justify-around">
            <div class="flex flex-col items-center">
                <SearchBar />
                <ContentList :subContentList="subContentList" />



                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Back
                </button>
            </div>
            <div class="flex flex-col items-center">
                <SearchBar />
                <ElementsList :preSelectedElementIds="preSelectedElementIds" />


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

const props = defineProps({
    parentContent: {
        type: Object,
        required: true
    },
    subContentList: {
        type: Array,
        required: true
    },
    preSelectedElementIds: {
        type: Array,
        required: true
    }
})

</script>