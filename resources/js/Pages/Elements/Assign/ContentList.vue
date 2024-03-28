<template>
    <!-- <h1>
        CONTENT
    </h1> -->

    <!-- <button @click="console.log(props.subContentList)">
        Check
    </button> -->

    <div class="overflow-auto mt-8">

        <!-- <div v-for="content in subContentList" :key="content.content_id" class="flex relative items-center"> -->
            <div v-for="content in contentModel" :key="content.content_id" class="flex relative items-center">
            <Checkbox class="flex-grow" :content="content" @checked="(event) => check(content.content_id, event)" />
            <Link :href="route('elements.assign', { contentType: content.type, content_id: content.content_id })"
                v-if="content.type !== 'Page'" class="absolute right-16 text-2xl" :class="{'text-black': content.checked === true}">
                         >>
            </Link>
        </div>

    </div>
</template>

<script setup>
import { defineEmits } from 'vue'
import Checkbox from './Checkbox.vue';

const contentModel = defineModel()

// const props = defineProps({
//     subContentList: {
//         type: Array,
//         required: true
//     }
// })

const emits = defineEmits(['contentChecked'])

// const contentItems = ref(props.subContentList)

function check(contentId, event) {

    // Set the checked value in the contentItems array. Search for object in contentItems that has the same contentId as the one passed in.
    const selectedContent = contentModel.value.find(content => content.content_id === contentId)

    selectedContent.checked = event

    emits('contentChecked', {
        type: selectedContent.type,
        content_id: selectedContent.content_id,
        checked: selectedContent.checked
    })
}
</script>
