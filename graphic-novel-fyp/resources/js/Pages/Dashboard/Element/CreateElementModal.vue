<script setup>
import { onClickOutside } from '@vueuse/core'
import { ref, inject, onMounted, computed } from 'vue'
import { useForm } from '@inertiajs/vue3';

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
    form.contentType = getParentContentType();
    form.contentId = parentContentId;
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
            return 'universes'
        case 'ChapterView':
            return 'series'
        case 'PageView':
            return 'chapters'
        default:
            return ''
    }

}
</script>

<template>
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">

            <div class="flex flex-col gap-10">
                <h2 class="text-4xl font-bold text-white ">Select type of element to create:</h2>

                <button @click="submit('SimpleText')"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Simple Text
                </button>
                <button @click="submit('MindMap')"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Mind Map
                </button>
                <button @click="submit('MindMap')"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Panel Planner
                </button>
            </div>

        </div>
    </div>
</template>