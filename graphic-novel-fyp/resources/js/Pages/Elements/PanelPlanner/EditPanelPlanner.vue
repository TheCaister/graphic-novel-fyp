<template>
    <div class="flex justify-center text-white">
        <div class="text-white">
            <div v-for="item in layout" class="rounded-lg relative border-4 border-pink-500 p-4">
                <div>
                    <!-- Do something like Panel + item.i -->
                    {{ 'Panel ' + item.i  }}
                </div>
                <div v-for="element in item.elements">
                    {{ element.element_name }}
                </div>
            </div>
        </div>
        <div class="w-2/3">
            <GridLayout v-model:layout="layout" :responsive="responsive" :layout.sync="layout" :col-num="12" :row-height="30"
            :is-draggable="true" :is-resizable="true" :is-mirrored="true" :vertical-compact="false" :margin="[10, 10]"
            :use-css-transforms="true" class="border-4 border-pink-500 rounded-lg  touch-none">
            <!-- <grid-item v-for="item in layout" :x="item.x" :y="item.y" :w="item.w" :h="item.h" :i="item.i" :key="item.i"
                class="bg-red-500 rounded-lg relative"
                :style="{ backgroundImage: `url(https://picsum.photos/seed/${item.i}/500/500)` }">

                <button type="button" class="absolute top-0 right-0 p-2 bg-blue-500 text-white rounded-lg" @click="openLink">
                    Test Button
                </button>
            </grid-item> -->

            <grid-item v-for="item in layout" :x="item.x" :y="item.y" :w="item.w" :h="item.h" :i="item.i" :key="item.i"
                class="rounded-lg relative border-4 border-pink-500 text-white p-4">
                Testing...
            </grid-item>
        </GridLayout>
        <div class="flex justify-center">
            <button>
                <-
            </button>
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
        <div >
            <div v-for="item in layout" class="rounded-lg relative border-4 border-pink-500 p-4">
                <div>
                    <!-- Do something like Panel + item.i -->
                    {{ 'Panel ' + item.i  }}
                </div>
                <div>
                    {{ item.text }}
                </div>
            </div>
        </div>
    </div>
</template>
  

<script lang="ts" setup>
import { ref } from 'vue';
import { GridLayout, GridItem } from 'vue3-grid-layout-next';

const responsive = ref(true)


// Set const layout to random values
const layout = ref([
    { "x": 0, "y": 0, "w": 2, "h": 2, "i": "0", "text": "Some lore here", "elements": [{
        "element_id": 1,
        "element_name": "Grudd",
    }] },
    { "x": 2, "y": 0, "w": 2, "h": 4, "i": "1" },
    { "x": 4, "y": 0, "w": 2, "h": 5, "i": "2" },
    { "x": 6, "y": 0, "w": 2, "h": 3, "i": "3" },
    { "x": 8, "y": 0, "w": 2, "h": 3, "i": "4" },
    { "x": 10, "y": 0, "w": 2, "h": 3, "i": "5" },
])

// const layout = reactive([]) // will cause some bug

// it will work, when responsive is false
// const layout = reactive([])

</script>

<style scoped>
/* .no-aspect-ratio {
    object-fit: fill;
    width: 100%;
    height: 100%;
} */
</style>