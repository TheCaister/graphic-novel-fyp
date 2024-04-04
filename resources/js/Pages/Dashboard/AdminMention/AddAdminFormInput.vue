<template>
    <div>
        <PrimaryButton @click.prevent="console.log(model)">Test</PrimaryButton>
        <AdminEditor @add-admin="addAdmin" :admin-list="model" />
    </div>
</template>

<script setup>
import AdminEditor from '@/Components/Editors/AdminEditor.vue';
import { onMounted } from 'vue'

const model = defineModel({
    default: [],
})

function addAdmin(admin) {
    if (!model.value.some(obj => obj.id === admin.id)) {
  model.value.push(admin);
}
}

function removeAdmin(admin) {
    model.value = model.value.filter((item) => item.id !== admin.id)
}
</script>