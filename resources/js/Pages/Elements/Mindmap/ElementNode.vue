<script setup>
import { Handle, Position } from '@vue-flow/core'
import { useNode } from '@vue-flow/core'

// `useNode` returns us the node object straight from the state
// since the node obj is reactive, we can mutate it to update our nodes' data
const { node } = useNode()

</script>

<template>
    <div>
        <Handle type="target" :position="Position.Top" :x="50" />
        <Handle type="target" :position="Position.Right" />
        <Handle type="target" :position="Position.Left" />
     

        <div class="flex flex-col items-center">
            <div>
                <img :src="node.data.element_thumbnail" alt="" class="w-16 h-16 rounded-lg shadow-lg hover:scale-105 transition-transform duration-300">
            </div>
            <div>
                {{ node.data.element_name }}
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
