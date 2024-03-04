<script setup>
import { Head } from '@inertiajs/vue3';
import EditSimpleText from './SimpleText/EditSimpleText.vue';
import EditMindMap from './Mindmap/EditMindMap.vue';
import EditPanelPlanner from './PanelPlanner/EditPanelPlanner.vue';
import ElementThumbnailModal from './ElementThumbnailModal.vue';
import { onMounted, computed, ref, watch } from 'vue'
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
    element: {
        content: {},
    },
    childrenElements: [],
    upload: '',
});

const elementThumbnailImage = computed(() => {
    return form.element.element_thumbnail ? form.element.element_thumbnail : '/assets/black_page.jpg'

})

watch(form.element.content, (newVal) => {
    // parse newVal if it is a string
    if (typeof newVal === 'string') {
        form.element.content = JSON.parse(newVal)
    }

})

function updateForm(element) {
    form.element = element
}

function updateChildrenElements(elements) {
    form.childrenElements = elements
}

function saveElement(assign = false) {

    console.log(form.childrenElements)

    form.put(route('elements.update', {
        element: form.element.element_id,
        assign: assign, content_type: assign ? props.parentContentType : '',
        content_id: assign ? props.parentContentId : '',
        preSelectedElements: [{
            element_id: form.element.element_id,
            checked: true
        }],
        childrenElements: form.childrenElements
    })), {
        onSuccess: () => {
            console.log('success')
        },
        onError: (e) => {
            console.log(e)
        }
    }
}

function updateUpload(upload) {
    form.upload = upload

    saveElement()
}

onMounted(() => {

    if (typeof props.element.content === 'string') {
        props.element.content = JSON.parse(props.element.content)
    }

    form.element = props.element
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
        </div>

        <!-- Thumbnail with option to edit name -->

        <div>
            <div class="absolute z-10 w-full">
                <div v-if="isHeaderOpen" class=" h-64 flex items-center p-6"
                    :style="'background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.5)), url(' + elementThumbnailImage + ')'">
                    <div class="flex items-center gap-8">
                        <button @click="isEditThumbnailModalOpen = true">
                            <img class="w-32 h-32 shadow-2xl rounded-lg hover:scale-105 transition-transform duration-300"
                                :src="props.element.element_thumbnail ? props.element.element_thumbnail : '/assets/black_page.jpg'"
                                alt="" srcset="">
                        </button>
                        <div>
                            <input id="element_name" type="text"
                                class="mt-1 block w-full bg-transparent border-none rounded-lg shadow-xl text-white"
                                v-model="form.element.element_name" required autofocus />
                        </div>
                    </div>
                </div>

                <button @click="isHeaderOpen = !isHeaderOpen"
                    class="bg-black p-2 text-white rounded-lg absolute -translate-y-1/2 left-1/2 -translate-x-1/2 z-50">Toggle
                    Header</button>
            </div>

            <!-- Element Edit View -->
            <KeepAlive>
                <component :is="DashboardViewComponent" v-bind:element="props.element" @updateElement="updateForm"
                    @updateChildrenElementIDs="updateChildrenElements" />
            </KeepAlive>
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