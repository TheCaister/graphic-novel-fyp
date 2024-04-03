<script setup>
import { Head } from '@inertiajs/vue3';
import EditSimpleText from './SimpleText/EditSimpleText.vue';
import EditMindMap from './Mindmap/EditMindMap.vue';
import EditPanelPlanner from './PanelPlanner/EditPanelPlanner.vue';
import ElementThumbnailModal from './ElementThumbnailModal.vue';
import { onMounted, computed, ref, watch, provide } from 'vue'
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    element: {
        type: Object,
    },
    parentContentType: {
        type: String,
    },
    parentContentId: {
        type: Number,
    },
    errors: {
        type: Object,
    },
    auth: {
        type: Object,
    },
    ziggy: {
        type: Object,
    }
});

const DashboardViewComponent = computed(() => {
    switch (props.element.derived_element_type) {
        case "App\\Models\\SimpleTextElement":
            return EditSimpleText
        case "App\\Models\\MindmapElement":
            return EditMindMap
        case "App\\Models\\PanelPlannerElement":
            return EditPanelPlanner
        default:
            return EditSimpleText
    }
})

const isEditThumbnailModalOpen = ref(false)

const isHeaderOpen = ref(true)

const form = useForm({
    element: props.element,
    childrenElements: [],
    upload: '',
    assign: false,
    contentType: '',
    content_id: '',
    preSelectedElements: [],
});

provide('contentType', form.contentType)
provide('contentId', form.content_id)

const elementThumbnailImage = computed(() => {
    return form.element.element_thumbnail ? form.element.element_thumbnail : '/assets/black_page.jpg';
})

function updateChildrenElements(elements) {
    form.childrenElements = elements
}

function saveElement(assign = false) {
    // form.element = props.element

    console.log(form)

    form.assign = assign
    form.contentType = assign ? props.parentContentType : ''
    form.content_id = assign ? props.parentContentId : ''
    form.preSelectedElements = [{
        element_id: form.element.element_id,
        checked: true
    }]
    form.childrenElements = form.childrenElements


    form.put(route('elements.update', form.element.element_id), {
    },
        {
            onSuccess: () => {
                console.log('success')
                console.log(form.element)
            },
            onError: (e) => {
                console.log(e)
            }
        })
}

function updateUpload(upload) {
    form.upload = upload

    saveElement()
}

onMounted(() => {

    // console.log(JSON.parse(JSON.stringify(props.element)))
    // console.log(form.element)

    // form.element = JSON.parse(JSON.stringify(form.element))

    if (typeof form.element.content === 'string') {
        form.element.content = JSON.parse(form.element.content)
    }

    // console.log(form.element)

    // if form,element,content is null, set it to empty object
    // if (form.element.content === null) {
    //     form.element.content = {}
    // }


})

function back() {
    window.history.back();
}
</script>

<template>

    <Head title="Element" />

    <Teleport to="body">
        <Transition name="modal" class="z-50">
            <ElementThumbnailModal v-if="isEditThumbnailModalOpen" @closeModal="isEditThumbnailModalOpen = false"
                @save-thumbnail="updateUpload" :element="props.element"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60" />
        </Transition>
    </Teleport>

    <div class="relative">
        <!-- Buttons for saving, going back -->
        <div class="flex mb-8 w-full">
            <div class="flex justify-between w-full">
                <button @click="back" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Back
                </button>
                <div>
                    <button @click="saveElement()"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Save
                    </button>
                    <button @click="saveElement(true)"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Save and Assign
                    </button>
                </div>
            </div>
            <div>
                <button class="text-white" @click="console.log(form)">
                    check form
                </button>
            </div>
        </div>

        <!-- Thumbnail with option to edit name -->

        <div>
            <div class="absolute z-10 w-full">
                    <div v-if="isHeaderOpen" class=" h-64 flex items-center p-6"
                        :style="'background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.5)), url(' + elementThumbnailImage + ')'">
                        <div class="flex items-center gap-8">
                            <button @click="isEditThumbnailModalOpen = true">
                                <img class="w-32 h-32 shadow-2xl rounded-lg hover:scale-105 transition-transform duration-300"
                                    :src="elementThumbnailImage" alt="" srcset="">
                            </button>
                            <div>
                                <input id="element_name" type="text"
                                    class="mt-1 block w-full bg-transparent border-none rounded-lg shadow-xl text-white text-2xl hover:scale-105 transition-transform duration-300"
                                    v-model="form.element.element_name" required autofocus />
                            </div>
                        </div>
                    </div>

                <KeepAlive>

                    <component :is="DashboardViewComponent" v-model="form.element"
                        @updateChildrenElementIDs="updateChildrenElements" />
                </KeepAlive>

                <button @click="isHeaderOpen = !isHeaderOpen"
                    class="bg-black p-2 text-white text-xl rounded-lg absolute top-[-60px] left-1/2 -translate-x-1/2 z-50">Toggle
                    Header</button>
            </div>


            <!-- <component :is="DashboardViewComponent" v-model="form.element" :element="form.element" 
                @updateElement="updateForm"
                    @updateChildrenElementIDs="updateChildrenElements" /> -->


        </div>


    </div>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: all 0.25s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: scale(1.1);
}
</style>