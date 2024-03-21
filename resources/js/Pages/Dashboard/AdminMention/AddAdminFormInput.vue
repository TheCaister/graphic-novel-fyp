<template>
    <div>
        <AdminEditor @add-admin="addAdmin" />
    </div>
</template>

<script setup>
import AdminEditor from '@/Components/Editors/AdminEditor.vue';

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