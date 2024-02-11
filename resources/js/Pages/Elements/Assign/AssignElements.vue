<template>
    <div class="p-8 border border-white rounded-lg text-white">
        <!-- Content title here, with option  to go back... -->
        <div class="flex">
            <button @click="updatePreSelectedElementList">
                Refresh
            </button>
            <Link
                :href="route('elements.assign.get-parent', { type: parentContent.type, content_id: parentContent.content_id })"
                v-if="parentContent.type !== 'universes'">
            <span class="material-symbols-outlined dark">
                chevron_left
            </span>
            </Link>


            <div>
                <!-- Content Title -->
                {{ parentContent.content_name }}
            </div>
        </div>

        <!-- Two lists, one for content, one for elements -->
        <div class="flex justify-around">
            <div class="flex flex-col items-center">
                <SearchBar />
                <ContentList :subContentList="subContentList"
                    @content-checked="(event) => updateSelectedContentList(event)" />



                <button @click="goBack" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Back
                </button>
            </div>
            <div class="flex flex-col items-center">
                <SearchBar />

                <ElementsList v-model="elements" @element-checked="(event) => updateSelectedElementList(event)" />

                <button @click="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Save
                </button>
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
import { onMounted, ref } from 'vue'


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
    preSelectedElements: {
        type: Array,
        required: true
    }
})

const elements = ref(props.elementList)

const form = useForm({
    content_type: props.parentContent.type,
    content_id: props.parentContent.content_id,
    selectedContentList: [],
    selectedElementList: props.preSelectedElements,
})

// During submit, I'll remove any element with checked null
function submit() {
    form.post(route('elements.assign.store'), {
        onFinish: () => {
            form.reset()
            console.log('form reset')
        },
    });
}

function updateSelectedContentList(event) {
    // First, check if the content is already in the selectedContentList by comparing the contentId. If it is, remove it from the list. If it isn't, add it to the list.
    const selectedContent = form.selectedContentList.find(content => content.type === event.type && content.content_id === event.content_id)

    if (selectedContent) {
        // Remove the content from the list
        form.selectedContentList = form.selectedContentList.filter(content => content.content_id !== event.content_id)
    } else {
        // Add the content to the list
        form.selectedContentList.push({
            content_id: event.content_id,
            type: event.type,
            checked: event.checked
        })
    }
}

function updatePreSelectedElementList() {

    let type = ''
    let contentIdList = []

    switch (props.parentContent.type) {
        case 'universes':
            type = 'Series'
            break
        case 'series':
            type = 'Chapter'
            break
        case 'chapters':
            type = 'Page'
            break
    }

    let assignedElementsList = []

    // from form.selectedContentList, get the content_id of each content and add it to the contentIdList
    form.selectedContentList.forEach(content => {
        contentIdList.push(content.content_id)
    })

    APICalls.getAssignedElements(type, contentIdList).then(response => {
        console.log('response data...')
        console.log(response.data[0])
        assignedElementsList = response.data[0]
        // console.log(assignedElementsList)


        form.selectedElementList = []

        // Loop through assignedElementsList and update the preSelectedElements list
        props.elementList.forEach(element => {


            // check if element is inside assignedElementsList
            const assignedElement = assignedElementsList.find(assignedElement => assignedElement.element_id === element.element_id)

            // console.log(assignedElement)

            if (assignedElement) {

                form.selectedElementList.push({
                    element_id: element.element_id,
                    checked: true
                })
            } else {
                form.selectedElementList.push({
                    element_id: element.element_id,
                    checked: false
                })
            }
        })

        // props.preSelectedElements = form.selectedElementList
    }).catch(error => console.log(error))
}

function updateSelectedElementList(event) {

    // if element_id in event is checked === null, remove it from the list. Otherwise, add it to the list with the checked value.
    if (event.checked === null) {
        // Remove the element from the list
        form.selectedElementList = form.selectedElementList.filter(element => element.element_id !== event.element_id)
    } else {

        // if the element is already in the list, update the checked value. Otherwise, add it to the list.
        const selectedElement = form.selectedElementList.find(element => element.element_id === event.element_id)

        if (selectedElement) {
            // Update the checked value
            selectedElement.checked = event.checked
        } else {
            // Add the element to the list
            form.selectedElementList.push({
                element_id: event.element_id,
                checked: event.checked
            })
        }
    }
}

function goBack() {
    // switch on the parentContent.type
    switch (props.parentContent.type) {
        case 'universes':
            router.visit(route('universes.show', { universe: props.parentContent.content_id }))
            break;
        case 'series':
            router.visit(route('series.show', { series: props.parentContent.content_id }))
            break;
        case 'chapters':
            router.visit(route('chapters.show', { chapter: props.parentContent.content_id }))
            break;
        default:
            break;
    }
}

onMounted(() => {

    // Go through all elements in elements and see if an element is in preSelectedElements. If it is, set the checked value to true. If it isn't, set the checked value to null.
    elements.value.forEach(element => {

        const preSelectedElement = props.preSelectedElements.find(preSelectedElement => Number(preSelectedElement.element_id) === element.element_id)

        if (preSelectedElement && preSelectedElement.checked === '1') {
            element.checked = true
        } else {
            element.checked = null
        }
    })
})
</script>