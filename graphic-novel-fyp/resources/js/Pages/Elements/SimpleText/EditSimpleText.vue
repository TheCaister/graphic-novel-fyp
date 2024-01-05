<template>
    <!-- <SimpleEditor v-model="form.content" class="bg-white" /> -->

    <div class="bg-white">
        <SimpleEditor v-model="element.content" />
    </div>
</template>

<script setup>
import SimpleEditor from '@/Components/Editors/SimpleEditor.vue';
import {defineProps, defineEmits, watch} from 'vue'

const props = defineProps({
    element: {
        type: Object,
        required: true
    }
})

// Define the emits for the component
const emit = defineEmits(['updateElement'])

watch(props.element, (element) => {
    emit('updateElement', element)
    // console.log(element)
})


</script>