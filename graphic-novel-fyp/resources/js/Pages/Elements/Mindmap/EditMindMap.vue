<!-- <template>
    <div class="border border-black">
        Hi guys
        <VueFlow v-model="elements" />
    </div>
</template>
  
<script setup>
import { VueFlow } from '@vue-flow/core'
import { ref } from 'vue';

const elements = ref([
    // Nodes
    // An input node, specified by using `type: 'input'`
    { id: '1', type: 'input', label: 'Node 1', position: { x: 250, y: 5 } },

    // Default nodes, you can omit `type: 'default'`
    { id: '2', label: 'Node 2', position: { x: 100, y: 100 }, },
    { id: '3', label: 'Node 3', position: { x: 400, y: 100 } },

    // An output node, specified by using `type: 'output'`
    { id: '4', type: 'output', label: 'Node 4', position: { x: 400, y: 200 } },

    // Edges
    // Most basic edge, only consists of an id, source-id and target-id
    { id: 'e1-3', source: '1', target: '3' },

    // An animated edge
    { id: 'e1-2', source: '1', target: '2', animated: true },
])
</script> -->

<style>
/* import the necessary styles for Vue Flow to work */
@import '@vue-flow/core/dist/style.css';

/* import the default theme, this is optional but generally recommended */
@import '@vue-flow/core/dist/theme-default.css';
</style>


<script setup>

// The nodes and edges can be bound to props that I will pass in from the database.

import { ref } from 'vue'
import { onClickOutside } from '@vueuse/core'
import { Background } from '@vue-flow/background'
import { Controls } from '@vue-flow/controls'
import { MiniMap } from '@vue-flow/minimap'
import { VueFlow, useVueFlow } from '@vue-flow/core'
import CustomNode from './CustomNode.vue'
import CustomEdge from './CustomEdge.vue'
import SearchElementModal from '../SearchElementModal.vue'

const showAddElementButton = ref(false)
const isSearchElementModalOpen = ref(false)
const clickedMouseX = ref(0)
const clickedMouseY = ref(0)

const AddElementButton = ref(null)



onClickOutside(AddElementButton, () => {
    showAddElementButton.value = false
})

// https://vueflow.dev/typedocs/interfaces/Actions.html#addedges
// Actions that can be listened to
const { onConnect, addEdges, removeEdges, removeNodes, addNodes, onNodeDragStart, onNodeClick, onPaneClick } = useVueFlow()

// default is the default node type, which includes 2 handles.
// You can tweak the position like so 
// targetHandle: Position.Top, // or Bottom, Left, Right,
// sourceHandle: Position.Bottom, // or Top, Left, Right,

// input has a single handle.
const nodes = ref([
    // { id: '1', type: 'input', label: 'Node 1', position: { x: 250, y: 5 } },
    // { id: '2', type: 'output', label: 'Node 2', position: { x: 100, y: 100 } },

    {
        id: '1',
        position: { x: 50, y: 50 },
        label: 'Node 1',
    },
    {
        id: '2',
        position: { x: 50, y: 250 },
        label: 'Node 2',
    },
    // Dynamic resolution to slot-names is done for your user-defined node-types, meaning a node with the type custom is expected to have a slot named #node-custom.
    { id: '3', type: 'custom', label: 'Node 3', position: { x: 400, y: 100 } },


    { id: 'e1-2', source: '1', target: '2', type: 'custom' },
    // { id: 'e1-2', source: '1', target: '2' },

    // {
    //     id: 'e1-2',
    //     source: '1',
    //     target: '2',
    // },

    { id: 'e1-3', source: '1', target: '3', animated: true },
])

onConnect((params) => {
    addEdges([params])
})

onNodeDragStart((params) => {
    console.log(params)
})

onNodeClick((params) => {
    console.log(params)
})

onPaneClick((params) => {
    clickedMouseX.value = params.clientX
    clickedMouseY.value = params.clientY
    showAddElementButton.value = true
})

function insertNode() {
    // nodes.value.push({
    //     id: getNextNodeId(),
    //     type: 'input',
    //     label: 'Node 4',
    //     position: { x: 400, y: 200 }
    // })

    addNodes([{
        id: getNextNodeId(),
        type: 'input',
        label: 'Node 4',
        position: { x: 400, y: 200 }
    }])
}

function getNextNodeId() {
    return nodes.value.length + 1
}

function removeNode() {
    // nodes.value.pop()

    removeNodes(nodes.value.length.toString())
}

function onAddElementButtonClicked() {
    console.log('hi guys')
    isSearchElementModalOpen.value = true
    showAddElementButton.value = false
}
</script>

<template>
    <div>
        <Teleport to="body">
            <Transition name="modal" class="z-50">
                <search-element-modal v-if="isSearchElementModalOpen" @closeModal="isSearchElementModalOpen = false"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
            </Transition>
        </Teleport>

        <!-- Cool button that calls insertNode -->
        <button @click="insertNode()" class="text-white text-4xl">Insert Node</button>

        <!-- Cool button that calls removeNode -->
        <button @click="removeNode()" class="text-white text-4xl">Remove Node</button>

        <!-- <div class="p-16 bg-white"> -->
        <!-- <VueFlow v-model:nodes="nodes" v-model:edges="edges" fit-view-on-init
            class="border-4 border-green-500 bg-blue-500 vue-flow-basic-example" :default-zoom="1.5" :min-zoom="0.2"
            :max-zoom="4"> -->

        <VueFlow v-model="nodes" fit-view-on-init class="border-4 border-green-500 bg-blue-500 vue-flow-basic-example"
            :default-zoom="1.5" :min-zoom="0.2" :max-zoom="4" @pane-click="onPaneClicked">


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
        <!-- </div> -->

    </div>
</template>

