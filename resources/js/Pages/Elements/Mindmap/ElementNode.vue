<script setup>
import { Handle, Position } from '@vue-flow/core'
import { ref } from 'vue'
import { useNode } from '@vue-flow/core'

// `useNode` returns us the node object straight from the state
// since the node obj is reactive, we can mutate it to update our nodes' data
const { node } = useNode()

function onSomeEvent() {
    node.data = {
        ...node.data,
        hello: 'world',
    }

    // you can also mutate properties like `selectable` or `draggable`
    //   node.selectable = false
    //   node.draggable = false
}

</script>

<template>
    <div class="element-node">
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
