<template>
    <div class="border-2 border-gray-200">
        <div>
            Advanced Search
        </div>

        <div class="flex w-full">
            <TriCheckbox label="Owns a universe" class="w-1/2" @checked="updateOwnsUniverse"/>
            <TriCheckbox label="Assigned to existing content" class="w-1/2" @checked="updateAssignedToExisting"/>
        </div>


        <div class="w-full flex justify-center">^</div>
    </div>
</template>

<script setup>
import TriCheckbox from './TriCheckbox.vue';

import {defineEmits} from 'vue';

const emits = defineEmits(['update:resultsList'])

function updateOwnsUniverse (checked) {
    // console.log(checked)
    emits('update:resultsList', 
    {
        name: 'ownsUniverse',
        value: checked
    })
}

function updateAssignedToExisting (checked) {
    // console.log(checked)
    emits('update:resultsList', 
    {
        name: 'assignedToExisting',
        value: checked
    })
}

</script>