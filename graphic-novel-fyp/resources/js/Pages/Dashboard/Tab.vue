<template>
    <div v-show="title == selectedTitle">
        <slot :update="update"/>
    </div>

</template>

<script setup>
import { inject, useSlots, defineEmits } from 'vue'

const slots = useSlots()

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
})

const emits = defineEmits(['updateSize'])

const selectedTitle = inject('selectedTitle')
function update(event){
    console.log('the tab has been updated')
    emits('updateSize', event)
}

</script>