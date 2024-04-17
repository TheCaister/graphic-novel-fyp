<script setup>
import { onClickOutside } from '@vueuse/core'
import { ref, inject, onMounted, computed } from 'vue'
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    parentContentId: {
        type: Number,
        default: null
    },
    parentContentType: {
        type: String,
        default: null
    },

})

const emit = defineEmits(['closeModal'])
function close() {
    emit('closeModal');
};

const dashboardView = inject('dashboardView')
const parentContentId = inject('parentContentId')

console.log(dashboardView)
console.log(parentContentId)

const modal = ref(null)

onClickOutside(modal, () => {
    close()
})

const form = useForm({
    elementType: '',
    contentType: '',
    contentId: '',
});

const submit = (elementType) => {
    // console.log(parentContentType)
    form.elementType = elementType;

    // If we pass in the props, use those, otherwise use the injects
    if (props.parentContentId && props.parentContentType) {
        form.contentType = props.parentContentType;
        form.contentId = props.parentContentId;
    }
    else {
        form.contentType = getParentContentType();
        form.contentId = parentContentId;
    }


    form.post(route('elements.store'), {
        onFinish: () => {
            form.reset();
            close();
        }
    })
};

function getParentContentType() {
    switch (dashboardView) {
        case 'UniverseView':
            return ''
        case 'SeriesView':
            return 'Universe'
        case 'ChapterView':
            return 'Series'
        case 'PageView':
            return 'Chapter'
        default:
            return ''
    }
}
</script>

<template>
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5 h-5/6">

            <div class="flex flex-col gap-10 h-full">
                <h2 class="text-4xl font-bold text-white ">Select type of element to create:</h2>

                <div class="py-4 flex flex-wrap overflow-y-scroll gap-8 h-full  justify-center">
                    <button @click="submit('SimpleText')"
                        class="text-white font-bold py-2 px-4 rounded hover:scale-105  hover:shadow-[-7px_7px_0px_0px_rgba(255,255,255,0.8)] transition-all duration-200 relative
                        "
                        style="width: 33vw;">
                        <img src="/assets/simpletext.jpg" alt="Simple Text"
                            class="absolute inset-0 w-full h-full object-cover  opacity-40 rounded-lg">
                        <span class="absolute inset-0 flex items-center justify-center w-full h-full  text-4xl font-thin">Simple Text</span>
                    </button>
                    <button @click="submit('MindMap')"
                        class="text-white font-bold py-2 px-4 rounded hover:scale-105 
                        hover:shadow-[-7px_7px_0px_0px_rgba(255,255,255,0.8)] transition-all duration-200 relative"
                        style="width: 33vw;">
                        <img src="/assets/mindmap.jpg" alt="Mind Map"
                            class="absolute inset-0 w-full h-full object-cover  
                           opacity-40 rounded-lg">
                        <span class="absolute inset-0 flex items-center justify-center w-full h-full text-4xl font-thin">Mind Map</span>
                    </button>

                    <button @click="submit('PanelPlanner')"
                        class="text-white font-bold py-2 px-4 rounded hover:scale-105 
                        hover:shadow-[-7px_7px_0px_0px_rgba(255,255,255,0.8)] 
                        transition-all duration-200 relative"
                        style="width: 33vw;">
                        <img src="/assets/panelplanner.jpg" alt="Panel Planner"
                            class="absolute inset-0 w-full h-full object-cover  opacity-40 rounded-lg">
                        <span class="absolute inset-0 flex items-center justify-center w-full h-full text-4xl font-thin">Panel Planner</span>
                    </button>
                    <!-- <button @click="submit('MindMap')"
                        class="text-white font-bold py-2 px-4 rounded hover:scale-105 transition-all duration-200" style="width: 33vw;
                        background-image: url('/assets/simpletext.jpg');"
                        >
                        Mind Map
                    </button>
                    <button @click="submit('PanelPlanner')"
                        class="text-white font-bold py-2 px-4 rounded hover:scale-105 transition-all duration-200" style="width: 33vw;
                        background-image: url('/assets/simpletext.jpg');">
                        Panel Planner
                    </button> -->
                </div>
            </div>

        </div>
    </div>
</template>