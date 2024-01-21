<script setup>
import { Head } from '@inertiajs/vue3';
import EditSimpleText from './SimpleText/EditSimpleText.vue';
import EditMindMap from './Mindmap/EditMindMap.vue';
import EditPanelPlanner from './PanelPlanner/EditPanelPlanner.vue';
import { onMounted, computed, inject, ref } from 'vue'
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
const form = useForm({
    element: {},
    upload: '',
});

function updateForm(element) {
    // console.log('updating form...')
    // console.log(element)
    form.element = element
}

function saveElement(assign = false) {

    form.put(route('elements.update', {
        element: form.element.element_id, assign: assign, content_type: assign ? props.parentContentType : '', content_id: assign ? props.parentContentId : '', preSelectedElements: [{
            element_id: form.element.element_id,
            checked: true
        }]
    })), {
        onSuccess: () => {
            console.log('success')

        },
        onError: (e) => {
            console.log(e)
        }
    }

}

onMounted(() => {
    console.log('mounting...')
    console.log(props.element)
    console.log(form.element)
    form.element = props.element
    console.log('setting up...')
    console.log(props.element)
    console.log(form)
    console.log(form.element)
})

function back() {
    window.history.back();
}
</script>

<template>
    <Head title="Element" />

    <div>
        <!-- Buttons for saving, going back -->
        <div class="flex mb-8 w-full">
            <div class="flex justify-between w-full">
                <Link @click="back">
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Back
                </button>
                </Link>
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
        <div class="w-full h-64 flex items-center p-6"
            style="background-image: url('https://images.unsplash.com/photo-1481349518771-20055b2a7b24?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8cmFuZG9tfGVufDB8fDB8fHww')">
            <div class="flex items-center gap-8">
                <img class="w-32 h-32 shadow-2xl rounded-lg"
                    src="https://images.unsplash.com/photo-1481349518771-20055b2a7b24?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8cmFuZG9tfGVufDB8fDB8fHww"
                    alt="" srcset="">

                <!-- Input v-model form.element.element_name -->
                <div>
                    <input id="element_name" type="text" class="mt-1 block w-full bg-transparent border-none"
                        v-model="form.element.element_name" required autofocus />
                    </div>  
            </div>

        </div>

        <!-- Element Edit View -->
        <KeepAlive>
            <component :is="DashboardViewComponent" :element="props.element" @updateElement="updateForm" />
        </KeepAlive>
</div></template>