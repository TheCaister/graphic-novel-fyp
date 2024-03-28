<template>
    <div class="flex justify-center text-white bg-black" style="height: 80vh;">
        <div v-if="showElementList === true" class="text-white">
            <div v-for="item in layout" class="rounded-lg relative border-4 border-pink-500 p-4">
                <div>
                    <!-- Do something like Panel + item.i -->
                    {{ 'Panel ' + item.i }}
                </div>
                <div v-for="element in item.elements">
                    {{ element.element_name }}

                    <button @click="removeElement(item, element)">
                        X
                    </button>
                </div>
            </div>
        </div>



        <div class="flex flex-col items-center">
            <div class="relative border-2 flex flex-col">
                <div>
                    <button @click="console.log(props.element)">
                        Check element
                    </button>
                    <button @click="showElementList = !showElementList">
                        Toggle elements
                    </button>
                    <button @click="showPanelDescriptionList = !showPanelDescriptionList">
                        Toggle panel descriptions
                    </button>
                </div>
                <GridLayout v-model:layout="layout" :responsive="responsive" :layout.sync="layout" :col-num="12"
                    :row-height="30" :is-draggable="true" :is-resizable="true" :is-mirrored="isMirrored"
                    :vertical-compact="false" :margin="[10, 10]" :restore-on-drag="true" :use-css-transforms="true"
                    class="border-4 border-pink-500 rounded-lg touch-none" @click="addGridItem"
                    @mousemove="updateMousePosition" @mouseleave="showAddElementHint = false" :key="isMirrored" :style="{
            height: '60vh',
            aspectRatio: pageStyleAspectRatio
        }">
                    <grid-item v-for="item in layout" :x="item.x" :y="item.y" :w="item.w" :h="item.h" :i="item.i"
                        :key="item.i" class="rounded-lg border-4 border-pink-500 text-white p-4 bg-black z-0"
                        @mousemove.stop="showAddElementHint = false; isDragging = true; stopIsDraggingAfterDelay(50)"
                        @mouseup="stopIsDraggingAfterDelay(10)"
                        @click.stop="console.log(selectedGridId); selectedGridId = item.i; handleClick(); updateMouseClickPosition($event)">
                        {{ item.i }}
                        <span class="absolute top-0 right-0 cursor-pointer mt-2 mr-2"
                            @click="removeGridItem(item.i)">X</span>
                        <Teleport to="#gridHolder">
                            <Transition name="fade">
                                <DashboardDropdownMenu v-if="selectedGridId == item.i && isGridMenuOpen"
                                    :events="dropDownMenuOptions" @menuItemClick="handleMenuItemClicked($event, item)"
                                    @closeMenu="isGridMenuOpen = false" />
                            </Transition>
                        </Teleport>
                    </grid-item>
                </GridLayout>
                <!-- <div class="flex justify-center">
                    <button>
                        &lt;- </button>
                    <div>
                        12/29
                    </div>
                    <button>
                        ->
                    </button>
                </div> -->
            </div>
            <div class="flex flex-col">
                <select name="pageType" id="pageType" class="bg-gray-500 rounded-lg border-2 border-gray-300"
                    v-model="pageStyle">
                    <option value="standard_american">Standard American</option>
                    <option value="double_standard_american">Double Standard American</option>
                    <option value="a5">Single A5</option>
                    <option value="double_a5">Double A5</option>
                </select>
                <!-- select tag for left-to-right and right-to-left -->
                <select name="pageDirection" id="pageDirection" class="bg-gray-500 rounded-lg border-2 border-gray-300"
                    v-model="isMirrored">
                    <option :value="false">Left to Right</option>
                    <option :value="true">Right to Left</option>
                </select>
            </div>
        </div>

        <!-- Panel descriptions list -->
        <div v-if="showPanelDescriptionList === true">
            <div v-for="item in layout" class="rounded-lg border-4 border-pink-500 p-4">
                <div>
                    {{ 'Panel ' + item.i }}
                </div>
                <div>
                    <input type="text" v-model="item.text" class="text-black" placeholder="Type here" />

                </div>
            </div>
        </div>

    </div>

    <!-- Container for buttons that pop in and out of existence -->
    <div>

        <div id="gridHolder" :style="{ position: 'fixed', top: mouseClickY + 'px', left: mouseClickX + 'px' }"
            class=" z-10"></div>

        <div v-if="showAddElementHint" :style="{ position: 'fixed', top: mouseY + 'px', left: mouseX + 'px' }"
            class="bg-pink-500 p-8 z-10 rounded-lg border-4 border-black">
            Click to add panel
        </div>
    </div>

    <Teleport to="body">
        <Transition name="modal" class="z-50">
            <search-element-modal v-if="isSearchElementModalOpen" @closeElementSearchModal="onCloseElementSearchModal"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>
    </Teleport>
</template>


<script lang="ts" setup>
import { watch } from 'vue';
import { ref, computed, onMounted } from 'vue';
import { GridLayout, GridItem } from 'vue3-grid-layout-next';
import DashboardDropdownMenu from '../../Dashboard/DashboardDropdownMenu.vue';
import SearchElementModal from '../SearchElementModal.vue'

const responsive = ref(true)

const showAddElementButton = ref(false)
const isSearchElementModalOpen = ref(false)
const showAddElementHint = ref(false)
const mouseX = ref(0)
const mouseY = ref(0)
// const pageStyle = ref('a5')
const pageStyle = ref('a5')
const isGridMenuOpen = ref(false)
const mouseClickX = ref(0)
const mouseClickY = ref(0)

const colNum = 12

const isMirrored = ref(false)
const isDragging = ref(false)
const showElementList = ref(true)
const showPanelDescriptionList = ref(true)

const element = defineModel()

const emit = defineEmits(['updateChildrenElementIDs'])

// Set const layout to random values
// I should consider adding...
// static so that it won't move when I drag other panels over it
// list-index so that I can keep track of the order of the panels in the list on the right

// const layout = ref(props.element.content)
const layout = ref([])

const selectedGridId = ref(0)

const dropDownMenuOptions = [
    { id: 1, text: "Fix Position", eventName: "fixPosition" },
    { id: 2, text: "Add Element", eventName: "addElements" },
]

watch(layout, (newVal) => {
    if (element.value.content && element.value.content.layout) {
        element.value.content.layout = newVal
    } else {
        element.value.content = {
            layout: newVal
        }
    }

    // first sort by y, then by x

    layout.value = layout.value.sort((a, b) => {
        if (a.y === b.y) {
            return a.x - b.x
        }
        return a.y - b.y
    })
})

watch(pageStyle, (newVal) => {
    element.value.content.pageStyle = newVal
})

watch(isMirrored, (newVal) => {
    element.value.content.isMirrored = newVal
})

onMounted(() => {
    // console.log(props.element)

    // SETTING LAYOUT, MIRRORED AND ASPECT RATIO

    if (element.value.content) {

        if (element.value.content.layout) {
            console.log('setting layout...')

            layout.value = element.value.content.layout;
            console.log(layout.value)

            layout.value = layout.value.map(item => {
                return {
                    ...item,
                    h: parseFloat(item.h),
                    i: parseFloat(item.i),
                    w: parseFloat(item.w),
                    x: parseFloat(item.x),
                    y: parseFloat(item.y),
                    static: parseFloat(item.static) === 1 ? true : false,
                    moved: parseFloat(item.moved) === 1 ? true : false,
                    elements: item.elements ? item.elements : []
                };
            });
        }

        if (element.value.content.pageStyle) {
            pageStyle.value = element.value.content.pageStyle
        }

        // do something similar for isMirrored
        if (element.value.content.isMirrored) {
            isMirrored.value = parseFloat(element.value.content.isMirrored) === 1 ? true : false
        }
    }
})

// computed pageStyle to return the correct page aspect ratio
const pageStyleAspectRatio = computed(() => {
    console.log('changing aspect ratio')

    let aspectRatio = 'auto'

    switch (pageStyle.value) {
        case 'a5':
            aspectRatio = '1/1.42'
            break
        case 'double_a5':
            aspectRatio = '1/0.71'
            break
        case 'standard_american':
            aspectRatio = '1/0.67'
            break
        case 'double_standard_american':
            aspectRatio = '1/0.33'
            break
        default:
            aspectRatio = '1/1.42'
    }

    console.log(aspectRatio)

    return aspectRatio
})

function updateMousePosition(event) {
    mouseX.value = event.clientX
    mouseY.value = event.clientY

    showAddElementHint.value = true
}

function stopIsDraggingAfterDelay(delay) {
    setTimeout(() => {
        isDragging.value = false
    }, delay)

}

function handleClick() {
    if (!isDragging.value) {
        // Show your menu here
        console.log('clicked')
        isGridMenuOpen.value = true
    }
}

function handleMenuItemClicked(event, grid) {


    // switch statement for event
    switch (event) {
        case 'fixPosition':
            grid.static = !grid.static
            break
        case 'addElements':
            console.log('add elements')
            isSearchElementModalOpen.value = true

            break
        default:
            console.log('default')
    }

    isGridMenuOpen.value = false

    // find the grid in 
}

function onCloseElementSearchModal(event) {
    isSearchElementModalOpen.value = false

    if (event) {
        // insert event into the selected grid... 
        // first, we find the selected grid
        // afterwards, if it has an elements array, we push the event into it
        // if it doesn't, we create an elements array and push the event into it
        const index = layout.value.map(item => item.i).indexOf(selectedGridId.value);
        if (layout.value[index].elements) {
            layout.value[index].elements.push(event)
        } else {
            layout.value[index].elements = [event]
        }
    }
}

function updateMouseClickPosition(event) {
    mouseClickX.value = event.clientX;
    mouseClickY.value = event.clientY;
}

function removeElement(grid, element) {
    const index = layout.value.map(item => item.i).indexOf(grid.i);
    const elementIndex = layout.value[index].elements.map(item => item.element_id).indexOf(element.element_id);
    layout.value[index].elements.splice(elementIndex, 1);
}

function addGridItem() {
    layout.value.push({
        // 12 is the number of columns or colNum
        "x": (layout.value.length * 2) % 12,
        "y": layout.value.length + 12,
        // "x": (layout.value.length * 2) % (colNum || 12),
        // "y": layout.value.length + (colNum || 12),
        "w": 2,
        "h": 2,
        "i": layout.value.length.toString(),
        "text": "",
        "elements": [],
    })
}

function removeGridItem(val) {
    const index = this.layout.map(item => item.i).indexOf(val);
    this.layout.splice(index, 1);

}

</script>

<style scoped>
/* .no-aspect-ratio {
    object-fit: fill;
    width: 100%;
    height: 100%;
} */
</style>

<style scoped>
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

.material-symbols-outlined {
    font-variation-settings:
        'FILL' 0,
        'wght' 500,
        'GRAD' 0,
        'opsz' 40;
    font-size: 42px;
}

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