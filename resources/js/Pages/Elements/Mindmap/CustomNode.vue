<script lang="ts" setup>
import { Handle, Position } from '@vue-flow/core'
import { NodeResizer } from '@vue-flow/node-resizer'

// make sure to include the necessary styles!
import '@vue-flow/node-resizer/dist/style.css'
import { ref } from 'vue'

const counter = ref(0)
</script>

<template>
    <NodeResizer min-width="100" min-height="30" />
    <div class="custom-node">
        <Handle type="target" :position="Position.Top" />
        <button @click="counter++">Increment</button>
        <div v-for="count of counter">{{ count }}</div>
    </div>
</template>

<style scoped>
.custom-node {
    height: 100%;
    width: 100%;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: white;
    border: 1px solid black;
    border-radius: 4px;
}
</style>
