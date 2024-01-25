<template>
    <div class="bg-white">
        <AdminEditor v-model="admins" />
    </div>
</template>

<script setup>
import AdminEditor from '@/Components/Editors/AdminEditor.vue';
import { watch, ref } from 'vue'

const admins = ref('')

// Define the emits for the component
const emit = defineEmits(['updateElement'])

watch(admins, (admin) => {
    // console.log(element)
    emit('updateElement', admin)
})
</script>