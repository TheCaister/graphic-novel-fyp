<template>
    <div class="border-2 border-gray-500 rounded-lg p-6">
        <div class="text-3xl">
            Advanced Search
        </div>

        <div class="flex w-full">
            <!-- <TriCheckbox label="Owns a universe" class="w-1/2" @checked="updateOwnsUniverse"/>
            <TriCheckbox label="Assigned to existing content" class="w-1/2" @checked="updateAssignedToExisting"/> -->

            To be updated!
        </div>


        <div class="w-full flex justify-center">^</div>
    </div>
</template>

<script setup>
import TriCheckbox from './TriCheckbox.vue';

const emits = defineEmits(['updateAdvancedSearch'])


function updateOwnsUniverse (checked) {
    // console.log(checked)
    emits('updateAdvancedSearch', 
    {
        name: 'hasUniverse',
        value: checked
    })
}

function updateAssignedToExisting (checked) {
    // console.log(checked)
    emits('updateAdvancedSearch', 
    {
        name: 'assignedToExisting',
        value: checked
    })
}

</script>