<script lang="ts" setup>
import { Handle, Position, useVueFlow } from '@vue-flow/core'
import { useNode, NodeProps } from '@vue-flow/core'
import { router } from '@inertiajs/vue3';
import { computed } from 'vue'



// `useNode` returns us the node object straight from the state
// since the node obj is reactive, we can mutate it to update our nodes' data
const { node } = useNode()

const props = defineProps<NodeProps>()

const { removeNodes } = useVueFlow()

const textAreaRows = computed(() => {
    const rows = node.data ? node.data.split('\n').length : 0
    console.log(rows)
    return rows > 1 ? rows : 1
})
</script>

<script lang="ts">
export default {
    inheritAttrs: false,
}
</script>

<template>
    <div>
        <Handle type="target" :position="Position.Top" />
        <Handle type="target" :position="Position.Right" />
        <Handle type="target" :position="Position.Left" />
        <Handle type="target" :position="Position.Bottom" />

        <div class="relative p-4">
            <!-- Text node -->
            <textarea :rows="textAreaRows" type="text" v-model="node.data" class=" bg-white rounded-lg " placeholder="Type here" @mousedown.stop @wheel.stop/>
            <button @click.stop="removeNodes(id)"
                class="absolute top-[-16px] right-[-16px] bg-red-400 h-6 w-6 flex items-center justify-center rounded-full">
                x
            </button>

        </div>
    </div>
</template>

<style scoped></style>
