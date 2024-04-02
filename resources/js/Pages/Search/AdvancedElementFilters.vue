<template>
    <div class="border-2 border-gray-500 rounded-lg p-6">
        <div>
            Advanced Search
        </div>

        <div class="flex w-full  gap-8">
            <TriCheckbox label="Include simple text elements" @checked="updateFilter('includeSimpleText', $event)"  class="w-1/3"/>
            <TriCheckbox label="Include mindmap elements" @checked="updateFilter('includeMindmap', $event)" class="w-1/3"/>
            <TriCheckbox label="Include panel planner elements"
                @checked="updateFilter('includePanelPlanner', $event)" class="w-1/3"/>
        </div>

        <div class="flex w-full">

            <!-- <div>
                <label for="" class="block">Authors</label>
                <input type="text" class="w-full text-black" />
            </div>

            <div>
                <label for="" class="block">Included elements</label>
                <input type="text" class="w-full text-black" />
            </div> -->
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
            <!-- <TriCheckbox label="Include query in parent" @checked="updateFilter('includeUniverses', $event)" />
            <TriCheckbox label="Include query in children" @checked="updateFilter('includeSeries', $event)" /> -->
        </div>


        <div class="w-full flex justify-center">^</div>
    </div>
</template>

<script setup>
import TriCheckbox from './TriCheckbox.vue';

const emits = defineEmits(['updateAdvancedSearch'])

let derivedElementTypes = []

function updateFilter(filterName, value) {
    switch (filterName) {
        case 'includeSimpleText':

            updateDerivedElementTypes({
                derivedElementType: 'SimpleTextElement',
                include: value
            })

            emits('updateAdvancedSearch', {
                name: 'derivedElementTypes',
                value: derivedElementTypes
            })
            return;
        case 'includeMindmap':

            updateDerivedElementTypes({
                derivedElementType: 'MindmapElement',
                include: value
            })

            emits('updateAdvancedSearch', {
                name: 'derivedElementTypes',
                value: derivedElementTypes
            })
            return;
        case 'includePanelPlanner':

            updateDerivedElementTypes({
                derivedElementType: 'PanelPlannerElement',
                include: value
            })

            emits('updateAdvancedSearch', {
                name: 'derivedElementTypes',
                value: derivedElementTypes
            })
            return;
        default:
            break;
    }

    emits('updateAdvancedSearch',
        {
            name: filterName,
            value: value
        })
}

function updateDerivedElementTypes(value) {
    // if value.include is null, remove the value from the array through key derivedElementType
    // if value.include is true/false, add the value to the array through key derivedElementType
    if (value.include === null) {
        derivedElementTypes = derivedElementTypes.filter((element) => element.derivedElementType !== value.derivedElementType)
    } else {

        // if the value is already in the array, set it to the new value
        let index = derivedElementTypes.findIndex((element) => element.derivedElementType === value.derivedElementType)
        if (index !== -1) {
            derivedElementTypes[index] = value
        } else {
            derivedElementTypes.push(value)
        }
    }
}

</script>