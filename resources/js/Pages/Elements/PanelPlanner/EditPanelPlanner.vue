<template>
    <div class="relative flex flex-col justify-center items-center text-white bg-gray-900 mt-16 pb-16 ">
        <div class=" flex justify-between w-full px-24 mb-12">

            <PrimaryButton :style="{ visibility: !showElementList ? 'visible' : 'hidden' }" @click="toggleShowElementList" class="">
                Toggle elements
            </PrimaryButton>
            <PrimaryButton :style="{ visibility: !showPanelDescriptionList ? 'visible' : 'hidden' }" @click="toggleShowPanelDescriptionList" class="">
                Toggle panel descriptions
            </PrimaryButton>


        </div>


        <div class="flex gap-8 items-center overflow-auto mx-8">

            <div class="relative flex flex-col">
                <!-- <button @click="console.log(layout)">
                    Check element
                </button> -->

<!-- :key="isMirrored" -->

                <GridLayout v-model:layout="layout" :responsive="responsive" :layout.sync="layout"
                    :cols="{ lg: colNum, md: colNum, sm: colNum, xs: colNum, xxs: 6 }" :row-height="rowHeight"
                    :max-rows="rowNum" :is-draggable="true" :is-resizable="true" :is-mirrored="isMirrored"
                    :prevent-collision="false" :is-bounded="true" :margin="[gridMargin, gridMargin]"
                   :key="isMirrored"
                    :restore-on-drag="true" :vertical-compact="false"
                    
                    class="border-2 border-pink-500 rounded-lg touch-none grid" :style="{
                        height: `${rowNum * rowHeight - 10}px`,
                        aspectRatio: pageStyleAspectRatio
                    }" @breakpoint-changed="breakpointChangedEvent">

                        <!-- 
                            I only want the menu to appear when the grid item is not being dragged around.
                            When the grid item is stationary, the menu should appear when the grid item is clicked.
                            When the grid item is being dragged around, the menu should not appear.
                         -->

                    <grid-item v-for="item in layout" :x="item.x" :y="item.y" :w="item.w" :h="item.h" :i="item.i"
                        :key="item.i"
                        class="rounded-lg border-4 border-pink-500 text-white p-4 bg-black z-0 overflow-auto"
                        @mousemove.stop="isDragging = true"
                        @mousedown.left="isDragging = false"
                        @mouseup="stopIsDraggingAfterDelay()"
                        @click.stop="selectedGridId = item.i; handleClick(); updateMouseClickPosition($event)">
                        <img v-if="item.elements && item.elements.length > 0" :src="item.elements[0].element_thumbnail"
                            class="absolute inset-0 w-full h-full object-cover opacity-40 blur-sm" />
                        <div class="absolute text-xl">{{ item.text }}</div>
                        <Teleport to="#gridHolder">
                            <Transition name="fade">
                                <DashboardDropdownMenu v-if="selectedGridId == item.i && isGridMenuOpen"
                                    :events="dropDownMenuOptions" @menuItemClick="handleMenuItemClicked($event, item)"
                                    @closeMenu="isGridMenuOpen = false; isDragging = true; stopIsDraggingAfterDelay()" />
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
            <div class="flex flex-col gap-4">
                <select name="pageType" id="pageType" class="bg-gray-900 rounded-lg border-2 border-gray-300"
                    v-model="pageStyle">
                    <option value="standard_american">Standard American</option>
                    <!-- <option value="double_standard_american">Double Standard American</option> -->
                    <option value="a5">Single A5</option>
                    <option value="double_a5">Double A5</option>
                </select>
                <!-- select tag for left-to-right and right-to-left -->
                <select name="pageDirection" id="pageDirection" class="bg-gray-900 rounded-lg border-2 border-gray-300"
                    v-model="isMirrored">
                    <option :value="false">Left to Right</option>
                    <option :value="true">Right to Left</option>
                </select>
                <PrimaryButton @click="addGridItem">
                    Add grid
                </PrimaryButton>
            </div>
        </div>



        <Transition name="slide">
            <PanelDescriptionList v-if="showPanelDescriptionList === true" v-model="layout"
                @toggle-visibility="toggleShowPanelDescriptionList" class="w-1/4 mr-8" />
        </Transition>

        <Transition name="slide-reverse">
            <PanelElementList v-if="showElementList === true" v-model="layout"
                @toggle-visibility="toggleShowElementList" @remove-element="removeElement" class="w-1/4 ml-8" />
        </Transition>

    </div>

    <!-- Container for buttons that pop in and out of existence -->
    <div>

        <div id="gridHolder" :style="{ position: 'fixed', top: mouseClickY + 'px', left: mouseClickX + 'px' }"
            class=" z-10"></div>

        <!-- <div v-if="showAddElementHint" :style="{ position: 'fixed', top: mouseY + 'px', left: mouseX + 'px' }"
            class="bg-pink-500 p-8 z-10 rounded-lg border-4 border-black">
            Click to add panel
        </div> -->
    </div>

    <Teleport to="body">
        <Transition name="modal" class="z-50">
            <search-element-modal v-if="isSearchElementModalOpen"
            :reference-element-id="element.element_id" @closeElementSearchModal="onCloseElementSearchModal"
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
import PanelElementList from './PanelElementList.vue'
import PanelDescriptionList from './PanelDescriptionList.vue'

const responsive = ref(true)

// const showAddElementButton = ref(false)
const isSearchElementModalOpen = ref(false)
// const showAddElementHint = ref(false)
const mouseX = ref(0)
const mouseY = ref(0)
const mouseDown = ref(false)
// const pageStyle = ref('a5')
const pageStyle = ref('a5')
const isGridMenuOpen = ref(false)
const mouseClickX = ref(0)
const mouseClickY = ref(0)

const colNum = ref(12)
const rowNum = 12
const rowHeight = 60
const gridMargin = 10

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
    // { id: 1, text: "Fix Position", eventName: "fixPosition" },
    { id: 2, text: "Add Element", eventName: "addElements" },
    { id: 3, text: "Remove Panel", eventName: "removePanel" }
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
},
    {
        deep: true
    })

watch(pageStyle, (newVal) => {
    element.value.content.pageStyle = newVal
    // console.log(element.value.content.pageStyle)
})

watch(isMirrored, (newVal) => {
    element.value.content.isMirrored = newVal
    // console.log(element.value.content.isMirrored)
})

onMounted(() => {
    let rootStyle = document.documentElement.style;
    rootStyle.setProperty('--grid-margin', gridMargin + 'px');
    rootStyle.setProperty('--col-num', colNum.value.toString());
    rootStyle.setProperty('--row-height', rowHeight + 'px');
    // console.log(props.element)

    // SETTING LAYOUT, MIRRORED AND ASPECT RATIO

    if (element.value.content) {

        if (element.value.content.layout) {
            console.log('setting layout...')

            layout.value = element.value.content.layout;
            // console.log(layout.value)

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
            isMirrored.value = element.value.content.isMirrored
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
            aspectRatio = '1/1.5'
            break
        // case 'double_standard_american':
        //     aspectRatio = '1/0.33'
        //     break
        default:
            aspectRatio = '1/1.42'
    }

    console.log(aspectRatio)

    return aspectRatio
})

function updateMousePosition(event) {
    mouseX.value = event.clientX
    mouseY.value = event.clientY

    // showAddElementHint.value = true
}

function breakpointChangedEvent(newBreakpoint, newLayout) {

    switch (newBreakpoint) {
        case 'xxs':
            colNum.value = 6
            break;

        default:
            colNum.value = 12
            break;
    }

    let rootStyle = document.documentElement.style;
    rootStyle.setProperty('--col-num', colNum.value.toString());

}

function stopIsDraggingAfterDelay() {
    setTimeout(() => {
        isDragging.value = false
        // console.log('stopped dragging')
    }, 200)

}

function toggleShowElementList() {
    showElementList.value = !showElementList.value

    // If showElementList.value is true, showPanelDescriptionList.value should be false
    // if (showElementList.value === true) {
    //     showPanelDescriptionList.value = false
    // }
}

function toggleShowPanelDescriptionList() {
    showPanelDescriptionList.value = !showPanelDescriptionList.value

    // If showPanelDescriptionList.value is true, showElementList.value should be false
    // if (showPanelDescriptionList.value === true) {
    //     showElementList.value = false
    // }
}

function handleClick() {
    if (!isDragging.value) {
        // Show your menu here
        isGridMenuOpen.value = !isGridMenuOpen.value
    }
}

function handleMenuItemClicked(event, grid) {

    // switch statement for event
    switch (event) {
        case 'fixPosition':
            grid.static = !grid.static
            break
        case 'addElements':
            isSearchElementModalOpen.value = true

            break
        case 'removePanel':
            removeGridItem(grid.i)
            break
        default:
    }

    isGridMenuOpen.value = false
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
    // Create a 2D array to represent the grid
    let grid = new Array(rowNum).fill(null).map(() => new Array(colNum.value).fill(false));

    // Mark the occupied spaces in the grid
    for (let item of layout.value) {
        for (let dx = 0; dx < item.w; dx++) {
            for (let dy = 0; dy < item.h; dy++) {
                if (item.y + dy < rowNum && item.x + dx < colNum.value) {
                    grid[item.y + dy][item.x + dx] = true;
                }
            }
        }
    }

    // Find a free space in the grid
    for (let y = 0; y < rowNum; y++) {
        for (let x = 0; x < colNum.value; x++) {
            if (!grid[y][x]) {
                // Free space found, add the new grid item here
                layout.value.push({
                    "x": x,
                    "y": y,
                    "w": 1,
                    "h": 1,
                    "i": layout.value.length.toString(),
                    "text": "",
                    "elements": [],
                });
                return;
            }
        }
    }

    // No free space found, do not add a new grid item
    console.log("No free space available in the grid.");
}

function removeGridItem(val) {
    // const index = this.layout.map(item => item.i).indexOf(val);
    // this.layout.splice(index, 1);

    const index = layout.value.map(item => item.i).indexOf(val);
    layout.value.splice(index, 1);

}

</script>

<style scoped>
:root {
    --grid-margin: 10px;
    --col-num: 12;
    --row-height: 60px;
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

.grid::before {
    content: '';
    background-size: calc(calc(100% - calc(var(--grid-margin) / 2)) / var(--col-num)) calc(var(--row-height) + var(--grid-margin));
    background-image: linear-gradient(to right,
            rgb(85, 85, 85) 1px,
            transparent 1px),
        linear-gradient(to bottom, rgb(85, 85, 85) 1px, transparent 1px);
    height: calc(100% - calc(var(--grid-margin) / 2));
    width: calc(100% - calc(var(--grid-margin) / 2));
    position: absolute;
    background-repeat: repeat;
    margin: calc(var(--grid-margin) / 2);
}

.slide-enter-active,
.slide-leave-active {
    transition: all 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}

.slide-enter-to,
.slide-leave-from {
    transform: translateX(0);
}

.slide-reverse-enter-active,
.slide-reverse-leave-active {
    transition: all 0.3s ease;
}

.slide-reverse-enter-from,
.slide-reverse-leave-to {
    transform: translateX(-100%);
}

.slide-reverse-enter-to,
.slide-reverse-leave-from {
    transform: translateX(0);
}

.bg-overlay::before {
    content: "";
    display: block;
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.5);
    /* Adjust the 0.5 value to change the overlay transparency */
    z-index: 1;
}

.bg-overlay {
    position: relative;
}
</style>