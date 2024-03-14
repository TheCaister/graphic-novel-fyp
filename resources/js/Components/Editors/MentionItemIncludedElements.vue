<template>
    <node-view-wrapper class="inline-block text-white">
        <div class="p-2 rounded-lg bg-green-500">
            {{ node.attrs.id.element_name }}
            <button class=" inline-block text-white" @click="removeElement">X</button>
        </div>
    </node-view-wrapper>
</template>

<script setup>
import { NodeViewWrapper } from '@tiptap/vue-3'
import APICalls from '@/Utilities/APICalls';
import DashboardDropdownMenu from '../../Pages/Dashboard/DashboardDropdownMenu.vue';
import { defineEmits, ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3';

const emits = defineEmits(['deleteNode'])

const props = defineProps(
    {
        node: {
            type: Object,
            required: true,
        },
        deleteNode: {
            type: Function,
            required: true,
        },
        editor: {
            type: Object,
            required: true,
        }
    }
)

function removeElement() {
    props.deleteNode()
}
</script>