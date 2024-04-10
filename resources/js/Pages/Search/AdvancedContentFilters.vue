<template>
    <div class="border-2 border-gray-500 rounded-lg p-6">
        <div class="text-3xl">
            Advanced Search
        </div>

        <div class="text-white">
            <button @click="console.log(model)">
                Print model
            </button>
        </div>

        <div class="flex w-full gap-8">
            <TriCheckbox label="Include universes" @checked="updateFilter('includeUniverses', $event)" class="w-1/4"/>
            <TriCheckbox label="Include series" @checked="updateFilter('includeSeries', $event)" class="w-1/4"/>
            <TriCheckbox label="Include chapters" @checked="updateFilter('includeChapters', $event)" class="w-1/4"/>
            <TriCheckbox label="Include pages" @checked="updateFilter('includePages', $event)" class="w-1/4"/>

        </div>

        <div class="flex w-full">
            <!-- <div>
                <label for="" class="block">Authors</label>
                <input type="text" class="w-full text-black" />
                Authors to be updated!
            </div> -->

            <div class="w-full">
                <!-- <IncludedElementsEditor  addMentionItem="addElement" @removeMentionItem="removeElement"
                @addMentionItem="addElement"
                    :includedElements="model?.value?.includedElements || []" :key="model?.value?.includedElements" /> -->

                    <IncludedElementsEditor  addMentionItem="addElement" @removeMentionItem="removeElement"
                @addMentionItem="addElement"
                    v-model="elementList"
                    :key="model?.value?.includedElements" />
            </div>
        </div>

        <!-- <div class="flex w-full">
            Query in parent and children to be updated!
            <TriCheckbox label="Include query in parent" @checked="updateFilter('includeUniverses', $event)" />
            <TriCheckbox label="Include query in children" @checked="updateFilter('includeSeries', $event)" />
        </div> -->


        <div class="w-full flex justify-center">^</div>
    </div>
</template>

<script setup>
import TriCheckbox from './TriCheckbox.vue';
import IncludedElementsEditor from '../../Components/Editors/IncludedElementsEditor.vue'
import { defineEmits, ref } from 'vue';

const emits = defineEmits(['updateAdvancedSearch'])

const model = defineModel()

const elementList = ref(model?.value?.includedElements || [])

let contentTypes = []

function updateFilter(filterName, value) {
    switch (filterName) {
        case 'includeUniverses':

            updateContentTypes({
                contentType: 'Universes',
                include: value
            })

            emits('updateAdvancedSearch', {
                name: 'contentTypes',
                value: contentTypes
            })
            return;
        case 'includeSeries':

            updateContentTypes({
                contentType: 'Series',
                include: value
            })

            emits('updateAdvancedSearch', {
                name: 'contentTypes',
                value: contentTypes
            })
            return;
        case 'includeChapters':

            updateContentTypes({
                contentType: 'Chapters',
                include: value
            })

            emits('updateAdvancedSearch', {
                name: 'contentTypes',
                value: contentTypes
            })
            return;
        case 'includePages':

            updateContentTypes({
                contentType: 'Pages',
                include: value
            })

            emits('updateAdvancedSearch', {
                name: 'contentTypes',
                value: contentTypes
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

function updateContentTypes(value) {
    if (value.include === null) {
        contentTypes = contentTypes.filter((content) => content.contentType !== value.contentType)
    } else {

        // if the value is already in the array, set it to the new value
        let index = contentTypes.findIndex((content) => content.contentType === value.contentType)
        if (index !== -1) {
            contentTypes[index] = value
        } else {
            contentTypes.push(value)
        }
    }
}

</script>