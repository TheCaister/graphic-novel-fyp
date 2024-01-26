<template>
    <div class="bg-white">
        <!-- Go through everything in model.value -->
        <div class="w-64 h-16">
            <div v-for="admin in model" :key="admin.id">
                <Tag :label="admin.username" @close-tag="removeAdmin(admin)"/>
            </div>
        </div>
        <AdminEditor @add-admin="addAdmin" />
    </div>
</template>

<script setup>
import AdminEditor from '@/Components/Editors/AdminEditor.vue';
import Tag from '@/Components/Tag.vue';
import { watch, ref } from 'vue'

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