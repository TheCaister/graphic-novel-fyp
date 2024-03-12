<template>
    <node-view-wrapper class="inline-block">
        <button @click="console.log('test')">Epic Mention</button>
    </node-view-wrapper>
</template>

<script setup>
import { NodeViewWrapper } from '@tiptap/vue-3'
import APICalls from '@/Utilities/APICalls';

function getElement(element_id) {
    APICalls.getElement(element_id).then(response => {
        console.log('returning')
        return response.data.element_name
    }).catch(error => console.log(error))
}

const props = defineProps(
    {
        node: {
            type: Object,
            required: true,
        },
        updateAttributes: {
            type: Function,
            required: true,
        },
    
    }
)
</script>
