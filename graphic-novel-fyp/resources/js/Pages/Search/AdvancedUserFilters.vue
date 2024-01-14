<template>
    <div class="border-2 border-gray-200">
        <div>
            Advanced Search
        </div>

        <div class="flex w-full">
            <TriCheckbox label="Owns a universe" class="w-1/2"/>
            <TriCheckbox label="Assigned to existing content" class="w-1/2"/>
        </div>


        <div class="w-full flex justify-center">^</div>
    </div>
</template>

<script setup>
import TriCheckbox from './TriCheckbox.vue';


</script>