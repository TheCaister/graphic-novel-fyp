<template>
    <div class="flex justify-center text-white ">
        <div class="text-white">
            <div v-for="item in layout" class="rounded-lg relative border-4 border-pink-500 p-4">
                <div>
                    <!-- Do something like Panel + item.i -->
                    {{ 'Panel ' + item.i }}
                </div>
                <div v-for="element in item.elements">
                    {{ element.element_name }}
                </div>
            </div>
        </div>
        <div class="w-2/3 min-h-10">
            <GridLayout v-model:layout="layout" :responsive="responsive" :layout.sync="layout" :col-num="12"
                :row-height="30" :is-draggable="true" :is-resizable="true" :is-mirrored="true" :vertical-compact="false"
                :margin="[10, 10]" :restore-on-drag="true" :use-css-transforms="true"
                class="border-4 border-pink-500 rounded-lg  touch-none" @click="addGridItem"
                @mousemove="updateMousePosition" @mouseleave="showAddElementHint = false">
                <grid-item v-for="item in layout" :x="item.x" :y="item.y" :w="item.w" :h="item.h" :i="item.i" :key="item.i"
                    class="rounded-lg relative border-4 border-pink-500 text-white p-4"
                    @mousemove.stop="showAddElementHint = false" @click.stop="">
                    Testing...
                    <span class="absolute top-0 right-0 cursor-pointer mt-2 mr-2" @click="removeGridItem(item.i)">X</span>
                </grid-item>



                <!-- Make an empty div with a border with the aspect ratio of a page to be overlayed on top of the grid -->
                <div class="border-4 border-blue-500 rounded-lg" :style="{ paddingBottom: pageStyleAspectRatio + '%' }">
                </div>

                <!-- <div class="border-4 border-blue-500 rounded-lg" style="padding-bottom: 141.0;"></div> -->


            </GridLayout>
            <div class="flex justify-center">
                <button>
                    &lt;- </button>
                <div>
                    12/29
                </div>
                <button>
                    ->
                </button>
            </div>
        </div>

        <div class="flex flex-col">
            <select name="pageType" id="pageType" class="bg-gray-500 rounded-lg border-2 border-gray-300">
                <option value="standard_american">Standard American</option>
                <option value="double_standard_american">Double Standard American</option>
                <option value="a5">Single A5</option>
                <option value="double_a5">Double A5</option>
            </select>

            <!-- select tag for left-to-right and right-to-left -->
            <select name="pageDirection" id="pageDirection" class="bg-gray-500 rounded-lg border-2 border-gray-300">
                <option value="ltr">Left to Right</option>
                <option value="rtl">Right to Left</option>
            </select>
        </div>
        <div>
            <div v-for="item in layout" class="rounded-lg relative border-4 border-pink-500 p-4">
                <div>
                    <!-- Do something like Panel + item.i -->
                    {{ 'Panel ' + item.i }}
                </div>
                <div>
                    {{ item.text }}
                </div>
            </div>
        </div>
    </div>

    <!-- Container for buttons that pop in and out of existence -->
    <div>
        <div v-if="showAddElementHint" :style="{ position: 'fixed', top: mouseY + 'px', left: mouseX + 'px' }"
            class="bg-pink-500 p-8 z-10 rounded-lg border-4 border-black">
            Click to add element
        </div>
    </div>
</template>
  

<script lang="ts" setup>
import { watch } from 'vue';
import { ref, computed, onMounted } from 'vue';
import { GridLayout, GridItem } from 'vue3-grid-layout-next';

const responsive = ref(true)

const showAddElementButton = ref(false)
const showAddElementHint = ref(false)
const mouseX = ref(0)
const mouseY = ref(0)
const pageStyle = ref('a5')

const props = defineProps({
    element: {
        type: Object,
        required: true
    }
})


const emit = defineEmits(['updateElement', 'updateChildrenElementIDs'])

// Set const layout to random values
// I should consider adding...
// static so that it won't move when I drag other panels over it
// list-index so that I can keep track of the order of the panels in the list on the right

// const layout = ref(props.element.content)
const layout = ref([])

watch(layout, (newVal) => {
    console.log('updated...')
    props.element.content = newVal

    // console.log(props.element)
    emit('updateElement', props.element)
})

onMounted(() => {

    layout.value = props.element.content ? props.element.content : [];
    // layout.value = []
})

// computed pageStyle to return the correct page aspect ratio
const pageStyleAspectRatio = computed(() => {
    switch (pageStyle.value) {
        case 'a5':
            return 141.42
        case 'double_a5':
            return 282.84
        case 'standard_american':
            return 150
        case 'double_standard_american':
            return 300
        default:
            return 141.42
    }
})

function updateMousePosition(event) {
    mouseX.value = event.clientX
    mouseY.value = event.clientY

    showAddElementHint.value = true
}

function addGridItem() {
    layout.value.push({
        // 12 is the number of columns or colNum
        "x": (layout.value.length * 2) % 12,
        "y": layout.value.length + 12,
        "w": 2,
        "h": 2,
        "i": layout.value.length.toString(),
        "text": "Some lore here", "elements": [{
            "element_id": 1,
            "element_name": "Grudd",
        }]
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