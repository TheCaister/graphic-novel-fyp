<template>
    <div>
        <!-- <PrimaryButton @click.prevent="console.log(model)">Test</PrimaryButton> -->
        <!-- <AdminEditor @add-admin="addAdmin" :admin-list="model" @removeAdmin="removeAdmin"/> -->

        <AdminEditor @add-admin="addAdmin" v-model="model" @removeAdmin="removeAdmin" />
    </div>
</template>

<script setup>
import AdminEditor from '@/Components/Editors/AdminEditor.vue';
// import { onMounted } from 'vue'

const model = defineModel({
    default: [],
})

function addAdmin(admin) {
    if (!model.value.some(obj => obj.id === admin.id)) {
        console.log('lets go')
        model.value.push(admin);
    }
}

function removeAdmin(admin) {
    model.value = model.value.filter((item) => item.id !== admin)
}
</script>