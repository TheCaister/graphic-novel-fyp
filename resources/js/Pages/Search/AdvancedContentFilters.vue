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
                <!-- <label for="" class="block">Authors</label>
                <input type="text" class="w-full text-black" /> -->
                Authors to be updated!
            </div>

            <div>
                <!-- <label for="" class="block">Included elements</label>
                <input type="text" class="w-full text-black"/> -->

                <IncludedElementsEditor class="w-64" @addMentionItem="addElement" @removeMentionItem="removeElement" :includedElements="model?.value?.includedElements || []" :key="model?.value?.includedElements"/>
            </div>
        </div>

        <div class="flex w-full">
            Query in parent and children to be updated!
            <!-- <TriCheckbox label="Include query in parent" @checked="updateFilter('includeUniverses', $event)" />
            <TriCheckbox label="Include query in children" @checked="updateFilter('includeSeries', $event)" /> -->
        </div>


        <div class="w-full flex justify-center">^</div>
    </div>
</template>

<script setup>
import TriCheckbox from './TriCheckbox.vue';
import IncludedElementsEditor from '../../Components/Editors/IncludedElementsEditor.vue'
// import Checkbox from './Checkbox.vue';

import { defineEmits } from 'vue';

const emits = defineEmits(['updateAdvancedSearch'])

const model = defineModel()

function updateFilter(filterName, value) {
    emits('updateAdvancedSearch',
        {
            name: filterName,
            value: value
        })
}

function addElement(event) {
    console.log(event)
    if (!model.value.includedElements) {
        model.value.includedElements = [];
    }
    model.value.includedElements.push(event)
    console.log(model.value)
}

function removeElement(event) {
    console.log('removing')

    model.value.includedElements = model.value.includedElements.filter((element) => element.id !== event)

}

</script>