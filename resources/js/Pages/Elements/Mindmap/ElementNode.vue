<script lang="ts" setup>
import { Handle, Position, useVueFlow } from '@vue-flow/core'
import { useNode, NodeProps } from '@vue-flow/core'

// `useNode` returns us the node object straight from the state
// since the node obj is reactive, we can mutate it to update our nodes' data
const { node } = useNode()

const props = defineProps<NodeProps>()

const { removeNodes } = useVueFlow()
</script>

<script lang="ts">
export default {
    inheritAttrs: false,
}
</script>

<template>
    <div>
        <Handle type="target" :position="Position.Top" :x="50" />
        <Handle type="target" :position="Position.Right" />
        <Handle type="target" :position="Position.Left" />
        <Handle type="target" :position="Position.Bottom" />


        <div class="flex flex-col items-center text-white relative">
            <div>
                <img :src="node.data.element_thumbnail" alt=""
                    class="w-16 h-16 rounded-lg shadow-lg hover:scale-105 transition-transform duration-300">
            </div>

            <button @click="removeNodes(id)"
                class="absolute top-0 right-0 bg-red-400 h-6 w-6 flex items-center justify-center rounded-full">
                x
            </button>

        </div>
    </div>
    <div class="text-white">
        {{ node.data.element_name }}
    </div>
</template>

<style scoped></style>
