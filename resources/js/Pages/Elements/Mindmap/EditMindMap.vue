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
import RelationEdge from './RelationEdge.vue'
import ElementNode from './ElementNode.vue'
import TextNode from './TextNode.vue'
import SearchElementModal from '../SearchElementModal.vue'
import DashboardDropdownMenu from '@/Pages/Dashboard/DashboardDropdownMenu.vue'
import { watch, onMounted } from 'vue'
import { router } from '@inertiajs/vue3';

const showAddElementButton = ref(false)
const showNodeMenu = ref(false)
const selectedNode = ref(null)
const isSearchElementModalOpen = ref(false)
const clickedMouseX = ref(0)
const clickedMouseY = ref(0)
const paneX = ref(0)
const paneY = ref(0)

const element = defineModel()

const emit = defineEmits(['updateElement'])

const nodesEdges = ref([])

let updateTimeout = null;


watch(nodesEdges, (list) => {

    // Clear the previous timeout if it exists
    if (updateTimeout) {
        clearTimeout(updateTimeout);
    }

    // Set a new timeout
    updateTimeout = setTimeout(() => {
        element.value.content = JSON.parse(JSON.stringify(list));
    }, 200);
}, {
    deep: true
})

const dropDownMenuOptions = [
    { id: 1, text: "Add Element", eventName: "addElement" },
    { id: 2, text: "Add Text", eventName: "addText" },
]

const dropDownMenuOptionsNode = [
    { id: 1, text: "Visit Node", eventName: "visitNode" },
]

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

    showNodeMenu.value = false

    if (showAddElementButton.value == false) {
        showAddElementButton.value = true

    }
    else {
        showAddElementButton.value = false
    }
})

function onNodeClick(event) {
    showAddElementButton.value = false

    clickedMouseX.value = event.event.clientX
    clickedMouseY.value = event.event.clientY

    selectedNode.value = event.node
    if (showNodeMenu.value == false) {
        showNodeMenu.value = true
    }
    else {
        showNodeMenu.value = false
    }
}

onConnect((params) => {
    // console.log(params)
    params.type = 'relation';
    params.textHidden = false;
    addEdges([params])
})

function insertElementNode(data) {
    addNodes([{
        id: getNextNodeId(),
        // label: data.element_name,
        position: { x: paneX.value, y: paneY.value },
        type: 'element',

        data: data
    }])
}

function insertTextNode(data) {
    addNodes([{
        id: getNextNodeId(),
        // label: data.text,
        position: { x: paneX.value, y: paneY.value },
        type: 'text',

        data: ""
    }])
}

function handleMenuItemClicked(eventName) {
    switch (eventName) {
        case 'addElement':
            onAddElementButtonClicked()
            break
        case 'addText':
            insertTextNode()
            break
        case 'visitNode':
            let element = selectedNode.value.data
            // console.log(selectedNode.value.data)
            router.visit(route('elements.edit', element.element_id))
            break
        default:
            break
    }

    showAddElementButton.value = false
}

function getNextNodeId() {
    return nodesEdges.value.length + 1
    // return element.value.content.length + 1
}

function onAddElementButtonClicked() {
    isSearchElementModalOpen.value = true
    showAddElementButton.value = false
}

function onCloseElementSearchModal(event) {
    isSearchElementModalOpen.value = false

    if (event) {
        insertElementNode(event)
    }
}

onMounted(() => {
    if (element.value.content !== null && element.value.content !== undefined) {
        nodesEdges.value = JSON.parse(JSON.stringify(element.value.content))
    }

    // going through each item in element.value.content, and converting some of the properties from string to boolean,
    // the properties are dragging, selected
    nodesEdges.value.forEach(item => {
        item.dragging = item.dragging === '1' ? true : false
        item.selected = item.selected === '0' ? true : false
    })

})

</script>

<template>
    <!-- <div :style="{ height: '3000px' }" class="h-screen"> -->
    <div class="p-8">
        <div style="height: 90vh;" class="border-pink-400 border-2 rounded-lg">
            <Teleport to="body">
                <Transition name="modal" class="z-50">
                    <search-element-modal v-if="isSearchElementModalOpen"
                    :reference-element-id="element.element_id" 
                        @closeElementSearchModal="onCloseElementSearchModal"
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
                </Transition>
            </Teleport>
            <VueFlow v-model="nodesEdges" fit-view-on-init class=" vue-flow-basic-example" :default-zoom="1.5"
                :min-zoom="0.2" :max-zoom="4" auto-connect>
                <Background pattern-color="#aaa" :gap="8" />
                <MiniMap />
                <Controls />
                <!-- Render this in the custom slot -->
                <template #node-custom="nodeProps">
                    <CustomNode v-bind="nodeProps" />
                </template>
                <template #node-element="nodeProps">
                    <ElementNode v-bind="nodeProps" @test="console.log('element clicked'); onNodeClick($event)" />
                </template>
                <template #node-text="nodeProps">
                    <TextNode v-bind="nodeProps" />
                </template>
                <!-- <template #edge-custom="edgeProps">
                    <CustomEdge v-bind="edgeProps" />
                </template> -->
                <template #edge-relation="edgeProps">
                    <RelationEdge v-bind="edgeProps" />
                </template>
                <!-- This is for when you want the connection line to be different when dragging them out -->
                <!-- <template #connection-line="{ sourceX, sourceY, targetX, targetY }">
                    <CustomConnectionLine :source-x="sourceX" :source-y="sourceY" :target-x="targetX" :target-y="targetY" />
                </template> -->
                <!-- <button @click="onAddElementButtonClicked" v-if="showAddElementButton"
                    :style="{ position: 'fixed', top: clickedMouseY + 'px', left: clickedMouseX + 'px' }"
                    class="bg-pink-500 p-8 z-10 rounded-lg border-4 border-black">
                    Add Element
                </button> -->
                <Transition name="fade">
                    <DashboardDropdownMenu v-if="showAddElementButton" class="absolute z-40"
                        :style="{ position: 'fixed', top: clickedMouseY + 'px', left: clickedMouseX + 'px' }"
                        :events="dropDownMenuOptions" @menuItemClick="handleMenuItemClicked" @closeMenu="" />
                </Transition>

                <Transition name="fade">
                    <DashboardDropdownMenu v-if="showNodeMenu" class="absolute z-40"
                        :style="{ position: 'fixed', top: clickedMouseY + 'px', left: clickedMouseX + 'px' }"
                        :events="dropDownMenuOptionsNode" @menuItemClick="handleMenuItemClicked" @closeMenu="" />
                </Transition>
            </VueFlow>
        </div>
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

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s, transform 0.2s;
}

.fade-enter-from {
    opacity: 0;
    transform: translateY(-20px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

.fade-enter-to {
    opacity: 1;
    transform: translateY(0);
}
</style>