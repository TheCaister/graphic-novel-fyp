<template>
    <div class="p-12 text-white" style="height: 110vh;">
        <div class="border-4 border-white rounded-lg p-8">
            <!-- <button @click="console.log(content)">
                Test
            </button> -->
            <!-- Content title here, with option  to go back... -->
            <div class="flex text-4xl">
                <Link
                    :href="route('elements.assign.get-parent', { type: parentContent.type, content_id: parentContent.content_id })"
                    v-if="parentContent.type !== 'Universe'">
                <span class="material-symbols-outlined dark">
                    chevron_left
                </span>
                </Link>
                <div>
                    {{ parentContent.content_name }}
                </div>
                <div class="flex-grow"></div>
                <PrimaryButton @click="toggleAll">Toggle all elements</PrimaryButton>
            </div>
            <!-- Two lists, one for content, one for elements -->
            <div class="flex justify-around  h-4/5 ">
                <div class="flex flex-col w-2/5 ">
                    <!-- <SearchBar /> -->
                    <ContentList v-model="content" class="px-4"/>
                    <!-- empty div that fills up flex -->
                    <div class="flex-grow"></div>
                    <PrimaryButton @click="goBack" class="mt-8">Back</PrimaryButton>
                </div>
                <!-- A dotted vertical grey line -->
                <div class="border-2 border-dashed border-gray-700"></div>
                <div class="flex flex-col w-2/5">
                    <!-- <SearchBar /> -->
                    <!-- <button @click="toggleAll">
                        Toggle all
                    </button> -->

                    <ElementsList v-model="elements"  class="px-4"/>
                    <div class="flex-grow"></div>
                    <!-- <button @click="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save
                    </button> -->
                    <PrimaryButton @click="submit" class="mt-8">Save</PrimaryButton>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import ElementsList from './ElementsList.vue';
import ContentList from './ContentList.vue';
import SearchBar from './SearchBar.vue';
import APICalls from '@/Utilities/APICalls'
import { onMounted, ref, watch } from 'vue'


const props = defineProps({
    parentContent: {
        type: Object,
        required: true
    },
    subContentList: {
        type: Array,
        required: true
    },
    elementList:
    {
        type: Array,
        required: true
    },
    preSelectedContent: {
        type: Array,
        required: true
    },
    preSelectedElements: {
        type: Array,
        required: true
    }
})

const elements = ref(props.elementList)
const content = ref(props.subContentList)

const form = useForm({
    contentType: props.parentContent.type,
    content_id: props.parentContent.content_id,
    selectedContentList: content.value,
    selectedElementList: [],
})

watch(content.value, () => {
    updatePreSelectedElementList()
})

// During submit, I'll remove any element with checked null
function submit() {

    // Remove any element  with checked null in elements and set it to form.selectedElementList
    form.selectedElementList = elements.value.filter(element => element.checked !== null)

    // in form.selectedContentlist, filter out any content with checked === false
    form.selectedContentList = content.value.filter(content => content.checked === true)


    form.post(route('elements.assign.store'), {
        onFinish: () => {
            form.reset()
            updatePreSelectedElementList()
            console.log('form reset')
        },
    });
}

function updatePreSelectedElementList() {


    let contentList = content.value.filter(content => content.checked === true)
    let contentIdList = []

    // if form.selectedContentList is empty, return
    if (contentList.length === 0) {

        elements.value.forEach(element => element.alreadyAttached = false)

        return
    }

    let type = ''

    switch (props.parentContent.type) {
        case 'Universe':
            type = 'Series'
            break
        case 'Series':
            type = 'Chapter'
            break
        case 'Chapter':
            type = 'Page'
            break
    }

    let assignedElementsList = []

    // from form.selectedContentList, get the content_id of each content and add it to the contentIdList
    contentList.forEach(content => {
        contentIdList.push(content.content_id)
    })

    APICalls.getAssignedElements(type, contentIdList).then(response => {
        assignedElementsList = response.data

        // Loop through assignedElementsList and update the preSelectedElements list
        elements.value.forEach(element => {
            // check if element is inside assignedElementsList
            const assignedElement = assignedElementsList.find(assignedElement => assignedElement.element_id === element.element_id)

            if (assignedElement) {
                element.alreadyAttached = true
            }
            else{
                element.alreadyAttached = false
            }
        })

    }).catch(error => console.log(error))
}

function goBack() {
    // switch on the parentContent.type
    switch (props.parentContent.type) {
        case 'Universe':
            router.visit(route('universes.show', { universe: props.parentContent.content_id }))
            break;
        case 'Series':
            router.visit(route('series.show', { series: props.parentContent.content_id }))
            break;
        case 'Chapter':
            router.visit(route('chapters.show', { chapter: props.parentContent.content_id }))
            break;
        default:
            break;
    }
}

function toggleAll() {

    // If there is a mix of checked and unchecked elements, set all elements to checked = true. If all elements are checked, set all elements to checked = false. If all elements are check = false, set all elements to checked = null.
    const checkedElements = elements.value.filter(element => element.checked === true)
    const uncheckedElements = elements.value.filter(element => element.checked === false)

    if (checkedElements.length === elements.value.length) {
        elements.value.forEach(element => element.checked = false)
    } else if (uncheckedElements.length === elements.value.length) {
        elements.value.forEach(element => element.checked = null)
    }
    else {
        elements.value.forEach(element => element.checked = true)
    }
}

onMounted(() => {
    console.log(props.preSelectedElements)

    // Go through all elements in elements and see if an element is in preSelectedElements. If it is, set the checked value to true. If it isn't, set the checked value to null.
    elements.value.forEach(element => {

        const preSelectedElement = props.preSelectedElements.find(preSelectedElement => Number(preSelectedElement.element_id) === element.element_id)

        if(preSelectedElement){
            element.checked = true
            console.log('mitsuketta!!')
            console.log(preSelectedElement.checked)
        }
        else{
            element.checked = null
        }

        // if (preSelectedElement && preSelectedElement.checked === '1') {
        //     element.checked = true
        // } else {
        //     element.checked = null
        // }
    })

    // foreach in props.subcontentlist, check if the content is in preSelectedContent. If it is, set the checked value to true.
    content.value.forEach(content => {

        const preSelectedContent = props.preSelectedContent.find(preSelectedContent => preSelectedContent === content.content_id)

        if (preSelectedContent) {
            content.checked = true
        }
    })
})
</script>