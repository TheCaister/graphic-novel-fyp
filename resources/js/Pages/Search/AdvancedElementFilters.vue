<template>
    <div class="border-2 border-gray-200">
        <div>
            Advanced Search
        </div>

        <div class="flex w-full">
            <TriCheckbox label="Include simple text elements" @checked="updateFilter('includeSimpleText', $event)" />
            <TriCheckbox label="Include mindmap elements" @checked="updateFilter('includeMindmap', $event)" />
            <TriCheckbox label="Include panel planner elements" @checked="updateFilter('includePanelPlanner', $event)" />
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

        <!-- <div class="flex w-full">
            <div>
                <label for="" class="block">Present on/from</label>
                <input type="text" class="w-full text-black"/>
            </div>

            <div>
                <label for="" class="block">Present before</label>
                <input type="text" class="w-full text-black"/>
            </div>
        </div> -->

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


    switch (filterName) {
        case 'includeSimpleText':
            emits('updateAdvancedSearch',
                {
                    name: 'derivedElementType',
                    value: value
                })
            break;
        case 'includeMindmap':
            emits('updateAdvancedSearch',
                {
                    name: 'derivedElementType',
                    value: value
                })
            break;
        case 'includePanelPlanner':
            emits('updateAdvancedSearch',
                {
                    name: 'derivedElementType',
                    value: value
                })
            break;
        default:
            break;
    }

    emits('updateAdvancedSearch',
        {
            name: filterName,
            value: value
        })
}

</script>