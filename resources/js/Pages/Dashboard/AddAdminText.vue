<template>
    <div class="bg-white">
        <SimpleEditor v-model="admins" />
    </div>
</template>

<script setup>
import SimpleEditor from '@/Components/Editors/SimpleEditor.vue';
import { watch, ref } from 'vue'

const admins = ref('')

// Define the emits for the component
const emit = defineEmits(['updateElement'])

watch(admins, (admin) => {
    // console.log(element)
    emit('updateElement', admin)
})
</script>