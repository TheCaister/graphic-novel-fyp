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

const parentContentType = computed(() => {
    switch (dashboardView.value) {
        case 'UniverseView':
            return 'universe'
        case 'SeriesView':
            return 'series'
        case 'ChapterView':
            return 'chapter'
        case 'PageView':
            return 'page'
        default:
            return 'universe'
    }
})

const modal = ref(null)

onClickOutside(modal, () => {
    close()
})

const form = useForm({
    elementType: '',
    contentType: parentContentType.value,
    contentId: parentContentId.value,
});

const submit = (elementType) => {
    form.elementType = elementType;
    form.post(route('elements.store'), {
        onFinish: () => {
            form.reset();
            close();
        }
    })
};
</script>

<template>
    <div>
        <div ref="modal" class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">

                <div class="flex flex-col gap-10">
                    <h2 class="text-4xl font-bold text-white ">Select type of element to create:</h2>

                    <!-- <Link :href="route('elements.create',
                        {
                            elementType: 'SimpleText',
                            contentId: parentContentId,
                            contentType: parentContentType
                        })" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Simple Text
                    </Link> -->

                    <button @click="submit('SimpleText')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Simple Text
                    </button>
                    <button @click="form.elementType = 'MindMap'"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Mind Map
                    </button>
                    <Link :href="route('elements.create')"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Panel Planner
                    </Link>
                </div>

        </div>
    </div>
</template>