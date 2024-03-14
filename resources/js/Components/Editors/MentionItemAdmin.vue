<template>
    <node-view-wrapper class="inline-block text-white">
        <button class="p-2 rounded-lg bg-green-500">
            {{ node.attrs.id.username }}
            <span class="text-white">X</span>
        </button>
    </node-view-wrapper>
</template>

<script setup>
import { NodeViewWrapper } from '@tiptap/vue-3'
import APICalls from '@/Utilities/APICalls';
import DashboardDropdownMenu from '../../Pages/Dashboard/DashboardDropdownMenu.vue';
import { defineEmits, ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3';

// update username instead
// function setElementName(element_id) {
//     APICalls.getElement(element_id).then(response => {
//         console.log('returning')
//         // return response.data.element_name
//         console.log(response.data.element_name)
//         props.updateAttributes({
//             id: {
//                 ...props.node.attrs.id,
//                 element_name: response.data.element_name
//             }
//         })
//     }).catch(error => console.log(error))
// }

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
})
</script>