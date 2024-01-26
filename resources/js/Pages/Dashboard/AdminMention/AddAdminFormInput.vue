<template>
    <div class="bg-white">
        <!-- Go through everything in model.value -->
        <div class="w-64 h-16">
            <div v-for="admin in model.value" :key="admin">
                <p>{{ admin }}</p>
            </div>
        </div>
        <AdminEditor @add-admin-id="addAdminId" />
    </div>
</template>

<script setup>
import AdminEditor from '@/Components/Editors/AdminEditor.vue';
import { watch, ref } from 'vue'

const model = defineModel({
    default: [],
})

function addAdminId(id) {
    console.log(model.value)


    if (!model.value.includes(id)) {
        model.value.push(id)
    }


}
</script>