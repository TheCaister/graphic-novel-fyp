<template>
    <node-view-wrapper class="inline-block text-white">
        <div class="p-2 rounded-lg bg-green-500">
            {{ node.attrs.id.label }}
            <button class=" inline-block text-white" @click="removeElement">X</button>
        </div>
    </node-view-wrapper>
</template>

<script setup>
import { NodeViewWrapper } from '@tiptap/vue-3'
import { defineEmits, ref, onMounted } from 'vue'

const emits = defineEmits(['removeMentionItem'])

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
        editor: {
            type: Object,
            required: true,
        }
    }
)

onMounted(() => {
    // console.log(props.editor.contentComponent)
    // props.editor.contentComponent.emit('hi')
    // console.log(props.node)
})

function removeElement() {
    props.editor.contentComponent.emit('removeMentionItem', props.node.attrs.id.id)
    props.deleteNode()
}
</script>