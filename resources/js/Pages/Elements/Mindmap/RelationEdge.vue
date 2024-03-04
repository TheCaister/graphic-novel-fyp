<script lang="ts" setup>
import { computed } from 'vue'
import type { EdgeProps } from '@vue-flow/core'
import { BaseEdge, EdgeLabelRenderer, getBezierPath, useEdge, useVueFlow } from '@vue-flow/core'

const props = defineProps<EdgeProps>()

const { removeEdges } = useVueFlow()

const edge = useEdge()

const path = computed(() => getBezierPath(props))
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
            <button class="edgebutton text-white" @click="removeEdges(id)">×</button>

            <!-- Provide an area to edit text -->

            <div>
                <input type="text" v-model="edge.edge.data" class="text-white bg-transparent" placeholder="Type here" />
                <!-- <button @click="console.log(edge)" class="text-white">Check props</button> -->

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
