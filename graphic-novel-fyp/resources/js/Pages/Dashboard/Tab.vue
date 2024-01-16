<template>
    <div v-show="title == selectedTitle">
        <slot :update="update"/>
    </div>

</template>

<script setup>
import { inject, ref, useSlots, defineEmits } from 'vue'

const slots = useSlots()

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
})

const emits = defineEmits(['updateSize'])

const test = ref(0)

// const listSize = ref(0)

const selectedTitle = inject('selectedTitle')

// function test(){
//     console.log('test')
// }

function update(event){
    console.log(event)
    console.log('the tab has been updated')
    emits('updateSize', event)
}

</script>