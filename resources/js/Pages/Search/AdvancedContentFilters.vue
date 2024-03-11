<template>
    <div class="border-2 border-gray-200">
        <div>
            Advanced Search
        </div>

        <div class="flex w-full">
            <TriCheckbox label="Include universes" @checked="updateFilter('includeUniverses', $event)" />
            <TriCheckbox label="Include series" @checked="updateFilter('includeSeries', $event)" />
            <TriCheckbox label="Include chapters" @checked="updateFilter('includeChapters', $event)" />
            <TriCheckbox label="Include pages" @checked="updateFilter('includePages', $event)" />
        </div>

        <div class="flex w-full">
            <!-- input with label -->
            <div>
                <label for="" class="block">Authors</label>
                <input type="text" class="w-full text-black"/>
            </div>

            <div>
                <label for="" class="block">Included elements</label>
                <input type="text" class="w-full text-black"/>
            </div>
        </div>

        <div class="flex w-full">
            <TriCheckbox label="Include query in parent" @checked="updateFilter('includeUniverses', $event)" />
            <TriCheckbox label="Include query in children" @checked="updateFilter('includeSeries', $event)" />
        </div>


        <div class="w-full flex justify-center">^</div>
    </div>
</template>

<script setup>
import TriCheckbox from './TriCheckbox.vue';
// import Checkbox from './Checkbox.vue';

import { defineEmits } from 'vue';

const emits = defineEmits(['updateAdvancedSearch'])

function updateFilter(filterName, value) {
    emits('updateAdvancedSearch',
        {
            name: filterName,
            value: value
        })
}

</script>