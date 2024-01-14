<style>
/* import the necessary styles for Vue Flow to work */
@import '@vue-flow/core/dist/style.css';

/* import the default theme, this is optional but generally recommended */
@import '@vue-flow/core/dist/theme-default.css';
</style>


<script setup>

// The nodes and edges can be bound to props that I will pass in from the database.

import { ref } from 'vue'
import { Background } from '@vue-flow/background'
import { Controls } from '@vue-flow/controls'
import { MiniMap } from '@vue-flow/minimap'
import { VueFlow, useVueFlow } from '@vue-flow/core'
import CustomNode from './CustomNode.vue'
import CustomEdge from './CustomEdge.vue'
import SearchElementModal from '../SearchElementModal.vue'
import { watch, onMounted } from 'vue'

const showAddElementButton = ref(false)
const isSearchElementModalOpen = ref(false)
const clickedMouseX = ref(0)
const clickedMouseY = ref(0)
const paneX = ref(0)
const paneY = ref(0)

const props = defineProps({
    element: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['updateElement'])

const nodesEdges = ref([])


watch(nodesEdges, (list) => {
    console.log('element has changed...')
    // Making a copy to prevent reactivity issues
    const updatedElement = JSON.parse(JSON.stringify(props.element))

    updatedElement.content = list
    emit('updateElement', updatedElement)
})

const AddElementButton = ref(null)
// https://vueflow.dev/typedocs/interfaces/Actions.html#addedges
// Actions that can be listened to
const { onConnect, addEdges, removeNodes, addNodes, onPaneClick, } = useVueFlow()

// default is the default node type, which includes 2 handles.
// You can tweak the position like so 
// targetHandle: Position.Top, // or Bottom, Left, Right,
// sourceHandle: Position.Bottom, // or Top, Left, Right,

onPaneClick((params) => {
    clickedMouseX.value = params.clientX
    clickedMouseY.value = params.clientY
    paneX.value = params.offsetX
    paneY.value = params.offsetY

    if(showAddElementButton.value == false) {
        showAddElementButton.value = true
    }
    else {
        showAddElementButton.value = false
    }
})

function insertNode(data) {
    // console.log(props.element)
    addNodes([{
            id: getNextNodeId(),
            label: data.element_name,
            position: { x: paneX.value, y: paneY.value },

            data: data
        }])
}

function getNextNodeId() {
    return nodesEdges.value.length + 1
}

function onAddElementButtonClicked() {
    isSearchElementModalOpen.value = true
    showAddElementButton.value = false
}

function onCloseElementSearchModal(event) {
    isSearchElementModalOpen.value = false

    if (event) {
        insertNode(event)
    }
}

onMounted(() => {
    if (props.element.content !== null) {
        nodesEdges.value = props.element.content
    }
})

</script>

<template>
    <div>
        <Teleport to="body">
            <Transition name="modal" class="z-50">
                <search-element-modal v-if="isSearchElementModalOpen" @closeElementSearchModal="onCloseElementSearchModal"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
            </Transition>
        </Teleport>

        <VueFlow v-model="nodesEdges" fit-view-on-init class="border-4 border-green-500 bg-blue-500 vue-flow-basic-example"
            :default-zoom="1.5" :min-zoom="0.2" :max-zoom="4" auto-connect>


            <Background pattern-color="#aaa" :gap="8" />

            <MiniMap />

            <Controls />

            <!-- Render this in the custom slot -->
            <template #node-custom="nodeProps">
                <CustomNode v-bind="nodeProps" />
            </template>

            <template #edge-custom="edgeProps">
                <CustomEdge v-bind="edgeProps" />
            </template>

            <button @click="onAddElementButtonClicked" ref="AddElementButton" v-if="showAddElementButton"
                :style="{ position: 'fixed', top: clickedMouseY + 'px', left: clickedMouseX + 'px' }"
                class="bg-pink-500 p-8 z-10 rounded-lg border-4 border-black">
                Add Element
            </button>
        </VueFlow>

    </div>
</template>

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