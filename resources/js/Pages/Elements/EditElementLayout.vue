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

function updateChildrenElements(elements){
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

    <div>
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
        <div class="w-full h-64 flex items-center p-6" :style="'background-image: url(' + elementThumbnailImage + ')'">
            <div class="flex items-center gap-8">
                <button @click="isEditThumbnailModalOpen = true">
                    <img class="w-32 h-32 shadow-2xl rounded-lg hover:scale-105 transition-transform duration-300"
                        :src="props.element.element_thumbnail ? props.element.element_thumbnail : '/assets/black_page.jpg'"
                        alt="" srcset="">
                </button>

                <!-- Input v-model form.element.element_name -->
                <div>
                    <input id="element_name" type="text" class="mt-1 block w-full bg-transparent border-none"
                        v-model="form.element.element_name" required autofocus />
                </div>
            </div>

        </div>

        <!-- Element Edit View -->
        <KeepAlive>
            <component :is="DashboardViewComponent" v-bind:element="props.element" @updateElement="updateForm" @updateChildrenElementIDs="updateChildrenElements"/>
        </KeepAlive>
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