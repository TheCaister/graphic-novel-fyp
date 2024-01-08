<template>
    <h1>
        Here is a list of all the content:
    </h1>

    <div class="h-96 overflow-auto">
        <div v-for="content in subContentList" :key="content.content_id">
            <Checkbox :content="content" @checked="(event) => check(content.content_id, event)" />
        </div>
    </div>
</template>

<script setup>
import { defineProps, ref, defineEmits } from 'vue'
import Checkbox from './Checkbox.vue';

const props = defineProps({
    subContentList: {
        type: Array,
        required: true
    }
})

const emits = defineEmits(['contentChecked'])

const contentItems = ref(props.subContentList)

function check(contentId, event) {

    // Set the checked value in the contentItems array. Search for object in contentItems that has the same contentId as the one passed in.
    const selectedContent = contentItems.value.find(content => content.content_id === contentId)

    selectedContent.checked = event

    emits('contentChecked', {
        type: selectedContent.type,
        content_id: selectedContent.content_id,
        checked: selectedContent.checked
    })
}
</script>