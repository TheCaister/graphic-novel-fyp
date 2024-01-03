<template>
    <div class="border border-black">
        <GridLayout v-model:layout="layout" :responsive="responsive" :layout.sync="layout" :col-num="12" :row-height="30"
            :is-draggable="true" :is-resizable="true" :is-mirrored="true" :vertical-compact="false" :margin="[10, 10]"
            :use-css-transforms="true">
            <grid-item v-for="item in layout" :x="item.x" :y="item.y" :w="item.w" :h="item.h" :i="item.i" :key="item.i"
                class="bg-red-500 rounded-lg relative"
                :style="{ backgroundImage: `url(https://picsum.photos/seed/${item.i}/500/500)` }">

                <button type="button" class="absolute top-0 right-0 p-2 bg-blue-500 text-white rounded-lg" @click="openLink">
                    Fortnite Rap Battle
                </button>
            </grid-item>
        </GridLayout>
    </div>
</template>
  

<script lang="ts" setup>
import { ref } from 'vue';
import { GridLayout, GridItem } from 'vue3-grid-layout-next';

const responsive = ref(true)


// Set const layout to random values
const layout = ref([
    { "x": 0, "y": 0, "w": 2, "h": 2, "i": "0" },
    { "x": 2, "y": 0, "w": 2, "h": 4, "i": "1" },
    { "x": 4, "y": 0, "w": 2, "h": 5, "i": "2" },
    { "x": 6, "y": 0, "w": 2, "h": 3, "i": "3" },
    { "x": 8, "y": 0, "w": 2, "h": 3, "i": "4" },
    { "x": 10, "y": 0, "w": 2, "h": 3, "i": "5" },
    { "x": 0, "y": 5, "w": 2, "h": 5, "i": "6" },
    { "x": 2, "y": 5, "w": 2, "h": 5, "i": "7" },
    { "x": 4, "y": 5, "w": 2, "h": 5, "i": "8" },
    { "x": 6, "y": 3, "w": 2, "h": 4, "i": "9" },
    { "x": 8, "y": 4, "w": 2, "h": 4, "i": "10" },
    { "x": 10, "y": 4, "w": 2, "h": 4, "i": "11" },
    { "x": 0, "y": 10, "w": 2, "h": 5, "i": "12" },
    { "x": 2, "y": 10, "w": 2, "h": 5, "i": "13" },
    { "x": 4, "y": 8, "w": 2, "h": 4, "i": "14" },
    { "x": 6, "y": 8, "w": 2, "h": 4, "i": "15" },
    { "x": 8, "y": 10, "w": 2, "h": 5, "i": "16" },
    { "x": 10, "y": 4, "w": 2, "h": 2, "i": "17" },
    { "x": 0, "y": 9, "w": 2, "h": 3, "i": "18" },
    { "x": 2, "y": 6, "w": 2, "h": 2, "i": "19" }
])

function openLink() {
  window.open('https://www.youtube.com/watch?v=3N4yIDfN-M8', '_blank');
}

// const layout = reactive([]) // will cause some bug

// it will work, when responsive is false
// const layout = reactive([])

</script>

<style scoped>
.no-aspect-ratio {
    object-fit: fill;
    width: 100%;
    height: 100%;
}
</style>