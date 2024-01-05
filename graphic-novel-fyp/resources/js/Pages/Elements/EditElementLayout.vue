<script setup>
import { Head } from '@inertiajs/vue3';
import EditSimpleText from './SimpleText/EditSimpleText.vue';
import EditMindMap from './Mindmap/EditMindMap.vue';
import EditPanelPlanner from './PanelPlanner/EditPanelPlanner.vue';
import { defineProps, onMounted, computed } from 'vue'
import { useForm} from '@inertiajs/vue3';

const props = defineProps({
    element: {
        type: Object,
    },
});

const DashboardViewComponent = computed(() => {
    // Switch statement to return the correct dashboard view
    switch (props.element.value) {
        case 'App\Models\SimpleTextElement':
            return EditSimpleText
        case 'App\Models\MindMapElement':
            return EditMindMap
        case 'App\Models\PanelPlannerElement':
            return EditPanelPlanner
        default:
            return EditSimpleText
    }
})

const form = useForm({
    // element_name: '',
    // upload: '',
    // content: {},

    element: {},
    upload: '',
});

function updateForm(element){
    // console.log('test')
    form.element = element

    // console.log(form.element)
}

function saveElement(assign = false){
    form.put(route('elements.update', {element: props.element.element_id, assign: assign})), {
        // preserveScroll: true,
        onSuccess: () => {
            console.log('success')

        }}}

onMounted(async () => {
})

// function updateDashboard(dashboardViewString, parentContentId) {
//     parentContentIdNumber.value = parentContentId
//     dashboardView.value = dashboardViewString
// }
</script>

<template>
    <Head title="Element" />

    <div>
        <!-- Buttons for saving, going back -->
        <div class="flex mb-8 w-full">
            <div class="flex justify-between w-full">
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Back
                </button>
                <div>
                    <button @click="saveElement()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Save
                </button>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Save and Assign
                </button>
                </div>
            </div>
        </div>

        <!-- Thumbnail with option to edit name -->
        <div class="w-full h-64 flex items-center p-6" style="background-image: url('https://images.unsplash.com/photo-1481349518771-20055b2a7b24?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8cmFuZG9tfGVufDB8fDB8fHww')">
            <div class="flex items-center gap-8">
                <img class="w-32 h-32 shadow-2xl rounded-lg" src="https://images.unsplash.com/photo-1481349518771-20055b2a7b24?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8cmFuZG9tfGVufDB8fDB8fHww" alt="" srcset="">

                <p>
                    Title...
                </p>
            </div>
        
        </div>

        <!-- Element Edit View -->
        <KeepAlive>
            <component :is="DashboardViewComponent" :element="props.element" @updateElement="updateForm"/>
        </KeepAlive>
    </div>
</template>