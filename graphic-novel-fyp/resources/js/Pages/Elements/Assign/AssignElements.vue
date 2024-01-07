<template>
    <div class="p-8 border border-white rounded-lg text-white">
        <!-- Content title here, with option  to go back... -->
        <div>
            Content Title
        </div>

        <!-- Two lists, one for content, one for elements -->
        <div class="flex">
            <div class="flex flex-col items-center">
                <SearchBar />
                <ContentList :parentContent="parentContent" />



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
    preSelectedElementIds: {
        type: Array,
        required: true
    }
})

</script>