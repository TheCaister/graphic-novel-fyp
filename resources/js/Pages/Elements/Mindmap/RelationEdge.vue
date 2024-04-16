<script lang="ts" setup>
import { computed } from 'vue'
import type { EdgeProps } from '@vue-flow/core'
import { BaseEdge, EdgeLabelRenderer, getBezierPath, useEdge, useVueFlow } from '@vue-flow/core'

const props = defineProps<EdgeProps>()

const { removeEdges } = useVueFlow()

const edge = useEdge()

if (edge.edge.data && Object.keys(edge.edge.data).length === 0) {
    edge.edge.data = ''
}

const path = computed(() => getBezierPath(props))

const textAreaRows = computed(() => {
    const rows = edge.edge.data ? edge.edge.data.split('\n').length : 0
    console.log(rows)
    return rows > 1 ? rows : 1
})

function toggleVisibility() {
    if (!edge.edge.hasOwnProperty('textHidden')) {
        edge.edge.textHidden = false
    } else {
        edge.edge.textHidden = !edge.edge.textHidden
    }
}
</script>

<script lang="ts">
export default {
    inheritAttrs: false,
}
</script>

<template>
    <BaseEdge :path="path[0]" :style="{
        strokeWidth: 2,
        stroke: 'pink',
    }" />

    <EdgeLabelRenderer>
        <div :style="{
            pointerEvents: 'all',
            position: 'absolute',
            transform: `translate(-50%, -50%) translate(${path[1]}px,${path[2]}px)`,
        }">
            <div class="flex justify-between bg-gray-900 p-2 rounded-lg gap-6 border-2 border-pink-200">
                <button class="text-white" @click="toggleVisibility">Toggle text</button>
                <button class="edgebutton text-white" @click="removeEdges(id)">×</button>
            </div>

            <!-- Provide an area to edit text -->

            <div>
                <textarea  v-if="!edge.edge.textHidden" :rows="textAreaRows" type="text" v-model="edge.edge.data" class=" bg-white rounded-lg" placeholder="Type here" @mousedown.stop @wheel.stop/>

            </div>
        </div>

    </EdgeLabelRenderer>
</template>

<style>
.edgebutton {
    border-radius: 999px;
    cursor: pointer;
}

.edgebutton:hover {
    box-shadow: 0 0 0 2px pink, 0 0 0 4px #f05f75;
}
</style>
